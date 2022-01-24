<?php

namespace Tests\Feature\User;

use App\Mail\InvitationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Tests\Traits\{CreateInvitations, CreateUsers};

class UserInvitationTest extends TestCase
{
    use RefreshDatabase, CreateUsers, CreateInvitations;

    /** @test */
    public function cannot_invite_already_existing_user()
    {
        $user = $this->authUser();
        $data = ['invitee' => $user->email_address];

        $response = $this->postJson($this->baseUrl . '/users/invite', $data);
        $response->assertStatus(422);
        $this->assertEquals($response->getData()->message, 'The given data was invalid.');
        $this->assertEquals($response->getData()->errors->invitee[0], 'User already exist');
    }

    /** @test */
    public function can_invite_non_existent_user()
    {
        Mail::fake();

        $this->authUser();
        $data = ['invitee' => 'new@email.com'];

        $response = $this->postJson($this->baseUrl . '/users/invite', $data);
        $response->assertStatus(200);
        $this->assertEquals($response->getData()->status, 'success');
        $this->assertEquals($response->getData()->message, 'Invitation successfully sent to user');

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
            'password'      => '12345678'
        ];

        $response = $this->postJson($this->baseUrl . '/accounts/invitation/accept', $data);
        $response->assertStatus(422);
        $this->assertEquals($response->getData()->message, 'The given data was invalid.');
        $this->assertEquals($response->getData()->errors->token[0], 'The token field is required.');
    }


    /** @test */
    public function cannot_accept_invitation_with_an_invalid_token()
    {
        $data = ['token' => '1234568'];

        $response = $this->postJson($this->baseUrl . '/accounts/invitation/accept', $data);
        $response->assertStatus(422);
        $this->assertEquals($response->getData()->message, 'The given data was invalid.');
        $this->assertEquals($response->getData()->errors->token[0], 'The selected token is invalid.');
    }

    /** @test */
    public function cannot_accept_invitation_with_short_password()
    {
        $data = ['password' => '12345'];

        $response = $this->postJson($this->baseUrl . '/accounts/invitation/accept', $data);
        $response->assertStatus(422);
        $this->assertEquals($response->getData()->message, 'The given data was invalid.');
        $this->assertEquals($response->getData()->errors->password[0], 'The password must be at least 6 characters.');
    }

    /** @test */
    public function cannot_accept_invitation_if_email_address_or_phone_number_exist()
    {
        $user = $this->createUser();
        $response = $this->postJson($this->baseUrl . '/accounts/invitation/accept', $user->getAttributes());
        $response->assertStatus(422);
        $this->assertEquals($response->getData()->message, 'The given data was invalid.');
        $this->assertEquals($response->getData()->errors->phone_number[0], 'The phone number has already been taken.');
        $this->assertEquals($response->getData()->errors->email_address[0], 'The email address has already been taken.');
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
            'password'      => bcrypt('123456789')
        ];

        $response = $this->postJson($this->baseUrl . '/accounts/invitation/accept', $data);
        $response->assertStatus(200);
        $this->assertEquals($response->getData()->status, 'success');
        $this->assertEquals($response->getData()->message, 'Invitation accepted successfully');
    }
}
