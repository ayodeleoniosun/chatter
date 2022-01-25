<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreateUsers;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, CreateUsers;

    /** @test */
    public function cannot_register_with_short_password()
    {
        $data = [
            'first_name'    => 'firstname',
            'last_name'     => 'lastname',
            'email_address' => 'email@chatter.app',
            'phone_number'  => '08123456789',
            'password'      => '12345'
        ];

        $response = $this->postJson($this->baseUrl . '/accounts/register', $data);
        $response->assertUnprocessable();
        $this->assertEquals($response->getData()->message, 'The given data was invalid.');
        $this->assertEquals($response->getData()->errors->password[0], 'The password must be at least 6 characters.');
    }

    /** @test */
    public function cannot_register_if_lastname_and_email_address_is_empty()
    {
        $data = [
            'first_name'   => 'firstname',
            'phone_number' => '08123456789',
            'password'     => '1234567'
        ];

        $response = $this->postJson($this->baseUrl . '/accounts/register', $data);
        $response->assertUnprocessable();
        $this->assertEquals($response->getData()->message, 'The given data was invalid.');
        $this->assertEquals($response->getData()->errors->last_name[0], 'The last name field is required.');
        $this->assertEquals($response->getData()->errors->email_address[0], 'The email address field is required.');
    }

    /** @test */
    public function cannot_register_if_email_address_or_phone_number_exist()
    {
        $user = $this->createUser();
        $response = $this->postJson($this->baseUrl . '/accounts/register', $user->getAttributes());
        $response->assertUnprocessable();
        $this->assertEquals($response->getData()->message, 'The given data was invalid.');
        $this->assertEquals($response->getData()->errors->phone_number[0], 'The phone number has already been taken.');
        $this->assertEquals($response->getData()->errors->email_address[0], 'The email address has already been taken.');
    }

    /** @test */
    public function can_register_new_user()
    {
        $data = [
            'first_name'    => 'firstname',
            'last_name'     => 'lastname',
            'email_address' => 'email@chatter.app',
            'phone_number'  => '08123456789',
            'password'      => bcrypt('123456789')
        ];

        $response = $this->postJson($this->baseUrl . '/accounts/register', $data);
        $response->assertCreated();
        $this->assertEquals($response->getData()->status, 'success');
        $this->assertEquals($response->getData()->message, 'Registration successful');
        $this->assertEquals($response->getData()->data->first_name, $data['first_name']);
        $this->assertEquals($response->getData()->data->last_name, $data['last_name']);
        $this->assertEquals($response->getData()->data->email_address, $data['email_address']);
        $this->assertEquals($response->getData()->data->phone_number, $data['phone_number']);
    }
}
