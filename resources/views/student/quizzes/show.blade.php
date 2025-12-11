@extends('layouts.app')

@section('content')
<div class="page-container p-6">
<h2 class="mb-4">{{ $quiz->title }}</h2>

<form action="{{ route('student.quizzes.submit', $quiz->id) }}" method="POST">
    @csrf

    @foreach($questions as $q)
        <div class="mb-4 p-3 border rounded">
            <p class="font-semibold">{{ $loop->iteration }}. {{ $q->question_text }}</p>

             @if($q->question_type == 'mcq')
                    @foreach(['a','b','c','d'] as $opt)
                        @php $optionValue = $q->{'option_'.$opt}; @endphp
                        @if($optionValue)
                            <label class="block ml-4">
                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ strtoupper($opt) }}" required>
                                {{ $optionValue }}
                            </label>
                        @endif
                    @endforeach
                @elseif($q->question_type == 'short')
                    <input type="text" name="answers[{{ $q->id }}]" class="w-full border rounded p-2" required>
                @endif
            @endforeach

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
