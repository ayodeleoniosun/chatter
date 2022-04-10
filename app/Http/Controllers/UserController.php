<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Users\InviteUserRequest;
use App\Http\Requests\Users\UpdatePasswordRequest;
use App\Http\Requests\Users\UpdateProfilePictureRequest;
use App\Http\Requests\Users\UpdateUserProfileRequest;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        try {
            $data = $this->userService->index();

            return response()->json([
                'status' => 'success',
                'data'   => $data,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    public function profile(Request $request): JsonResponse
    {
        try {
            $data = $this->userService->profile($request->user()->id);

            return response()->json([
                'status' => 'success',
                'data'   => $data,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    public function updateProfile(UpdateUserProfileRequest $request): JsonResponse
    {
        try {
            $data = $this->userService->updateProfile($request->all(), $request->user()->id);

            return response()->json([
                'status'  => 'success',
                'message' => 'Profile successfully updated',
                'data'    => $data,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    public function updateProfilePicture(UpdateProfilePictureRequest $request): JsonResponse
    {
        try {
            $data = $this->userService->updateProfilePicture($request->image, $request->user()->id);

            return response()->json([
                'status'  => 'success',
                'message' => 'Profile picture successfully updated',
                'data'    => $data,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        try {
            $this->userService->updatePassword($request->all(), $request->user()->id);

            return response()->json([
                'status'  => 'success',
                'message' => 'Password successfully updated',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    public function invite(InviteUserRequest $request): JsonResponse
    {
        try {
            $this->userService->inviteUser($request->invitee, $request->user());

            return response()->json([
                'status'  => 'success',
                'message' => 'Invitation successfully sent to user',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $this->userService->logout($request->user());

            return response()->json([
                'status'  => 'success',
                'message' => 'Logged out successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}
