<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\Assignment;


class ClassPageController extends Controller
{
    /** -----------------------------------------------
     *  LIST ALL CLASSES
     * -----------------------------------------------*/
public function index()
{
    $user = Auth::user();
    $role = strtolower($user->role);

    if ($role === 'teacher') {
        $directClasses = Classroom::where('teacher_id', $user->id)->get();
        $scheduledClasses = Schedule::with('classroom')
            ->where('teacher_id', $user->id)
            ->get()
            ->pluck('classroom')
            ->unique('id');

        $classes = $directClasses->merge($scheduledClasses)->unique('id');
        $canCreate = true;
    } else {
        $classes = $user->classrooms()->with('schedules')->get();
        $canCreate = false;
    }

    // **Add posts for this user or class**
    $posts = Post::whereIn('classroom_id', $classes->pluck('id'))->get();

    return view('classes.index', compact('classes', 'role', 'canCreate', 'posts'));
}

public function show($id)
{
    $class = Classroom::findOrFail($id);
    $role = auth()->user()->role;

    // Fetch posts for this class
    $posts = Post::where('classroom_id', $class->id)->get();

    return view('classes.show', compact('class', 'role', 'posts'));
}

    /** -----------------------------------------------
     *  SHOW SINGLE CLASS PAGE
     * -----------------------------------------------*/
    public function showClass(Classroom $class)
    {
        $user = Auth::user();
        $role = strtolower($user->role);

        // Load posts + comments for this class
        $posts = Post::with(['user', 'comments.user'])
            ->where('classroom_id', $class->id)
            ->latest()
            ->get();

        return view('classes.show', compact('class', 'posts', 'role'));
    }



    /** -----------------------------------------------
     *  CREATE NEW POST
     * -----------------------------------------------*/
    public function storePost(Request $request, Classroom $class)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $class->posts()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Post created successfully!');
    }

    /** -----------------------------------------------
     *  ADD COMMENT TO A POST
     * -----------------------------------------------*/
    public function storeComment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return redirect()->back();
    }

    /** -----------------------------------------------
     *  CLASS NAVIGATION: MATERIALS
     * -----------------------------------------------*/
    public function materials($id)
{
    $class = Classroom::findOrFail($id);
    $role = auth()->user()->role;

    $materials = Material::where('classroom_id', $id)->get();

    return view('classes.materials', compact('class', 'role', 'materials'));
}

    /** -----------------------------------------------
     *  CLASS NAVIGATION: ASSIGNMENTS
     * -----------------------------------------------*/
    public function assignment($id)
    {
        $class = Classroom::findOrFail($id);
        $role = auth()->user()->role;
        $assignments = Assignment::where('classroom_id', $id)
         ->latest()        // latest first
        ->get();

        return view('classes.assignment', compact('class', 'role', 'assignments'));
    }



    /** -----------------------------------------------
     *  CLASS NAVIGATION: QUIZZES
     * -----------------------------------------------*/
    public function quizzes($id)
{
    $class = Classroom::findOrFail($id);
    $role = auth()->user()->role;

    $quizzes = Quiz::where('classroom_id', $id)->get();

    return view('classes.quizzes', compact('class', 'role', 'quizzes'));
}

    // Update post
// Update post
public function update(Request $request, Post $post)
{
    $user = Auth::user();

    // Only owner can edit
    if ($user->id !== $post->user_id) {
        abort(403);
    }

    $request->validate([
        'content' => 'required|string',
    ]);

    $post->update([
        'content' => $request->content,
    ]);

    return back()->with('success', 'Post updated successfully!');
}


// Delete post
public function destroy(Post $post)
{
    $user = Auth::user();

    if ($user->id === $post->user_id || ($user->hasRole('teacher') && $user->id !== $post->user_id)) {
        $post->delete();
        return back()->with('success', 'Post deleted successfully!');
    }

    abort(403);
}

// Update comment
public function updateComment(Request $request, Comment $comment)
{
    if (Auth::id() !== $comment->user_id) {
        abort(403); // Cannot edit others' comments
    }

    $request->validate(['content' => 'required|string']);
    $comment->update($request->only('content'));

    return back()->with('success', 'Comment updated successfully!');
}

// Delete comment
public function destroyComment(Comment $comment)
{
    $user = Auth::user();

    if ($user->id === $comment->user_id || ($user->hasRole('teacher') && $user->id !== $comment->user_id)) {
        $comment->delete();
        return back()->with('success', 'Comment deleted successfully!');
    }

    abort(403);
}



}
