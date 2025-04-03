<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kreiraj Novi Projekt') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('projects.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Naziv projekta:</label>
                            <input type="text" name="name" id="name" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Opis:</label>
                            <textarea name="description" id="description" required class="mt-1 p-2 w-full border border-gray-300 rounded-md"></textarea>
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Cijena:</label>
                            <input type="number" name="price" id="price" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Datum početka:</label>
                            <input type="date" name="start_date" id="start_date" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Datum završetka:</label>
                            <input type="date" name="end_date" id="end_date" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="team_members" class="block text-sm font-medium text-gray-700">Dodaj članove tima:</label>
                            <div class="flex items-center gap-2">
                                <select id="user-select" class="border-gray-300 rounded-md p-2">
                                    <option value="">Odaberi korisnika</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" id="add-user" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-700">+</button>
                            </div>
                            <ul id="selected-users" class="mt-2"></ul>
                        </div>

                        <div>
                            <label for="tasks" class="block text-sm font-medium text-gray-700">Dodaj zadatke:</label>
                            <div class="flex items-center gap-2">
                                <input type="text" id="task-name" class="border-gray-300 rounded-md p-2" placeholder="Naziv zadatka">
                                <button type="button" id="add-task" class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-700">+</button>
                            </div>
                            <ul id="task-list" class="mt-2"></ul>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Spremi projekt</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const userSelect = document.getElementById("user-select");
            const addUserBtn = document.getElementById("add-user");
            const selectedUsersList = document.getElementById("selected-users");

            const taskInput = document.getElementById("task-name");
            const addTaskBtn = document.getElementById("add-task");
            const taskList = document.getElementById("task-list");

            addUserBtn.addEventListener("click", function () {
                const userId = userSelect.value;
                const userName = userSelect.options[userSelect.selectedIndex].text;

                if (userId && !document.querySelector(`li[data-id='${userId}']`)) {
                    const li = document.createElement("li");
                    li.classList.add("flex", "items-center", "gap-2", "mt-1", "user-item");
                    li.setAttribute("data-id", userId);
                    li.innerHTML = `${userName} 
                        <button type="button" class="remove-user text-red-500">X</button>
                        <input type="hidden" name="team_members[]" value="${userId}">`;
                    selectedUsersList.appendChild(li);
                }
            });

            addTaskBtn.addEventListener("click", function () {
                const taskName = taskInput.value;

                if (taskName && !document.querySelector(`li[data-name='${taskName}']`)) {
                    const li = document.createElement("li");
                    li.classList.add("flex", "items-center", "gap-2", "mt-1", "task-item");
                    li.setAttribute("data-name", taskName);
                    li.innerHTML = `${taskName} 
                        <button type="button" class="remove-task text-red-500">X</button>
                        <input type="hidden" name="tasks[]" value="${taskName}">`;
                    taskList.appendChild(li);
                    taskInput.value = ''; 
                }
            });

            selectedUsersList.addEventListener("click", function (event) {
                if (event.target.classList.contains("remove-user")) {
                    event.target.parentElement.remove();
                }
            });

            taskList.addEventListener("click", function (event) {
                if (event.target.classList.contains("remove-task")) {
                    event.target.parentElement.remove();
                }
            });
        });
    </script>
</x-app-layout>
