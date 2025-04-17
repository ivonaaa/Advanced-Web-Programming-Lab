<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dodaj novi rad') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Naziv rada (HR):</label>
                            <input type="text" name="title_hr" required class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Naziv rada (EN):</label>
                            <input type="text" name="title_en" required class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Opis rada:</label>
                            <textarea name="description" required class="mt-1 block w-full border-gray-300 rounded-md"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tip studija:</label>
                            <select name="study_type" required class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="stručni">Stručni</option>
                                <option value="preddiplomski">Preddiplomski</option>
                                <option value="diplomski">Diplomski</option>
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Spremi rad
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
