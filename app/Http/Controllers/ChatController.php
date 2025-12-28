<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // List users to chat with (opposite role)
    public function list()
    {
        $users = User::where('id', '!=', Auth::id())
                    ->where('role', '!=', 'admin')
                    ->get();

        return view('chat.list', compact('users'));
    }


    // Show chat messages with a specific user
    public function show(User $user)
    {
        $authUser = Auth::user();

        // 🚫 Block admin from chatting
        if ($authUser->role === 'admin' || $user->role === 'admin') {
            abort(403, 'Admins are not allowed to chat.');
        }

        // Get chat messages
        $messages = Message::where(function ($q) use ($authUser, $user) {
                $q->where('sender_id', $authUser->id)
                ->where('receiver_id', $user->id);
            })
            ->orWhere(function ($q) use ($authUser, $user) {
                $q->where('sender_id', $user->id)
                ->where('receiver_id', $authUser->id);
            })
            ->orderBy('created_at')
            ->get();

        // Mark received messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $authUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Sidebar users (exclude self + admin)
        $users = User::where('id', '!=', $authUser->id)
                    ->where('role', '!=', 'admin')
                    ->get();

        return view('chat.show', [
            'chatUser' => $user,
            'messages' => $messages,
            'users' => $users,
        ]);
    }
    // Send a message
    public function send(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
        ]);

        return redirect()->route('chat.show', $user->id);
    }
}
