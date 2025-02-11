<?php

namespace DaaluPay\Http\Controllers;

use DaaluPay\Models\Chat;
use DaaluPay\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminChatController extends BaseController
{
    // Get all pending chats
    public function getPendingChats()
    {
        return response()->json(Chat::where('status', 'pending')->get());
    }

    // Assign chat to an agent
    public function assignChat($chatId)
    {
        $chat = Chat::findOrFail($chatId);
        $chat->update(['agent_id' => Auth::id(), 'status' => 'active']);

        return response()->json($chat);
    }

    // Admin sends a message
    public function sendAdminMessage(Request $request, $chatId)
    {
        $request->validate(['message' => 'required|string']);

        $message = Message::create([
            'chat_id' => $chatId,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return response()->json($message);
    }

    // Close a chat
    public function closeChat($chatId)
    {
        $chat = Chat::findOrFail($chatId);
        $chat->update(['status' => 'closed']);

        return response()->json(['message' => 'Chat closed']);
    }
}

