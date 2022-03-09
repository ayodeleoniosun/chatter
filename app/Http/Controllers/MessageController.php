<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chats\SendMessageRequest;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function conversations(Request $request): JsonResponse
    {
        try {
            $conversations = $this->messageService->conversations($request->user()->id);

            return response()->json([
                "status" => "success",
                "data"   => $conversations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status"  => "error",
                "message" => $e->getMessage()
            ], $e->getStatusCode());
        }
    }

    public function send(SendMessageRequest $request): JsonResponse
    {
        try {
            $this->messageService->send($request->user(), $request->all());

            return response()->json([
                "status"  => "success",
                "message" => "Message sent"
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "status"  => "error",
                "message" => $e->getMessage()
            ], $e->getStatusCode());
        }
    }
}
