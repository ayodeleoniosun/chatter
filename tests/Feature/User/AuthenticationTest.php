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

        $response = $this->postJson($this->apiBaseUrl . '/accounts/login', $data);

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user' => ['id', 'first_name', 'last_name', 'email_address', 'phone_number', 'profile_picture',
                        'previous_profile_pictures', 'created_at', 'updated_at'
                    ],
                    'token'
                ]
            ]);

        $this->assertEquals('success', $response->getData()->status);
        $this->assertEquals('Login successful', $response->getData()->message);
    }

    /** @test */
    public function cannot_login_with_invalid_credentials()
    {
        $data = [
            'email_address' => 'email@chatter.app',
            'password'      => '123456789'
        ];

        $response = $this->postJson($this->apiBaseUrl . '/accounts/login', $data);

        $response->assertUnauthorized();
        $this->assertEquals('error', $response->getData()->status);
        $this->assertEquals('Incorrect login credentials', $response->getData()->message);
    }
}
