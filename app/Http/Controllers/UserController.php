<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
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

    public function register(UserRegistrationRequest $request)
    {
        try {
            $user = $this->userService->register($request->all());
            return response()->json([
                "status" => "success",
                "message" => "Registration successful",
                "data" => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e->getMessage()], $e->getStatusCode());
        }
    }

    public function login(Request $request)
    {
        try {
            $data = $this->userService->login($request->all());
            return response()->json([
                "status" => "success",
                "message" => "Login successful",
                "data" => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    public function profile(Request $request)
    {
        try {
            return response()->json(["status" => "success", "data" => $request->user()], 200);
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
}
