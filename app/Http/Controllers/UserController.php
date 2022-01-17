<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfilePictureRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function profile(Request $request)
    {
        try {
            $data = $this->userService->profile($request->user()->id);
            
            return response()->json(["status" => "success", "data" => $data], 200);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e->getMessage()], $e->getStatusCode());
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $data = $this->userService->updateProfile($request->all(), $request->user()->id);
            return response()->json([
                "status" => "success",
                "message" => "Profile successfully updated",
                "data" => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e->getMessage()], $e->getStatusCode());
        }
    }

    public function updateProfilePicture(UpdateProfilePictureRequest $request)
    {
        try {
            $data = $this->userService->updateProfilePicture($request->image, $request->user()->id);
            return response()->json([
                "status" => "success",
                "message" => "Profile picture successfully updated",
                "data" => $data
            ], 200);
        } catch (Exception $e) {
            dd($e);
            return response()->json(["status" => "error", "message" => $e->getMessage()], $e->getStatusCode());
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $data = $this->userService->updatePassword($request->all(), $request->user()->id);
            return response()->json([
                "status" => "success",
                "message" => "Password successfully updated"
            ], 200);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e->getMessage()], $e->getStatusCode());
        }
    }
}
