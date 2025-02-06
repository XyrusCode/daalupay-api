<?php

namespace DaaluPay\Http\Controllers;

use DaaluPay\Message;
use DaaluPay\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsController extends BaseController
{
/**
 * Show chats
 *
 * @return \Illuminate\Http\Response
 */
public function index()
{
  return view('chat');
}

/**
 * Fetch all messages
 *
 * @return Message
 */
public function fetchMessages()
{
//   return Message::with('user')->get();
}

/**
 * Persist message to database
 *
 * @param  Request $request
 * @return Response
 */
public function sendMessage(Request $request)
{
  $user = Auth::user();

//   $message = $user->messages()->create([
//     'message' => $request->input('message')
//   ]);

  return ['status' => 'Message Sent!'];
}

}
