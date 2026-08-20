<x-form>
    <div class="max-w-lg mx-auto px-4 sm:px-6 py-10">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Create User</h1>
                <p class="text-gray-500 text-sm mt-1">Add a new user to the platform</p>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
                    <input type="text" id="name" name="name" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                        placeholder="Enter username">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                        placeholder="user@example.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                        placeholder="Set a secure password">
                </div>

                <div class="flex items-center justify-between py-3 border border-gray-200 rounded-lg px-4">
                    <div>
                        <label for="role" class="text-sm font-medium text-gray-700">Admin Role</label>
                        <p class="text-xs text-gray-400">Grant admin privileges</p>
                    </div>
                    <input type="checkbox" id="role" name="role" value="admin" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                </div>

                <div class="flex items-center justify-between py-3 border border-gray-200 rounded-lg px-4">
                    <div>
                        <label for="active" class="text-sm font-medium text-gray-700">Active</label>
                        <p class="text-xs text-gray-400">Allow user to log in</p>
                    </div>
                    <input type="checkbox" id="active" name="active" value="1" checked class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg transition-colors font-semibold text-sm shadow-sm">
                    Create User
                </button>
            </form>
        </div>
    </div>
</x-form>
