<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_login_with_valid_credentials()
    {
        $data = [
            'first_name'    => 'firstname',
            'last_name'     => 'lastname',
            'email_address' => 'email@chatter.app',
            'phone_number'  => '08123456789',
            'password'      => '123456789'
        ];

        $this->postJson($this->baseUrl . '/accounts/register', $data);
        $response = $this->postJson($this->baseUrl . '/accounts/login', $data);
        $response->assertStatus(200);
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

    public function test_cannot_login_with_invalid_credentials()
    {
        $data = [
            'email_address' => 'email@chatter.app',
            'password'      => '123456789'
        ];

        $response = $this->postJson($this->baseUrl . '/accounts/login', $data);
        $response->assertStatus(401);
        $this->assertEquals($response->getData()->status, 'error');
        $this->assertEquals($response->getData()->message, 'Incorrect login credentials');
    }
}
