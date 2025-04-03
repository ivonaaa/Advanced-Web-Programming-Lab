<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Uredi Projekt') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('projects.update', $project->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Naziv projekta:</label>
                            <input type="text" name="name" id="name" value="{{ $project->name }}" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Opis:</label>
                            <textarea name="description" id="description" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">{{ $project->description }}</textarea>
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Cijena:</label>
                            <input type="number" name="price" id="price" value="{{ $project->price }}" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Datum početka:</label>
                            <input type="date" name="start_date" id="start_date" value="{{ $project->start_date }}" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Datum završetka:</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $project->end_date }}" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="tasks" class="block text-sm font-medium text-gray-700">Zadaci:</label>
                            <div id="tasks-list" class="space-y-2">
                                @foreach ($project->tasks as $task)
                                    <div class="flex items-center gap-4" data-id="{{ $task->id }}">
                                        <input type="text" name="tasks[{{ $task->id }}][name]" value="{{ $task->name }}" class="p-2 w-full border border-gray-300 rounded-md">
                                        <label for="tasks[{{ $task->id }}][completed]" class="inline-flex items-center">
                                            <input type="checkbox" name="tasks[{{ $task->id }}][completed]" value="1" {{ $task->completed ? 'checked' : '' }} class="form-checkbox h-4 w-4 text-blue-600">
                                            <span class="text-xs ml-2">Obavljen</span>
                                        </label>
                                        <button type="button" class="remove-task text-red-500">X</button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-task" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Dodaj zadatak</button>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Spremi promjene</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const addTaskBtn = document.getElementById("add-task");
            const tasksList = document.getElementById("tasks-list");

            addTaskBtn.addEventListener("click", function () {
                const taskHtml = `
                    <div class="flex items-center gap-4">
                        <input type="text" name="tasks[new][name]" class="p-2 w-full border border-gray-300 rounded-md" placeholder="Naziv zadatka">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="tasks[new][completed]" value="1" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="text-xs ml-2">Obavljen</span>
                        </label>
                        <button type="button" class="remove-task text-red-500">X</button>
                    </div>
                `;
                tasksList.insertAdjacentHTML('beforeend', taskHtml);
            });

            tasksList.addEventListener("click", function (event) {
                if (event.target.classList.contains("remove-task")) {
                    event.target.parentElement.remove();
                }
            });
        });
    </script>
</x-app-layout>
