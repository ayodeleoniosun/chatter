<?php

namespace Tests\Feature\Message;

use App\Events\Chats\MessageSent;
use App\Jobs\SaveMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\CreateUsers;

class PrivateMessageTest extends TestCase
{
    use RefreshDatabase, CreateUsers;

    public function setup(): void
    {
        parent::setup();
    }

    /** @test */
    public function cannot_send_message_unauthenticated()
    {
        $data = [
            'message'      => 'hello world',
            'recipient_id' => 4,
        ];

        $response = $this->postJson($this->apiBaseUrl . '/messages/send', $data);

        $response->assertUnauthorized();
        $this->assertEquals('Unauthenticated.', $response->getData()->message);
    }

    /** @test */
    public function cannot_send_message_with_invalid_recipient()
    {
        $this->authUser();

        $data = [
            'message' => 'hello world',
        ];

        $response = $this->postJson($this->apiBaseUrl . '/messages/send', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The recipient id field is required.', $response->getData()->errors->recipient_id[0]);
    }

    /** @test */
    public function cannot_send_message_to_yourself()
    {
        $user = $this->authUser();

        $data = [
            'message'      => 'hello world',
            'recipient_id' => $user->id,
        ];

        $response = $this->postJson($this->apiBaseUrl . '/messages/send', $data);

        $response->assertForbidden();
        $this->assertEquals('error', $response->getData()->status);
        $this->assertEquals('You cannot send message to yourself', $response->getData()->message);
    }

    /** @test */
    public function can_send_message()
    {
        Bus::fake();
        Event::fake();

        $response = $this->sendMessage();

        $response->assertCreated();
        $this->assertEquals('success', $response->getData()->status);
        $this->assertEquals('Message sent', $response->getData()->message);

        Bus::assertDispatched(SaveMessage::class);
        Event::assertDispatched(MessageSent::class);
    }

    /** @test */
    public function can_send_message_with_attachment()
    {
        Bus::fake();
        Event::fake();

        $response = $this->sendMessage(null, true);

        $response->assertCreated();
        $this->assertEquals('success', $response->getData()->status);
        $this->assertEquals('Message sent', $response->getData()->message);

        Bus::assertDispatched(SaveMessage::class);
        Event::assertDispatched(MessageSent::class);
    }

    /** @test */
    public function can_get_user_conversations()
    {
        $this->sendMessage();
        $response = $this->getJson($this->apiBaseUrl . '/messages/conversations');

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id', 'sender_id', 'recipient_id', 'sender', 'recipient', 'show_name',
                        'last_message' => [],
                        'count_unread_messages',
                        'created_at', 'updated_at',
                    ],
                ],
            ]);

        $this->assertEquals('success', $response->getData()->status);
    }

    /** @test */
    public function cannot_view_invalid_conversation_messages()
    {
        $this->authUser();
        $conversationId = 1000;

        $response = $this->getJson($this->apiBaseUrl . "/messages/conversations/{$conversationId}");

        $this->assertEquals('error', $response->getData()->status);
        $this->assertEquals('You cannot view this conversation messages.', $response->getData()->message);
    }

    /** @test */
    public function cannot_view_unauthorized_conversation_messages()
    {
        $this->authUser();
        $secondUser = $this->createUser();
        $this->sendMessage($secondUser); //send message between first and second user

        $this->authUser($secondUser);
        $thirdUser = $this->createUser();
        $this->sendMessage($thirdUser); //send message between second and third user

        $this->authUser($thirdUser);

        $response = $this->getJson($this->apiBaseUrl . '/messages/conversations/1');

        $this->assertEquals('error', $response->getData()->status);
        $this->assertEquals('You cannot view this conversation messages.', $response->getData()->message);
    }

    /** @test */
    public function can_get_conversation_messages()
    {
        $this->sendMessage();

        $conversations = $this->getJson($this->apiBaseUrl . '/messages/conversations');
        $conversationId = $conversations->getData()->data[0]->id;

        $response = $this->getJson($this->apiBaseUrl . "/messages/conversations/{$conversationId}");

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id', 'conversation_id', 'sender_id', 'sender', 'is_read', 'read_at', 'created_at',
                    ],
                ],
            ]);

        $this->assertEquals('success', $response->getData()->status);
    }

    /** @test */
    public function cannot_delete_invalid_message()
    {
        $this->authUser();
        $messageId = 1000;

        $response = $this->postJson($this->apiBaseUrl . "/messages/delete/{$messageId}");
        $response->assertNotFound();

        $this->assertEquals('error', $response->getData()->status);
        $this->assertEquals('Message not found.', $response->getData()->message);
    }

    /** @test */
    public function cannot_delete_unauthorized_message()
    {
        $this->authUser();
        $secondUser = $this->createUser();
        $this->sendMessage($secondUser); //send message between first and second user

        $this->authUser($secondUser);
        $thirdUser = $this->createUser();
        $this->sendMessage($thirdUser); //send message between second and third user

        $conversations = $this->getJson($this->apiBaseUrl . '/messages/conversations');
        $firstConversationId = $conversations->getData()->data[0]->id;

        $messages = $this->getJson($this->apiBaseUrl . "/messages/conversations/{$firstConversationId}");
        $messageId = $messages->getData()->data[0]->id;

        $response = $this->postJson($this->apiBaseUrl . "/messages/delete/{$messageId}");
        $response->assertForbidden();

        $this->assertEquals('error', $response->getData()->status);
        $this->assertEquals('You cannot delete this message.', $response->getData()->message);
    }

    /** @test */
    public function can_delete_message()
    {
        $this->sendMessage();

        $conversations = $this->getJson($this->apiBaseUrl . '/messages/conversations');
        $conversationId = $conversations->getData()->data[0]->id;

        $messages = $this->getJson($this->apiBaseUrl . "/messages/conversations/{$conversationId}");
        $messageId = $messages->getData()->data[0]->id;

        $response = $this->postJson($this->apiBaseUrl . "/messages/delete/{$messageId}");

        $this->assertEquals('success', $response->getData()->status);
        $this->assertEquals('Message deleted', $response->getData()->message);
    }

    private function sendMessage($recipient = null, $attachment = false): TestResponse
    {
        if (is_null($recipient)) {
            $this->authUser();
            $recipientUser = $this->createUser();
        } else {
            $recipientUser = $recipient;
        }

        $file = null;

        if ($attachment) {
            Storage::fake('s3');
            $file = UploadedFile::fake()->image('attachment.png');
        }

        $data = [
            'message'      => 'hello world',
            'recipient_id' => $recipientUser->id,
            'attachment'   => $file
        ];

        return $this->postJson($this->apiBaseUrl . '/messages/send', $data);
    }
}
