<?php

namespace App\Http\Controllers;

use App\Requests\UserRegistrationRequest;
use App\Services\UserService;
use Exception;

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
                "user" => $user,
            );

            return response()->json($response, 201);
        } catch (Exception $e) {
            $response = array("status" => "error", "message" => $e->getMessage());
            return response()->json($response, $e->getStatusCode());
        }
    }
}
