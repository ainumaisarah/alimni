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
        $user = Auth::user();

        if ($user->hasRole('teacher')) {
            $users = User::where('role', 'student')->get();
        } elseif ($user->hasRole('student')) {
            $users = User::where('role', 'teacher')->get();
        } else {
            $users = collect(); // For admin or other roles
        }

        return view('chat.list', compact('users'));
    }

    // Show chat messages with a specific user
    public function show(User $user)
    {
        $authUser = Auth::user();

        // Get the chat messages
        $messages = Message::where(function($q) use ($authUser, $user) {
                $q->where('sender_id', $authUser->id)
                ->where('receiver_id', $user->id);
            })
            ->orWhere(function($q) use ($authUser, $user) {
                $q->where('sender_id', $user->id)
                ->where('receiver_id', $authUser->id);
            })
            ->orderBy('created_at')
            ->get();

        // Mark messages received by this user as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $authUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Get the list of users (for the left sidebar)
        if ($authUser->hasRole('teacher')) {
            $users = User::where('role', 'student')->get();
        } elseif ($authUser->hasRole('student')) {
            $users = User::where('role', 'teacher')->get();
        } else {
            $users = collect();
        }

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
