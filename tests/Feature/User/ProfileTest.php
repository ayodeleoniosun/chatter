<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Traits\CreateUsers;

class ProfileTest extends TestCase
{
    use RefreshDatabase, CreateUsers;

    protected $user;

    public function setup(): void
    {
        parent::setup();
        $this->user = $this->authUser();
    }

    /** @test */
    public function can_view_profile()
    {
        $response = $this->getJson($this->apiBaseUrl . '/users/profile');

        $response->assertOk();
        $response->assertJson(fn(AssertableJson $json) => $json->hasAll('status', 'data'));
        $this->assertEquals('success', $response->getData()->status);
        $this->assertEquals($response->getData()->data->first_name, $this->user->first_name);
        $this->assertEquals($response->getData()->data->last_name, $this->user->last_name);
        $this->assertEquals($response->getData()->data->email_address, $this->user->email_address);
        $this->assertEquals($response->getData()->data->phone_number, $this->user->phone_number);
    }

    /** @test */
    public function cannot_update_profile_with_empty_fields()
    {
        $data = [
            'first_name'   => 'firstname',
            'phone_number' => '08123456789',
        ];

        $response = $this->putJson($this->apiBaseUrl . '/users/profile/update', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The last name field is required.', $response->getData()->errors->last_name[0]);
    }

    /** @test */
    public function cannot_update_profile_with_existing_phone_number()
    {
        $user = $this->createUser();

        $data = [
            'first_name'   => 'firstname',
            'last_name'    => 'lastname',
            'phone_number' => $user->phone_number,
        ];

        $response = $this->putJson($this->apiBaseUrl . '/users/profile/update', $data);

        $response->assertForbidden();
        $this->assertEquals('error', $response->getData()->status);
        $this->assertEquals('Phone number belongs to another user', $response->getData()->message);
    }

    /** @test */
    public function can_update_profile()
    {
        $data = [
            'first_name'   => 'new firstname',
            'last_name'    => 'new lastname',
            'phone_number' => Str::random(11),
        ];

        $response = $this->putJson($this->apiBaseUrl . '/users/profile/update', $data);

        $response->assertOk();
        $this->assertEquals('success', $response->getData()->status);
        $this->assertEquals('Profile successfully updated', $response->getData()->message);
        $this->assertEquals($response->getData()->data->first_name, $data['first_name']);
        $this->assertEquals($response->getData()->data->last_name, $data['last_name']);
        $this->assertEquals($response->getData()->data->phone_number, $data['phone_number']);
    }

    /** @test */
    public function cannot_update_password_with_wrong_current_password()
    {
        $data = [
            'current_password' => '12345',
        ];

        $response = $this->putJson($this->apiBaseUrl . '/users/password/update', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('Current password is incorrect', $response->getData()->errors->current_password[0]);
    }

    /** @test */
    public function cannot_update_password_with_short_passwords()
    {
        $data = [
            'new_password'              => '12345',
            'new_password_confirmation' => '12345',
        ];

        $response = $this->putJson($this->apiBaseUrl . '/users/password/update', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The new password must be at least 6 characters.', $response->getData()->errors->new_password[0]);
    }

    /** @test */
    public function cannot_update_password_with_non_matching_passwords()
    {
        $data = [
            'new_password'              => '1234567',
            'new_password_confirmation' => '12345678',
        ];

        $response = $this->putJson($this->apiBaseUrl . '/users/password/update', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The new password confirmation does not match.', $response->getData()->errors->new_password[0]);
    }

    /** @test */
    public function can_update_password()
    {
        $data = [
            'current_password'          => '12345678',
            'new_password'              => '123456789',
            'new_password_confirmation' => '123456789',
        ];

        $response = $this->putJson($this->apiBaseUrl . '/users/password/update', $data);

        $response->assertOk();
        $this->assertEquals('success', $response->getData()->status);
        $this->assertEquals('Password successfully updated', $response->getData()->message);
    }

    /** @test */
    public function cannot_update_profile_picture_with_invalid_file()
    {
        $data = ['image' => 'filename.jpg'];

        $response = $this->postJson($this->apiBaseUrl . '/users/picture/update', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The image must be an image.', $response->getData()->errors->image[0]);
        $this->assertEquals('The image must be a file of type: jpeg, png, jpg.', $response->getData()->errors->image[1]);
    }

    /** @test */
    public function can_update_profile_picture()
    {
        Storage::fake('s3');
        $file = UploadedFile::fake()->image('avatar.png');
        $data = ['image' => $file];

        $response = $this->postJson($this->apiBaseUrl . '/users/picture/update', $data);

        $response->assertOk();
        $this->assertEquals('success', $response->getData()->status);
        $this->assertEquals('Profile picture successfully updated', $response->getData()->message);
        $this->assertNotNull($response->getData()->data->profile_picture);
    }
}
