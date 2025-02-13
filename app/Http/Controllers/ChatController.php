<?php

namespace DaaluPay\Http\Controllers;

use DaaluPay\Models\Admin;
use DaaluPay\Models\Chat;
use DaaluPay\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ChatController extends BaseController
{
    // User initiates a chat
    public function createChat(Request $request)
    {
        return $this->process(function () use ($request) {
            // get admin of type support in random order
            $admin = Admin::where('role', 'support')->inRandomOrder()->first();
            $user = Auth::user();

            $chat = Chat::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'agent_id' => $admin->id,
            ]);

            // Mail::to($admin->email)->send(new \DaaluPay\Mail\NewChat($chat));

            return $this->getResponse(
                status: true,
                message: 'Chat session created successfully',
                data: $chat,
                status_code: 200
            );
        });
    }

    // Get messages for a chat
    public function getMessages(Request $request)
    {
        return $this->process(function () use ($request) {
            $chatId = $request->route('chatId');
            $messages = Message::where('chat_id', $chatId)->orderBy('created_at', 'asc')->get();

            return $this->getResponse(
                status: true,
                message: 'Messages retrieved',
                data: $messages,
                status_code: 200
            );
        });
    }

    // Send a message
    public function sendMessage(Request $request)
    {
        return $this->process(function () use ($request) {
            $chatId = $request->route('chatId');
            $request->validate(['message' => 'required|string']);

            $message = Message::create([
                'chat_id' => $chatId,
                'sender_id' => Auth::id(),
                'sent_from' => 'user',
                'message' => $request->message,
            ]);

            return $this->getResponse(
                status: true,
                message: 'Message sent',
                data: $message,
                status_code: 200
            );
        });
    }
}
