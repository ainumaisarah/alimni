@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Chat with {{ $chatUser->name }}</h2>

    <div class="bg-gray-100 p-4 rounded h-96 overflow-y-auto mb-4" id="chat-box">
        @foreach($messages as $msg)
            <div class="mb-2 {{ $msg->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                <span class="inline-block p-2 rounded {{ $msg->sender_id === auth()->id() ? 'bg-blue-200' : 'bg-gray-200' }}">
                    <strong>{{ $msg->sender_id === auth()->id() ? 'You' : $chatUser->name }}:</strong> {{ $msg->message }}
                    <small class="text-gray-500 block text-xs">{{ $msg->created_at->format('H:i') }}</small>
                </span>
            </div>
        @endforeach
    </div>

    <form action="{{ route('chat.send', $chatUser->id) }}" method="POST">
        @csrf
        <div class="flex">
            <input type="text" name="message" class="flex-1 border p-2 rounded-l" placeholder="Type your message..." required>
            <button type="submit" class="bg-blue-500 text-black px-4 rounded-r">Send</button>
        </div>
    </form>
</div>
@endsection
