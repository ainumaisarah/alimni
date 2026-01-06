@extends('layouts.app')

@section('content')
<div class="class-container">
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

    @if($quiz->duration)
        <div class="sticky top-4 z-50 mb-4 p-2 bg-yellow-100 border rounded text-yellow-800 font-semibold shadow" id="countdown">
            Time remaining: <span id="timeRemaining">{{ $quiz->duration }}:00</span>
        </div>
    @endif

    <div class="classbox">
        <form action="{{ route('student.quizzes.submit', $quiz->id) }}" method="POST">
            @csrf

            @foreach($questions as $q)
                <div class="info-card p-4 mb-4 border rounded shadow-sm">
                    <p class="font-semibold mb-2">{{ $loop->iteration }}. {{ $q->question_text }}</p>

                    @if($q->question_type == 'mcq')
                        @foreach(['a','b','c','d'] as $opt)
                            @php
                                $optionValue = $q->{'option_'.$opt};
                            @endphp

                            @if($optionValue)
                                <label class="block ml-4 mb-1">
                                    <input type="radio"
                                           name="answers[{{ $q->id }}]"
                                           value="{{ strtoupper($opt) }}"
                                           required>
                                    {{ $optionValue }}
                                </label>
                            @endif
                        @endforeach

                    @elseif($q->question_type == 'short')
                        <textarea name="answers[{{ $q->id }}]"
                                  class="w-full border rounded p-2 resize-y h-32 overflow-auto"
                                  placeholder="Type your answer here..."
                                  required></textarea>
                    @endif
                </div>
            @endforeach

            <button type="submit" class="btn-primary">Submit Quiz</button>
        </form>
    </div>

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

    @if($quiz->duration)
    const durationMinutes = {{ $quiz->duration }};
    let timeLeft = durationMinutes * 60; // in seconds

    const countdownEl = document.getElementById('timeRemaining');

    const timerInterval = setInterval(() => {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            alert('Time is up! Your quiz will be submitted automatically.');

            document.querySelector('form').submit();
        }

        timeLeft--;
    }, 1000);
    @endif

    </script>
</div>
@endsection
