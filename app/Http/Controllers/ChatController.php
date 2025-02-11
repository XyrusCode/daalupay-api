<?php

namespace DaaluPay\Http\Controllers;

use DaaluPay\Models\Chat;
use DaaluPay\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends BaseController
{
    // User initiates a chat
    public function createChat(Request $request)
    {
        $chat = Chat::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        return response()->json($chat);
    }

    // Get messages for a chat
    public function getMessages($chatId)
    {
        $messages = Message::where('chat_id', $chatId)->orderBy('created_at', 'asc')->get();
        return response()->json($messages);
    }

    // Send a message
    public function sendMessage(Request $request, $chatId)
    {
        $request->validate(['message' => 'required|string']);

        $message = Message::create([
            'chat_id' => $chatId,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return response()->json($message);
    }
}

