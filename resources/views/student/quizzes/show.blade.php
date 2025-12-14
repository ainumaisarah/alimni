@extends('layouts.app')

@section('content')
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('classes.quizzes', $quiz->classroom_id) }}"
            class="h-8 w-8 inline-flex items-center justify-center p-2
                    bg-gray-100 hover:bg-gray-200 rounded-lg
                    text-[#2b5948] hover:text-[#1f4033]">
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
        <h2 style="font-size: 22px; font-weight: 650; color: #2b5948;">{{ $quiz->title }}</h2>
    </div>
<div class = "info-card">
<form action="{{ route('student.quizzes.submit', $quiz->id) }}" method="POST">
    @csrf

    @foreach($questions as $q)
        <div class="mb-4 p-3 border rounded">
            <p class="font-semibold">{{ $loop->iteration }}. {{ $q->question_text }}</p>

            @if($q->question_type == 'mcq')
                @foreach(['a','b','c','d'] as $opt)
                    @php
                        $optionValue = $q->{'option_'.$opt};
                    @endphp

                    @if($optionValue)
                        <label class="block ml-4">
                            <input type="radio"
                                   name="answers[{{ $q->id }}]"
                                   value="{{ strtoupper($opt) }}"
                                   required>

                            {{ $optionValue }}
                        </label>
                    @endif
                @endforeach

            @elseif($q->question_type == 'short')
                <input type="text"
                       name="answers[{{ $q->id }}]"
                       class="w-full border rounded p-2"
                       required>
            @endif

        </div>
    @endforeach  {{-- ✔ CORRECTLY CLOSED FOREACH --}}

    <button type="submit" class="btn-primary">Submit Quiz</button>
</form>

<script src="{{ asset('/offlineQuizzes.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const quiz = {
        id: {{ $quiz->id }},
        title: "{{ $quiz->title }}",
        questions: @json($questions)
    };

    if (navigator.onLine) {
        saveQuizOffline(quiz);
    }

    const form = document.querySelector('form');

    form.addEventListener('submit', async (e) => {
        if (!navigator.onLine) {
            e.preventDefault();
            const answers = Object.fromEntries(new FormData(form).entries());

            await saveSubmissionOffline({
                quiz_id: quiz.id,
                answers: answers,
                timestamp: new Date().toISOString()
            });

            if ('serviceWorker' in navigator && 'SyncManager' in window) {
                const registration = await navigator.serviceWorker.ready;
                await registration.sync.register('sync-submissions');
            }

            alert('You are offline. Your answers are saved and will be submitted when back online.');
            form.reset();
        }
    });
});
</script>

</div>
@endsection
