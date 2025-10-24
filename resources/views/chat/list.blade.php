@extends('layouts.app')

@section('content')
<div class="container mx-auto flex h-[80vh] border rounded shadow overflow-hidden">

    <!-- User List -->
    <div class="w-1/3 bg-white overflow-y-auto border-r">
        <h2 class="text-xl font-bold p-4 border-b">Chats</h2>
        <ul>
            @foreach($users as $user)
                <li class="border-b p-4 flex justify-between items-center hover:bg-gray-100">
                    <span>{{ $user->name }}</span>
                    <a href="{{ route('chat.show', $user->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                        Chat
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Empty Chat Box / Placeholder -->
    <div class="flex-1 bg-gray-100 flex items-center justify-center text-gray-400">
        <p>Select a user to start chatting</p>
    </div>
</div>
@endsection
