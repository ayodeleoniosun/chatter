<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Users\AcceptInvitationRequest;
use App\Http\Requests\Users\ResetPasswordRequest;
use App\Http\Requests\Users\UserRegistrationRequest;
use App\Services\AccountService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function register(UserRegistrationRequest $request): JsonResponse
    {
        try {
            $user = $this->accountService->register($request->all());

            return response()->json([
                'status'  => 'success',
                'message' => 'Registration successful',
                'data'    => $user,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $data = $this->accountService->login($request->all());

            return response()->json([
                'status'  => 'success',
                'message' => 'Login successful',
                'data'    => $data,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        try {
            $this->accountService->forgotPassword($request->all());

            return response()->json([
                'status'  => 'success',
                'message' => 'Reset password link successfully sent to ' . $request->email_address,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $this->accountService->resetPassword($request->all());

            return response()->json([
                'status'  => 'success',
                'message' => 'Password successfully reset',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    public function acceptInvitation(AcceptInvitationRequest $request): JsonResponse
    {
        try {
            $this->accountService->acceptInvitation($request->all());

            return response()->json([
                'status'  => 'success',
                'message' => 'Invitation accepted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}
