<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreateUsers;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase, CreateUsers;

    /** @test */
    public function can_login_with_valid_credentials()
    {
        $user = $this->createUser();
        $data = ['email_address' => $user->email_address, 'password' => '12345678'];

        $response = $this->postJson($this->baseUrl . '/accounts/login', $data);
        $response->assertOk();
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'user' => ['id', 'first_name', 'last_name', 'email_address', 'phone_number', 'profile_picture',
                    'previous_profile_pictures', 'created_at', 'updated_at'
                ],
                'token'
            ]
        ]);
        $this->assertEquals($response->getData()->status, 'success');
        $this->assertEquals($response->getData()->message, 'Login successful');
    }

    /** @test */
    public function cannot_login_with_invalid_credentials()
    {
        $data = [
            'email_address' => 'email@chatter.app',
            'password'      => '123456789'
        ];

        $response = $this->postJson($this->baseUrl . '/accounts/login', $data);
        $response->assertUnauthorized();
        $this->assertEquals($response->getData()->status, 'error');
        $this->assertEquals($response->getData()->message, 'Incorrect login credentials');
    }
}
