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
            
            $response = array(
                "status" => "success",
                "message" => "Registration successful",
                "data" => $user,
            );

            return response()->json($response, 201);
        } catch (Exception $e) {
            $response = array("status" => "error", "message" => $e->getMessage());
            return response()->json($response, $e->getStatusCode());
        }
    }

    public function login(Request $request)
    {
        try {
            $data = $this->userService->login($request->all());
            
            $response = array(
                "status" => "success",
                "message" => "Login successful",
                "data" => $data,
            );

            return response()->json($response, 200);
        } catch (Exception $e) {
            $response = array("status" => "error", "message" => $e->getMessage());
            return response()->json($response, $e->getStatusCode());
        }
    }
}
