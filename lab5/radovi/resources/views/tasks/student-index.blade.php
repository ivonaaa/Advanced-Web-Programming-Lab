<x-app-layout>
    <x-slot name="header">Radovi</x-slot>

    <div class="p-6">
        <form action="{{ route('tasks.apply') }}" method="POST">
            @csrf
            <h2 class="font-bold mb-2">Svi dostupni radovi:</h2>
            <ul class="space-y-2">
                @foreach ($allTasks as $task)
                    <li class="p-3 border rounded shadow-sm flex items-center gap-3">
                        <input type="checkbox" name="tasks[]" value="{{ $task->id }}"
                            {{ in_array($task->id, $myTasks) ? 'checked' : '' }}>
                        <div>
                            <div class="font-semibold">{{ $task->title_hr }}</div>
                            <div class="text-sm text-gray-600">{{ $task->study_type }}</div>
                            <div class="text-xs text-gray-500">Mentor: {{ $task->teacher->name }}</div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <button type="submit" class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Pošalji prijave
            </button>
        </form>
    </div>
</x-app-layout>
