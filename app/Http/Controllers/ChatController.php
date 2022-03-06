<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chats\SendMessageRequest;
use App\Services\ChatService;

class ChatController extends Controller
{
    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function index()
    {
        return view('chats.index');
    }

    public function send(SendMessageRequest $request)
    {
        try {
            $user = $this->chatService->send($request->user(), $request->all());
            return response()->json([
                "status"  => "success",
                "message" => "Message sent"
            ], 200);
        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                "status"  => "error",
                "message" => $e->getMessage()
            ], $e->getStatusCode());
        }
    }
}
