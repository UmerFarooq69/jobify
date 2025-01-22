<x-form>
    <div class="h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-900">
        <form action="{{ route('login.submit') }}" method="POST" class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
            @csrf
            <h2 class="text-2xl font-semibold text-white text-center mb-6">Login</h2>
            @if ($errors->has('name'))
                <div class="text-red-500 text-sm mb-4">
                    {{ $errors->first('name') }}
                </div>
            @endif

            <div class="pt-4">
                <label for="name" class="text-white font-semibold">Username</label>
                <input type="text" id="name" name="name" required class="w-full mt-2 p-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('name') }}">
            </div>

            <div class="pt-4">
                <label for="password" class="text-white font-semibold">Password</label>
                <input type="password" id="password" name="password" required class="w-full mt-2 p-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-between items-center pt-4">
                <a href="#" class="text-sm text-blue-600 hover:text-blue-700">Forgot Password?</a>
            </div>

            <button type="submit" class="w-full mt-6 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Login
            </button>
        </form>
    </div>
</x-form>
