<x-form>
    <div class="h-screen flex items-center justify-center bg-gray-100 px-6">
        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden max-w-4xl w-full grid grid-cols-2 relative">
            <div class="p-10 flex flex-col justify-center transition-all duration-500" id="form-container">
                <h2 class="text-4xl font-extrabold text-gray-900 mb-6 text-center" id="form-title">Welcome Back</h2>
                <p class="text-gray-500 text-center mb-6" id="form-subtitle">Login to access your account</p>
                
                <form action="{{ route('login.submit') }}" method="POST" class="space-y-6" id="login-form">
                    @csrf
                    @if ($errors->has('name'))
                        <div class="text-red-500 text-sm mb-4 bg-red-100 p-3 rounded-lg border border-red-400">
                            {{ $errors->first('name') }}
                        </div>
                    @endif

                    <div>
                        <label for="name" class="text-gray-700 text-sm font-medium">Username</label>
                        <input type="text" id="name" name="name" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 placeholder-gray-400 border border-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 transition-all" value="{{ old('name') }}">
                    </div>

                    <div>
                        <label for="password" class="text-gray-700 text-sm font-medium">Password</label>
                        <input type="password" id="password" name="password" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 placeholder-gray-400 border border-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 transition-all">
                    </div>

                    <div class="flex justify-between items-center">
                        <label class="flex items-center text-gray-700 text-sm">
                            <input type="checkbox" class="h-4 w-4 text-blue-500 border-gray-300 rounded focus:ring-blue-400" name="remember">
                            <span class="ml-2">Remember Me</span>
                        </label>
                        <a href="#" class="text-sm text-blue-500 hover:text-blue-600 transition">Forgot Password?</a>
                    </div>

                    <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition duration-300 focus:outline-none focus:ring-4 focus:ring-blue-400 shadow-md text-lg font-semibold">
                        Login
                    </button>
                </form>
            </div>         
            <div class="hidden md:flex items-center justify-center bg-blue-500 text-white p-10">
                <div class="text-center">
                    <h2 class="text-3xl font-bold">Join Us</h2>
                    <p class="text-white/80 mt-4">Contact us today to register your buisness and enjoy seamless access to our platform.</p>
                    <a href="#" class="inline-block mt-6 bg-white text-blue-500 px-6 py-3 rounded-full font-semibold shadow-lg hover:bg-gray-100 transition">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</x-form>
