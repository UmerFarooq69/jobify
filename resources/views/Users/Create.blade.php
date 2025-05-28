<x-form>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-900 to-blue-100">
        <form action="{{ route('users.store') }}" method="POST" class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
            @csrf
            <div class="pt-4">
                <label for="name" class="text-white font-semibold">Name</label>
                <input type="text" id="name" name="name" required class="w-full mt-2 p-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="pt-4">
                <label for="email" class="text-white font-semibold">Email</label>
                <input type="email" id="email" name="email" required class="w-full mt-2 p-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="pt-4">
                <label for="password" class="text-white font-semibold">Password</label>
                <input type="password" id="password" name="password" required class="w-full mt-2 p-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="pt-4">
                <label for="role" class="text-white font-semibold">Admin Role</label>
                <input type="checkbox" id="role" name="role" value="admin" class="w-5 h-5 mt-2 bg-gray-700 border-none focus:ring-2 focus:ring-blue-500">
            </div>
            

            <div class="pt-4">
                <label for="active" class="text-white font-semibold">Active</label>
                <input type="checkbox" id="active" name="active" value="1" checked class="w-5 h-5 mt-2 bg-gray-700 border-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full mt-6 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Create User
            </button>
        </form>
    </div>
</x-form>
