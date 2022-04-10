<?php

namespace Tests\Feature\User;

use App\Mail\ForgotPasswordMail;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tests\Traits\CreatePasswordResets;
use Tests\Traits\CreateUsers;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase, CreateUsers, CreatePasswordResets;

    /** @test */
    public function cannot_send_forgot_password_link_to_non_existent_email()
    {
        $response = $this->postJson($this->apiBaseUrl . '/accounts/password/forgot', ['email_address' => 'invalid@email.com']);

        $response->assertNotFound();

        $this->assertEquals('error', $response->getData()->status);
        $this->assertEquals('Email address does not exist', $response->getData()->message);
    }

    /** @test */
    public function send_forgot_password_link_to_existing_email()
    {
        Mail::fake();

        $user = $this->createUser();

        $response = $this->postJson($this->apiBaseUrl . '/accounts/password/forgot', ['email_address' => $user->email_address]);

        $response->assertOk();
        $this->assertEquals('success', $response->getData()->status);
        $this->assertEquals($response->getData()->message, 'Reset password link successfully sent to ' . $user->email_address);

        Mail::assertQueued(ForgotPasswordMail::class);
    }

    /** @test */
    public function cannot_reset_password_with_empty_token()
    {
        $data = ['token' => ''];

        $response = $this->postJson($this->apiBaseUrl . '/accounts/password/reset', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The token field is required.', $response->getData()->errors->token[0]);
    }

    /** @test */
    public function cannot_reset_password_with_short_passwords()
    {
        $data = [
            'new_password'              => '12345',
            'new_password_confirmation' => '12345',
        ];

        $response = $this->postJson($this->apiBaseUrl . '/accounts/password/reset', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The new password must be at least 6 characters.', $response->getData()->errors->new_password[0]);
    }

    /** @test */
    public function cannot_reset_password_with_non_matching_passwords()
    {
        $data = [
            'new_password'              => '1234567',
            'new_password_confirmation' => '12345678',
        ];

        $response = $this->postJson($this->apiBaseUrl . '/accounts/password/reset', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The new password confirmation does not match.', $response->getData()->errors->new_password[0]);
    }

    /** @test */
    public function cannot_reset_password_with_non_existent_token()
    {
        $data = ['token' => Str::random(60)];

        $response = $this->postJson($this->apiBaseUrl . '/accounts/password/reset', $data);

        $response->assertUnprocessable();
        $this->assertEquals('The given data was invalid.', $response->getData()->message);
        $this->assertEquals('The selected token is invalid.', $response->getData()->errors->token[0]);
    }

    /** @test */
    public function cannot_reset_password_with_used_token()
    {
        $passwordReset = $this->createPasswordReset('used');

        $data = [
            'token'                     => $passwordReset->token,
            'new_password'              => '1234567',
            'new_password_confirmation' => '1234567',
        ];

        $response = $this->postJson($this->apiBaseUrl . '/accounts/password/reset', $data);

        $response->assertForbidden();
        $this->assertEquals('error', $response->getData()->status);
        $this->assertEquals('Invalid token', $response->getData()->message);
    }

    /** @test */
    public function cannot_reset_password_with_non_existent_email()
    {
        $passwordReset = $this->createPasswordReset();

        $data = [
            'token'                     => $passwordReset->token,
            'new_password'              => '1234567',
            'new_password_confirmation' => '1234567',
        ];

        $response = $this->postJson($this->apiBaseUrl . '/accounts/password/reset', $data);

        $response->assertNotFound();
        $this->assertEquals('error', $response->getData()->status);
        $this->assertEquals('User not found.', $response->getData()->message);
    }

    /** @test */
    public function cannot_reset_password_with_expired_token()
    {
        $user = $this->createUser();
        $passwordReset = $this->createPasswordReset();

        $passwordReset->email = $user->email_address;
        $passwordReset->save();

        $data = [
            'token'                     => $passwordReset->token,
            'new_password'              => '1234567',
            'new_password_confirmation' => '1234567',
        ];

        $response = $this->postJson($this->apiBaseUrl . '/accounts/password/reset', $data);

        $response->assertForbidden();
        $this->assertEquals('error', $response->getData()->status);
        $this->assertEquals('Token has expired. Kindly reset password again.', $response->getData()->message);
    }

    /** @test */
    public function can_reset_password()
    {
        $user = $this->createUser();
        $passwordReset = $this->createPasswordReset();

        $passwordReset->expires_at = Carbon::now()->toDateTimeString();
        $passwordReset->email = $user->email_address;
        $passwordReset->save();

        $data = [
            'token'                     => $passwordReset->token,
            'new_password'              => '1234567',
            'new_password_confirmation' => '1234567',
        ];

        $response = $this->postJson($this->apiBaseUrl . '/accounts/password/reset', $data);

        $response->assertOk();
        $this->assertEquals('success', $response->getData()->status);
        $this->assertEquals('Password successfully reset', $response->getData()->message);
    }
}
