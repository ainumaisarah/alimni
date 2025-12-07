<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;       // ✅ Add this
use App\Models\Comment;    // ✅ If using comments
use App\Models\Classroom;  // ✅ If using classrooms
use Illuminate\Support\Facades\Auth;

class ChannelController extends Controller
{
    public function show(Classroom $class)
{
    $posts = Post::with('user', 'comments.user')
        ->where('classroom_id', $class->id)
        ->latest()
        ->get();

    return view('classes.channel', compact('class', 'posts'));
}

public function post(Request $request, Classroom $class)
{
    $request->validate([
        'content' => 'required|string',
        'title' => 'nullable|string|max:255',
    ]);

    Post::create([
        'classroom_id' => $class->id,
        'user_id' => auth()->id(),
        'content' => $request->content,
    ]);

    return redirect()->back()->with('success', 'Post created successfully!');
}

public function comment(Request $request, Post $post)
{
    $request->validate(['content' => 'required|string']);

    $post->comments()->create([
        'user_id' => auth()->id(),
        'content' => $request->content,
    ]);

    return redirect()->back();
}


}

