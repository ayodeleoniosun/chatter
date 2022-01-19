<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\{ResetPasswordRequest, UserRegistrationRequest};
use App\Services\AccountService;
use Exception;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function register(UserRegistrationRequest $request)
    {
        try {
            $user = $this->accountService->register($request->all());
            return response()->json([
                "status"  => "success",
                "message" => "Registration successful",
                "data"    => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "status"  => "error",
                "message" => $e->getMessage()
            ], $e->getStatusCode());
        }
    }

    public function login(Request $request)
    {
        try {
            $data = $this->accountService->login($request->all());
            return response()->json([
                "status"  => "success",
                "message" => "Login successful",
                "data"    => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status"  => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $this->accountService->forgotPassword($request->all());

            return response()->json([
                "status"  => "success",
                "message" => "Reset password link successfully sent to " . $request->email_address
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status"  => "error",
                "message" => $e->getMessage()
            ], $e->getStatusCode());
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $this->accountService->resetPassword($request->all());

            return response()->json([
                "status"  => "success",
                "message" => "Password successfully reset"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status"  => "error",
                "message" => $e->getMessage()
            ], $e->getStatusCode());
        }
    }
}
