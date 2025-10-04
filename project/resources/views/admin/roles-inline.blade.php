<div class="max-w-6xl mx-auto px-6 py-10">
    <h2 class="text-2xl font-bold text-purple-900 mb-6">Gestion des rôles</h2>

    <div class="bg-white rounded-2xl shadow border border-purple-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-purple-50 text-purple-700">
            <tr>
                <th class="px-4 py-3 text-left">Nom</th>
                <th class="px-4 py-3 text-left">Email</th>
                <th class="px-4 py-3 text-left">Rôle actuel</th>
                <th class="px-4 py-3 text-left">Nouveau rôle</th>
                <th class="px-4 py-3"></th>
            </tr>
            </thead>
            <tbody class="divide-y divide-purple-100">
            @foreach($users as $u)
                <tr>
                    <td class="px-4 py-3">{{ $u->prenom }} {{ $u->nom }}</td>
                    <td class="px-4 py-3">{{ $u->email }}</td>
                    <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($u->role === 'admin') bg-red-100 text-red-700
                                @elseif($u->role === 'organisateur') bg-indigo-100 text-indigo-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ $u->role }}
                            </span>
                    </td>
                    <td class="px-4 py-3">
                        <form action="{{ route('admin.role.update', $u) }}" method="POST" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <select name="role" class="border border-gray-300 rounded-lg px-2 py-1 text-sm">
                                <option value="participant" @selected($u->role === 'participant')>Participant</option>
                                <option value="organisateur" @selected($u->role === 'organisateur')>Organisateur</option>
                                <option value="admin" @selected($u->role === 'admin')>Admin</option>
                            </select>
                            <button type="submit" class="px-3 py-1 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-xs">
                                Sauver
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
