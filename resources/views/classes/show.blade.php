@extends('layouts.app')

@section('content')
<div class="class-container">

    <nav class="class-nav">
            <div class="flex items-center gap-2">
            <a href="{{ route('classes.index') }}" :active="request()->routeIs('classes.index')"
                class="h-8 w-8 inline-flex items-center justify-center p-2" style="color: rgb(224, 216, 191);
                hover:text-[#1f4033]">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2><a href="{{ route('classes.show', $class->id) }}" class="font-semibold text-lg">
            {{ $class->name }}
        </a></h2>
        </div>

        <div class="class-menu">
            <a href="{{ route('classes.materials', $class->id) }}"
                class="{{ request()->routeIs('classes.materials') ? 'active' : '' }}"> Materials </a>
             <a href="{{ route('classes.assignment', $class->id) }}"
                class="{{ request()->routeIs('classes.assignment') ? 'active' : '' }}"> Assignment </a>
            <a href="{{ route('classes.quizzes', $class->id) }}"
                class="{{ request()->routeIs('classes.quizzes') ? 'active' : '' }}"> Quiz </a>
        </div>
    </nav>


    {{-- Scrollable Classbox --}}
    <div class="classbox">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="success-alert mb-4 px-4">
                {{ session('success') }}
            </div>
        @endif

        @auth
        @php $user = Auth::user(); @endphp

        @if(!isset($posts) || $posts->isEmpty())
            <div class="flex-1 flex items-center justify-center text-gray-600">
                <h3 class="text-lg font-semibold">Welcome to {{ $class->name }}!</h3>
            </div>
        @else
            <div class="flex-1 space-y-4">
                @foreach($posts as $post)
                    @php
                        $postClass = $post->user->hasRole('teacher') ? 'app-card' : 'student-card';
                        $isOwner = $user->id === $post->user_id;
                        $isTeacher = $user->hasRole('teacher');
                    @endphp

                    <div class="{{ $postClass }} p-4 border rounded" x-data="{ editing: false }">
                        <div class="flex items-center gap-2 mb-2">
                            <strong>{{ $post->user->name }}</strong>
                            <span class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</span>

                            <div class="ml-auto flex gap-2">
                                {{-- Edit button only for owner --}}
                                @if($isOwner)
                                    <button @click="editing = !editing" class="text-blue-500 text-sm">Edit</button>
                                @endif

                                {{-- Delete button: owner or teacher deleting student post --}}
                                @if($isOwner || ($isTeacher && !$isOwner))
                                    <form action="{{ route('channel.post.delete', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 text-sm">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        {{-- Post content / edit form --}}
                        <div>
                            <p x-show="!editing" class="mb-3">{{ $post->content }}</p>

                            <form x-show="editing" action="{{ route('channel.post.update', $post->id) }}" method="POST" class="mb-3">
                            @csrf
                            @method('PUT')

                            <textarea name="content" class="w-full p-3 border rounded mb-2" rows="3">{{ old('content', $post->content) }}</textarea>

                            <div class="flex gap-2">
                                <button type="submit" class="text-green-600 text-sm">Update</button>
                                <button type="button" @click="editing = false" class="text-red-600 text-sm">Cancel</button>
                            </div>
                        </form>

                        </div>

                        {{-- Comments --}}
                        <div class="ml-6 border-l pl-4">
                            @foreach($post->comments as $comment)
                                @php
                                    $isOwnerComment = $user->id === $comment->user_id;
                                @endphp

                                <div x-data="{ editingComment: false }" class="mb-2">
                                    <div class="flex items-center gap-2">
                                        <strong>{{ $comment->user->name }}</strong>
                                        <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>

                                        <div class="ml-auto flex gap-2">
                                            {{-- Edit button only for comment owner --}}
                                            @if($isOwnerComment)
                                                <button @click="editingComment = !editingComment" class="text-blue-500 text-sm">Edit</button>
                                            @endif

                                            {{-- Delete button: owner or teacher deleting student's comment --}}
                                            @if($isOwnerComment || ($isTeacher && !$isOwnerComment))
                                                <form action="{{ route('channel.comment.delete', $comment->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 text-sm">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Comment content / edit form --}}
                                    <p x-show="!editingComment">{{ $comment->content }}</p>

                                    <form x-show="editingComment" action="{{ route('channel.comment.update', $comment->id) }}" method="POST" class="mt-1">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="content" value="{{ $comment->content }}" class="w-full p-2 border rounded mb-1">
                                        <div class="flex gap-2">
                                            <button type="submit" class="text-green-500 text-sm">Update</button>
                                            <button type="button" @click="editingComment = false" class="text-red-500 text-sm">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            @endforeach

                            <form action="{{ route('channel.comment', $post->id) }}" method="POST" class="mt-2">
                                @csrf
                                <input type="text" name="content" class="w-full p-2 border rounded" placeholder="Reply…">
                            </form>
                        </div>

                    </div>
                @endforeach

            </div>
        @endif
        @endauth
        </div>

        {{-- Post in Class Button + Box --}}
        <div x-data="{ showPostBox: false }" class="post-box mt-auto">
            <div x-show="showPostBox" x-transition class="mb-2">
                <form action="{{ route('channel.post', $class->id) }}" method="POST">
                    @csrf
                    <textarea name="content"
                              class="w-full p-3 border rounded"
                              rows="3"
                              placeholder="Start a new conversation…"></textarea>
                    <button class="btn-primary">Post</button>
                </form>
            </div>

            <button
                class="btn-primary"
                @click="showPostBox = !showPostBox"
            >
                <span x-text="showPostBox ? '×' : 'Post in Class'"></span>
            </button>
        </div>


<script src="//unpkg.com/alpinejs" defer></script>
@endsection
