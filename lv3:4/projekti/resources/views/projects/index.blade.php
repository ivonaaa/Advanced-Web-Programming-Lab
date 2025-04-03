<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Moji Projekti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Moji Projekti -->
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Moji Projekti</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 shadow-md rounded-lg">
                            <thead class="bg-gray-100">
                                <tr class="text-left text-sm font-medium text-gray-500">
                                    <th class="py-3 px-6">Ime projekta</th>
                                    <th class="py-3 px-6">Opis</th>
                                    <th class="py-3 px-6">Cijena</th>
                                    <th class="py-3 px-6">Početak</th>
                                    <th class="py-3 px-6">Završetak</th>
                                    <th class="py-3 px-6 text-center">Akcije</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @foreach ($ownedProjects as $project)
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="py-3 px-6 text-sm font-medium">{{ $project->name }}</td>
                                        <td class="py-3 px-6 text-sm truncate max-w-xs">{{ $project->description }}</td>
                                        <td class="py-3 px-6 text-sm">{{ number_format($project->price, 2) }} €</td>
                                        <td class="py-3 px-6 text-sm">{{ \Carbon\Carbon::parse($project->start_date)->format('d.m.Y') }}</td>
                                        <td class="py-3 px-6 text-sm">{{ \Carbon\Carbon::parse($project->end_date)->format('d.m.Y') }}</td>
                                        <td class="py-3 px-6 text-center">
                                            <a href="{{ route('projects.edit', $project->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Uredi</a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="6">
                                            <div class="mt-4">
                                                <h4 class="text-md font-semibold text-gray-700">Zadaci:</h4>
                                                <ul class="list-disc pl-5">
                                                    @foreach ($project->tasks as $task)
                                                        <li class="flex items-center justify-between">
                                                            <span class="text-sm">{{ $task->name }}</span>
                                                            <form action="{{ route('tasks.update', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="flex items-center">
                                                                @csrf
                                                                @method('PUT')
                                                                <label for="completed" class="inline-flex items-center space-x-2">
                                                                    <input type="checkbox" name="completed" id="completed" value="1" 
                                                                    {{ $task->completed ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-blue-600">
                                                                    <span class="text-xs">Obavljen</span>
                                                                </label>
                                                                <button type="submit" class="ml-2 bg-blue-600 text-white px-2 py-1 text-xs rounded hover:bg-blue-700">
                                                                    Spremi
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($ownedProjects->isEmpty())
                            <p class="text-center text-gray-500 mt-4">Nema dostupnih projekata.</p>
                        @endif
                    </div>

                    <!-- Projekti na kojima sudjelujem -->
                    <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-4">Projekti na kojima sudjelujem</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 shadow-md rounded-lg">
                            <thead class="bg-gray-100">
                                <tr class="text-left text-sm font-medium text-gray-500">
                                    <th class="py-3 px-6">Ime projekta</th>
                                    <th class="py-3 px-6">Opis</th>
                                    <th class="py-3 px-6">Cijena</th>
                                    <th class="py-3 px-6">Početak</th>
                                    <th class="py-3 px-6">Završetak</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @foreach ($participatingProjects as $project)
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="py-3 px-6 text-sm font-medium">{{ $project->name }}</td>
                                        <td class="py-3 px-6 text-sm truncate max-w-xs">{{ $project->description }}</td>
                                        <td class="py-3 px-6 text-sm">{{ number_format($project->price, 2) }} €</td>
                                        <td class="py-3 px-6 text-sm">{{ \Carbon\Carbon::parse($project->start_date)->format('d.m.Y') }}</td>
                                        <td class="py-3 px-6 text-sm">{{ \Carbon\Carbon::parse($project->end_date)->format('d.m.Y') }}</td>
                                    </tr>

                                    <tr>
                                        <td colspan="6">
                                            <div class="mt-4">
                                                <h4 class="text-md font-semibold text-gray-700">Zadaci:</h4>
                                                <ul class="list-disc pl-5">
                                                    @foreach ($project->tasks as $task)
                                                        <li class="flex items-center justify-between">
                                                            <span class="text-sm">{{ $task->name }}</span>
                                                            <form action="{{ route('tasks.update', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" class="flex items-center">
                                                                @csrf
                                                                @method('PUT')
                                                                <label for="completed" class="inline-flex items-center space-x-2">
                                                                    <input type="checkbox" name="completed" id="completed" value="1" 
                                                                    {{ $task->completed ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-blue-600">
                                                                    <span class="text-xs">Obavljen</span>
                                                                </label>
                                                                <button type="submit" class="ml-2 bg-blue-600 text-white px-2 py-1 text-xs rounded hover:bg-blue-700">
                                                                    Spremi
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($participatingProjects->isEmpty())
                            <p class="text-center text-gray-500 mt-4">Niste dodani ni na jedan projekt.</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
