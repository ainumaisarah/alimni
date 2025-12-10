@extends('layouts.app')

@section('content')
<div class="page-container">
    <h2>{{ $quiz->title }}</h2>

   @if($result && !$canRetake)
        <div class="success-alert mb-4">
            You already submitted this quiz. Score: {{ $result->score }}/{{ $quiz->questions->count() }}
        </div>
    @endif

<form action="{{ route('student.quizzes.submit', $quiz->id) }}" method="POST">
    @csrf

    @foreach($questions as $question)
    <div class="app-card">
        <p>{{ $loop->iteration }}. {{ $question->question_text }}</p>

        @php
            $prevAnswer = strtoupper($result->answers[$question->id] ?? '');
            $correctAnswer = strtoupper($question->correct_answer);
        @endphp

        @foreach(['a','b','c','d'] as $option)
            @php
                $optionText = $question->{'option_'.$option};
                $isSelected = $prevAnswer == strtoupper($option);
                $isCorrect = $correctAnswer == strtoupper($option);
                $icon = '';

                if($result) {
                    if($isCorrect) {
                        $icon = '✅';
                    } elseif($isSelected && !$isCorrect) {
                        $icon = '❌';
                    }
                }

                // Disable old answers only if they exist
                $disabled = ($result && $prevAnswer && !$canRetake) ? 'disabled' : '';
            @endphp

            <div class="p-3 rounded mb-2 flex items-center gap-2">
                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}"
                    {{ $isSelected ? 'checked' : '' }}
                    {{ $disabled }}
                >
                <span class="font-medium">{{ strtoupper($option) }}. {{ $optionText }} {{ $icon }}</span>
            </div>
        @endforeach
    </div>
    @endforeach

    @if($canRetake)
        <button type="submit" class="btn-primary">
            Submit Quiz
        </button>
    @endif
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

    // <-- REPLACE your old form submit handler with this one
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
                console.log('Background sync registered for offline quiz submission.');
            }

            alert('You are offline. Your answers are saved and will be submitted when back online.');
            form.reset();
        }
    });
});
</script>

</div>
@endsection
