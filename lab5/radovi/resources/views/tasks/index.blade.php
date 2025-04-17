<x-app-layout>
    <x-slot name="header">Radovi</x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- STUDENT --}}
        @if (auth()->user()->isStudent())
            {{-- TABS --}}
            <div class="mb-6 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="?tab=available" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request('tab', 'available') === 'available' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Dostupni radovi
                    </a>
                    <a href="?tab=applied" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request('tab') === 'applied' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Moji prijavljeni radovi
                    </a>
                </nav>
            </div>

            {{-- TAB: Dostupni radovi --}}
            @if (request('tab', 'available') === 'available')
                <form action="{{ route('tasks.apply') }}" method="POST">
                    @csrf
                    <h2 class="text-lg font-semibold mb-4">Odaberi do 5 radova za prijavu</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($tasks as $task)
                            <div class="p-4 border rounded shadow-sm">
                                <label class="flex items-start gap-2">
                                    <input type="checkbox" name="tasks[]" value="{{ $task->id }}"
                                           {{ auth()->user()->tasks->contains($task->id) ? 'checked' : '' }}
                                           class="mt-1 text-blue-600 form-checkbox h-5 w-5">
                                    <div>
                                        <p><strong>{{ $task->title_hr }}</strong></p>
                                        <p class="text-sm text-gray-500 italic">{{ $task->title_en }}</p>
                                        <p class="text-sm mt-2">{{ $task->description }}</p>
                                        <p class="text-xs text-gray-600 mt-1">Studij: {{ $task->study_type }}</p>
                                        <p class="text-xs text-gray-400">Nastavnik: {{ $task->teacher->name }}</p>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 text-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Spremi prijave
                        </button>
                    </div>
                </form>
            @endif

            {{-- TAB: Moji prijavljeni radovi --}}
            @if (request('tab') === 'applied')
                <h2 class="text-lg font-semibold mb-4">Moji prijavljeni radovi</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse (auth()->user()->tasks as $task)
                        <div class="p-4 border rounded shadow-sm bg-gray-50">
                            <p><strong>{{ $task->title_hr }}</strong></p>
                            <p class="text-sm text-gray-500 italic">{{ $task->title_en }}</p>
                            <p class="text-sm mt-2">{{ $task->description }}</p>
                            <p class="text-xs text-gray-600 mt-1">Studij: {{ $task->study_type }}</p>
                            <p class="text-xs text-gray-400">Nastavnik: {{ $task->teacher->name }}</p>

                            @if ($task->accepted_student_id === auth()->id())
                                <div class="mt-2 text-green-600 font-semibold">✅ Prihvaćen si na ovom radu!</div>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500">Nisi se prijavio ni na jedan rad.</p>
                    @endforelse
                </div>
            @endif
        @endif

        {{-- NASTAVNIK --}}
        @if (auth()->user()->isTeacher())
            <h2 class="text-lg font-semibold mb-4">Tvoji radovi</h2>

            @forelse ($myTasks as $task)
                <div class="p-4 border rounded mb-4 shadow-sm">
                    <p><strong>{{ $task->title_hr }}</strong> ({{ $task->study_type }})</p>
                    <p class="text-sm text-gray-500 italic">{{ $task->title_en }}</p>
                    <p class="text-sm">{{ $task->description }}</p>

                    @if ($task->students->isNotEmpty())
                        <p class="mt-2 font-semibold">Prijavljeni studenti:</p>
                        <ul class="ml-4 list-disc">
                            @foreach ($task->students as $student)
                                <li class="text-sm flex items-center justify-between">
                                    {{ $student->name }} ({{ $student->email }})

                                    @if (!$task->acceptedStudent || $task->acceptedStudent->id !== $student->id)
                                        <form action="{{ route('tasks.acceptStudent', $task) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="student_id" value="{{ $student->id }}">
                                            <button type="submit" class="text-sm text-blue-600 hover:underline ml-2">
                                                Prihvati
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-green-600 font-semibold ml-2">✔ Prihvaćen</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 mt-2">Nitko se još nije prijavio.</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">Nemaš nijedan dodan rad.</p>
            @endforelse
        @endif

        {{-- ADMIN --}}
        @if (auth()->user()->isAdmin())
            <h2 class="text-lg font-semibold mb-4">Svi radovi u sustavu</h2>

            @forelse ($tasks as $task)
                <div class="p-4 border rounded mb-4 shadow-sm bg-gray-50">
                    <p><strong>{{ $task->title_hr }}</strong> ({{ $task->study_type }})</p>
                    <p class="text-sm text-gray-500 italic">{{ $task->title_en }}</p>
                    <p class="text-sm">{{ $task->description }}</p>
                    <p class="text-xs text-gray-600">Nastavnik: {{ $task->teacher->name }}</p>

                    @if ($task->students->isNotEmpty())
                        <p class="mt-2 font-semibold">Prijavljeni studenti:</p>
                        <ul class="ml-4 list-disc">
                            @foreach ($task->students as $student)
                                <li class="text-sm">
                                    {{ $student->name }} ({{ $student->email }})
                                    @if ($task->acceptedStudent && $task->acceptedStudent->id === $student->id)
                                        <span class="text-green-600 font-semibold ml-2">✔ Prihvaćen</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 mt-2">Nitko se još nije prijavio.</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">Nema nijedan rad u sustavu.</p>
            @endforelse
        @endif
    </div>
</x-app-layout>
