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
</div>
@endsection
