-<x-app-layout>
    <x-slot name="header">Moji radovi</x-slot>

    <div class="p-6">
        <a href="{{ route('tasks.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">➕ Novi rad</a>

        <ul class="mt-4 space-y-3">
            @foreach ($tasks as $task)
                <li class="p-4 border rounded shadow">
                    <div class="font-semibold">{{ $task->title_hr }}</div>
                    <p class="text-sm text-gray-600">{{ $task->description }}</p>

                    <p class="mt-2 text-xs text-gray-400">Prijavljeni studenti:</p>
                    <ul class="ml-4 text-sm list-disc">
                        @foreach ($task->students as $student)
                            <li>{{ $student->name }} ({{ $student->email }})
                                @if ($task->accepted_student_id === $student->id)
                                    <span class="text-green-600 font-bold">[PRIHVAĆEN]</span>
                                @else
                                    <form action="{{ route('tasks.acceptStudent', $task) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                                        <button class="text-blue-600 hover:underline text-xs">Prihvati</button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
