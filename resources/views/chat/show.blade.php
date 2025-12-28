@extends('chat.index')

@section('chatbox')

<div class="chat-container">

    <!-- Sticky Header -->
    <div class="chat-header">
        {{ $chatUser->name }}
    </div>

    <!-- Messages -->
    <div class="chat-messages" id="chat-box">
        @foreach($messages as $msg)
            <div class="chat-message {{ $msg->sender_id === auth()->id() ? 'sent' : 'received' }}">
                <p>{{ $msg->message }}</p>
                <small>
                    {{ $msg->created_at->timezone('Asia/Kuala_Lumpur')->isToday()
                        ? $msg->created_at->timezone('Asia/Kuala_Lumpur')->format('H:i')
                        : $msg->created_at->timezone('Asia/Kuala_Lumpur')->format('d M, H:i') }}
                </small>

            </div>
        @endforeach
    </div>

    <!-- Input Area -->
    <div class="chat-input">
        <form action="{{ route('chat.send', $chatUser->id) }}" method="POST" class="flex gap-2">
            @csrf

            <input
                type="text"
                name="message"
                placeholder="Type a message..."
                class="flex-1 border rounded px-3 py-2 focus:outline-none"
                required
            >

            <button
                class="btn-primary"
                type="submit"
            >
                Send
            </button>
        </form>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    });
</script>

@endsection
