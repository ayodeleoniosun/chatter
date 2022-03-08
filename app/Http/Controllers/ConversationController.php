<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chats\SendMessageRequest;
use App\Services\ConversationService;

class ConversationController extends Controller
{
    protected ConversationService $conversationService;

    public function __construct(ConversationService $conversationService)
    {
        $this->conversationService = $conversationService;
    }

    public function index()
    {
        return view('chats.index');
    }

    public function send(SendMessageRequest $request)
    {
        try {
            $this->conversationService->send($request->user(), $request->all());

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
