<x-app-layout>
    <x-slot name="header">Korisnici</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white p-6 rounded shadow">
            @if(session('success'))
                <div class="mb-4 text-green-600">
                    {{ session('success') }}
                </div>
            @endif

            <table class="min-w-full table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">Ime</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Uloga</th>
                        <th class="px-4 py-2">Akcija</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2 capitalize">{{ $user->role }}</td>
                            <td class="px-4 py-2">
                                <form action="{{ route('users.updateRole', $user) }}" method="POST" class="flex space-x-2 items-center">
                                    @csrf
                                    <select name="role" class="border rounded px-2 py-1 text-sm">
                                        <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                        <option value="nastavnik" @selected($user->role === 'nastavnik')>Nastavnik</option>
                                        <option value="student" @selected($user->role === 'student')>Student</option>
                                    </select>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded">Spremi</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
