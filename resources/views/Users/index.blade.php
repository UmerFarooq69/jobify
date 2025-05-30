<x-admin>
    <div class="flex flex-wrap gap-6 mb-6">
        <a href="{{ route('users.create') }}"
            class="bg-gradient-to-r from-blue-900 to-blue-300 text-white py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
            + Create New User</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        @foreach($users as $user)
            <div class="bg-white p-4 border border-gray-200 rounded-lg shadow-md flex flex-col justify-between h-36">
                <div>
                    <h2 class="text-gray-700 mb-2 truncate"><strong>Username:</strong> {{ $user->name }}</h2>
                    <p class="text-gray-700 mb-2"><strong>Email:</strong> {{ $user->email }}</p>
                </div>

                <div class="flex justify-between items-center">
                    <a href="" class="text-yellow-500 hover:underline">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline delete-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="transition duration-300 ease-in-out 
                                {{ $user->active == 1 ? 'bg-red-500 hover:bg-red-600 text-white' : 'bg-blue-500 hover:bg-blue-600 text-white' }} 
                                focus:outline-none focus:ring-2 focus:ring-offset-2 
                                focus:ring-teal-500 rounded-lg px-6 py-2">
                            {{ $user->active == 1 ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    </div>
</x-admin>