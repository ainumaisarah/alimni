@extends('layouts.app')

@section('content')
<div class="chat-wrapper mx-auto flex border rounded shadow overflow-hidden">

    <!-- User List -->
    <div class="chat-sidebar">

        <!-- Sticky Header -->
        <h2 class="chat-sidebar-header">Chats</h2>

        <!-- Sticky Search Box -->
        <div class="chat-search-wrapper">
            <input
                type="text"
                id="userSearch"
                placeholder="Search user..."
                class="chat-search-input"
            />
        </div>

        <!-- Scrollable content -->
        <div class="chat-sidebar-body">
            <ul id="userList">
                @foreach($users as $user)
                    @php
                        // Count unread messages from this user to the logged-in user
                        $unreadCount = \App\Models\Message::where('sender_id', $user->id)
                                        ->where('receiver_id', auth()->id())
                                        ->where('is_read', 0)
                                        ->count();
                    @endphp
                    <li class="border-b chat-user-row">
                        <a href="{{ route('chat.show', $user->id) }}" class="chat-user-item">
                            <span class="chat-user-name">{{ $user->name }}</span>
                            @if($unreadCount > 0)
                                <span style="display:inline-block; background:red; color:white; font-size:10px; width:16px; height:16px; text-align:center; line-height:16px; border-radius:50%; margin-left:5px;">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>

    <!-- Right Side Dynamic Section -->
    <div class="flex-1 overflow-y-auto chat-right-panel">
        @yield('chatbox')
    </div>

</div>

<!-- Simple Search Script -->
<script>
    const searchInput = document.getElementById('userSearch');
    const userRows = document.querySelectorAll('.chat-user-row');

    searchInput.addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase();

        userRows.forEach(row => {
            const name = row.innerText.toLowerCase();
            row.style.display = name.includes(keyword) ? '' : 'none';
        });
    });
</script>

@endsection

