<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chats\SendMessageRequest;
use App\Models\Message;
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

    public function messages(Request $request, string $conversation): JsonResponse
    {
        try {
            $messages = $this->messageService->messages($request->user()->id, $conversation);

            return response()->json([
                "status" => "success",
                "data"   => $messages
            ], 200);
        } catch (\Exception $e) {
            dd($e);
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

    public function delete(Request $request, string $message)
    {
        try {
            $this->messageService->delete($request->user()->id, $message);

            return response()->json([
                "status"  => "success",
                "message" => "Message deleted"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status"  => "error",
                "message" => $e->getMessage()
            ], $e->getStatusCode());
        }
    }
}
