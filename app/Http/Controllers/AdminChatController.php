<?php

namespace DaaluPay\Http\Controllers;

use DaaluPay\Models\Admin;
use DaaluPay\Models\Chat;
use DaaluPay\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminChatController extends BaseController
{
    // Get all pending chats
    public function getPendingChats(Request $request)
    {
        return $this->process(function () {

            // get pending or active chats
            $pendingChats = Chat::where('status', 'pending')->get();
            $activeChats = Chat::where('status', 'active')->get();
            // merge the two collections

            $chats = $pendingChats->merge($activeChats);

            return $this->getResponse(
                status: true,
                message: 'Chat session created successfully',
                data: Chat::where('status', 'pending')->get(),
                status_code: 200
            );
        });
    }

    // Assign chat to an agent
    public function assignChat($chatId)
    {
        return $this->process(function () use ($chatId) {
            $admin = Admin::where('role', 'support')->inRandomOrder()->first();
            $chat = Chat::findOrFail($chatId);
            $chat->update(['agent_id' => $admin->id, 'status' => 'active']);

            return $this->getResponse(
                status: true,
                message: 'Chat assigned to an agent',
                data: $chat,
                status_code: 200
            );
        });
    }

    // Admin sends a message
    public function sendAdminMessage(Request $request, $chatId)
    {
        return $this->process(function () use ($request, $chatId) {
            $request->validate(['message' => 'required|string']);

            $message = Message::create([
                'chat_id' => $chatId,
                'sender_id' => Auth::id(),
                'message' => $request->message,
                'sent_from' => 'agent',
            ]);

            return $this->getResponse(
                status: true,
                message: 'Message sent',
                data: $message,
                status_code: 200
            );
        });
        $request->validate(['message' => 'required|string']);

        $message = Message::create([
            'chat_id' => $chatId,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

    }

    // Close a chat
    public function closeChat($chatId)
    {
        return $this->process(function () use ($chatId) {
            $chat = Chat::findOrFail($chatId);
            $chat->update(['status' => 'closed']);

            return $this->getResponse(
                status: true,
                message: 'Chat closed',
                status_code: 200
            );
        });

    }
}
