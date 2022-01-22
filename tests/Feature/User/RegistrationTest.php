<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreateUsers;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, CreateUsers;

    public function test_cannot_register_with_short_password()
    {
        $data = [
            'first_name'    => 'firstname',
            'last_name'     => 'lastname',
            'email_address' => 'email@chatter.app',
            'phone_number'  => '08123456789',
            'password'      => '12345'
        ];

        $response = $this->postJson($this->baseUrl . '/accounts/register', $data);
        $response->assertStatus(422);
        $this->assertEquals($response->getData()->message, 'The given data was invalid.');
        $this->assertEquals($response->getData()->errors->password[0], 'The password must be at least 6 characters.');
    }

    public function test_lastname_and_email_address_required()
    {
        $data = [
            'first_name'   => 'firstname',
            'phone_number' => '08123456789',
            'password'     => '1234567'
        ];

        $response = $this->postJson($this->baseUrl . '/accounts/register', $data);
        $response->assertStatus(422);
        $this->assertEquals($response->getData()->message, 'The given data was invalid.');
        $this->assertEquals($response->getData()->errors->last_name[0], 'The last name field is required.');
        $this->assertEquals($response->getData()->errors->email_address[0], 'The email address field is required.');
    }

    public function test_email_address_or_phone_number_exist()
    {
        $data = [
            'first_name'    => 'firstname',
            'last_name'     => 'lastname',
            'email_address' => 'email@chatter.app',
            'phone_number'  => '08123456789',
            'password'      => bcrypt('123456789')
        ];

        $this->postJson($this->baseUrl . '/accounts/register', $data);
        $response = $this->postJson($this->baseUrl . '/accounts/register', $data);
        $response->assertStatus(422);
        $this->assertEquals($response->getData()->message, 'The given data was invalid.');
        $this->assertEquals($response->getData()->errors->phone_number[0], 'The phone number has already been taken.');
        $this->assertEquals($response->getData()->errors->email_address[0], 'The email address has already been taken.');
    }

    public function test_can_register_new_user()
    {
        $data = [
            'first_name'    => 'firstname',
            'last_name'     => 'lastname',
            'email_address' => 'email@chatter.app',
            'phone_number'  => '08123456789',
            'password'      => bcrypt('123456789')
        ];

        $response = $this->postJson($this->baseUrl . '/accounts/register', $data);
        $response->assertStatus(201);
        $this->assertEquals($response->getData()->status, 'success');
        $this->assertEquals($response->getData()->message, 'Registration successful');
        $this->assertEquals($response->getData()->data->first_name, $data['first_name']);
        $this->assertEquals($response->getData()->data->last_name, $data['last_name']);
        $this->assertEquals($response->getData()->data->email_address, $data['email_address']);
        $this->assertEquals($response->getData()->data->phone_number, $data['phone_number']);
    }
}
