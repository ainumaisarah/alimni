@extends('layouts.app')

@section('content')
<div class="page-container p-6">

    <h2 class="text-xl font-semibold mb-4">My Materials</h2>

    <a href="{{ route('teacher.materials.create') }}"
       class="btn-primary mb-4 inline-block">
       + Upload Material
    </a>

    @if($materials->isEmpty())
        <p class="empty-message">No materials yet.</p>
    @else
        <div class="info-card overflow-x-auto">
            <table class="w-full border-collapse text-center">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">Title</th>
                        <th class="border px-4 py-2">Class</th>
                        <th class="border px-4 py-2">Files</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($materials as $material)
                        <tr>
                            <td class="border px-4 py-2">
                                {{ $material->title }}
                            </td>

                            <td class="border px-4 py-2">
                                {{ $material->classroom->name }}
                            </td>

                            <td class="border px-4 py-2">
                                {{ $material->files->count() }}
                            </td>

                            <td class="border px-4 py-2">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('classes.materials', $material->classroom_id) }}"
                                       class="btn-secondary">
                                       View
                                    </a>

                                    <form action="{{ route('teacher.materials.destroy', $material->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this material?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    @endif

</div>
@endsection
