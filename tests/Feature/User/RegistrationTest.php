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

        $response = $this->postJson($this->apiBaseUrl . '/accounts/register', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The password must be at least 6 characters.', $response->getData()->errors->password[0]);
    }

    /** @test */
    public function cannot_register_if_lastname_and_email_address_is_empty()
    {
        $data = [
            'first_name'   => 'firstname',
            'phone_number' => '08123456789',
            'password'     => '1234567'
        ];

        $response = $this->postJson($this->apiBaseUrl . '/accounts/register', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The last name field is required.', $response->getData()->errors->last_name[0]);
        $this->assertEquals('The email address field is required.', $response->getData()->errors->email_address[0]);
    }

    /** @test */
    public function cannot_register_if_email_address_or_phone_number_exist()
    {
        $user = $this->createUser();

        $response = $this->postJson($this->apiBaseUrl . '/accounts/register', $user->getAttributes());

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The phone number has already been taken.', $response->getData()->errors->phone_number[0]);
        $this->assertEquals('The email address has already been taken.', $response->getData()->errors->email_address[0]);
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

        $response = $this->postJson($this->apiBaseUrl . '/accounts/register', $data);

        $response->assertCreated();
        $this->assertEquals('success', $response->getData()->status);
        $this->assertEquals('Registration successful', $response->getData()->message);
        $this->assertEquals($data['first_name'], $response->getData()->data->first_name);
        $this->assertEquals($response->getData()->data->last_name, $data['last_name']);
        $this->assertEquals($response->getData()->data->email_address, $data['email_address']);
        $this->assertEquals($response->getData()->data->phone_number, $data['phone_number']);
    }
}
