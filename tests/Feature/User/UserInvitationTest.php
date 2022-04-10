<?php

namespace Tests\Feature\User;

use App\Mail\InvitationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Tests\Traits\CreateInvitations;
use Tests\Traits\CreateUsers;

class UserInvitationTest extends TestCase
{
    use RefreshDatabase, CreateUsers, CreateInvitations;

    /** @test */
    public function cannot_invite_already_existing_user()
    {
        $user = $this->authUser();
        $data = ['invitee' => $user->email_address];

        $response = $this->postJson($this->apiBaseUrl . '/users/invite', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('User already exist', $response->getData()->errors->invitee[0]);
    }

    /** @test */
    public function can_invite_non_existent_user()
    {
        Mail::fake();

        $this->authUser();
        $data = ['invitee' => 'new@email.com'];

        $response = $this->postJson($this->apiBaseUrl . '/users/invite', $data);

        $response->assertOk();
        $this->assertEquals('success', $response->getData()->status);
        $this->assertEquals('Invitation successfully sent to user', $response->getData()->message);

        Mail::assertQueued(InvitationMail::class);
    }

    /** @test */
    public function cannot_accept_invitation_with_empty_token()
    {
        $data = [
            'first_name'    => 'firstname',
            'last_name'     => 'lastname',
            'email_address' => 'email@chatter.app',
            'phone_number'  => '08123456789',
            'password'      => '12345678',
        ];

        $response = $this->postJson($this->apiBaseUrl . '/accounts/invitation/accept', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The token field is required.', $response->getData()->errors->token[0]);
    }

    /** @test */
    public function cannot_accept_invitation_with_an_invalid_token()
    {
        $data = ['token' => '1234568'];

        $response = $this->postJson($this->apiBaseUrl . '/accounts/invitation/accept', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The selected token is invalid.', $response->getData()->errors->token[0]);
    }

    /** @test */
    public function cannot_accept_invitation_with_short_password()
    {
        $data = ['password' => '12345'];

        $response = $this->postJson($this->apiBaseUrl . '/accounts/invitation/accept', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The password must be at least 6 characters.', $response->getData()->errors->password[0]);
    }

    /** @test */
    public function cannot_accept_invitation_if_email_address_or_phone_number_exist()
    {
        $user = $this->createUser();
        $response = $this->postJson($this->apiBaseUrl . '/accounts/invitation/accept', $user->getAttributes());

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The phone number has already been taken.', $response->getData()->errors->phone_number[0]);
        $this->assertEquals('The email address has already been taken.', $response->getData()->errors->email_address[0]);
    }

    /** @test */
    public function can_register_new_user()
    {
        $this->createUser();

        $invitation = $this->createInvitation();

        $invitation->invitee = 'invitees@chatter.app';
        $invitation->save();

        $data = [
            'token'         => $invitation->token,
            'first_name'    => 'firstname',
            'last_name'     => 'lastname',
            'email_address' => $invitation->invitee,
            'phone_number'  => '0812345678',
            'password'      => bcrypt('123456789'),
        ];

        $response = $this->postJson($this->apiBaseUrl . '/accounts/invitation/accept', $data);

        $response->assertOk();
        $this->assertEquals('success', $response->getData()->status);
        $this->assertEquals('Invitation accepted successfully', $response->getData()->message);
    }
}
