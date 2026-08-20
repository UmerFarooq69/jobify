<x-admin>
    <!-- Toolbar -->
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-plus text-xs text-gray-500"></i> Create New User
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($users as $user)
        <div class="bg-white border border-gray-200 rounded-xl p-5 flex flex-col gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-blue-600 text-sm"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</div>
                    <div class="text-xs text-gray-400 truncate">{{ $user->email }}</div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-xs font-medium px-3 py-1.5 rounded-lg transition-colors {{ $user->active == 1 ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                        {{ $user->active == 1 ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition-colors delete-btn">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</x-admin>
