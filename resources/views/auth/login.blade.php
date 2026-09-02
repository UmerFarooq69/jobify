<x-form>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 py-12">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden max-w-4xl w-full grid grid-cols-1 md:grid-cols-2">

            <!-- Login Form -->
            <div class="p-8 md:p-10 flex flex-col justify-center">
                <div class="flex items-center gap-2 mb-8">
                    <img src="{{ asset('assets/images/logo/logo.jpg') }}" alt="Jobify" class="w-9 h-9 rounded-full object-cover">
                    <span class="text-xl font-bold text-gray-900">Jobify</span>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-1">Welcome back</h2>
                <p class="text-gray-500 text-sm mb-8">Sign in to access your account</p>

                <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    @if ($errors->has('name'))
                        <div class="text-red-600 text-sm bg-red-50 p-3 rounded-lg border border-red-200">
                            {{ $errors->first('name') }}
                        </div>
                    @endif

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition-colors text-sm"
                            placeholder="Enter your username"
                            value="{{ old('name') }}">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent transition-colors text-sm"
                            placeholder="Enter your password">
                    </div>

                    <div class="flex justify-between items-center text-sm">
                        <label class="flex items-center gap-2 text-gray-600 cursor-pointer">
                            <input type="checkbox" class="h-4 w-4 text-blue-700 border-gray-300 rounded focus:ring-blue-700" name="remember">
                            Remember me
                        </label>
                        <a href="#" class="text-blue-700 hover:text-blue-800 font-medium transition-colors">Forgot password?</a>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white py-2.5 rounded-lg transition-colors font-semibold text-sm shadow-sm">
                        Sign In
                    </button>
                </form>
            </div>

            <!-- Side Banner -->
            <div class="hidden md:flex flex-col items-center justify-center bg-blue-700 text-white p-10">
                <div class="text-center max-w-xs">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="mdi mdi-briefcase text-3xl text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold mb-3">Welcome to Jobify</h2>
                    <p class="text-blue-100 text-sm leading-relaxed">
                        Connect with thousands of qualified candidates and top employers across every industry.
                    </p>
                    <div class="mt-8 space-y-3 text-left">
                        <div class="flex items-center gap-3 text-blue-100 text-sm">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="mdi mdi-check text-xs text-white"></i>
                            </div>
                            500K+ job listings
                        </div>
                        <div class="flex items-center gap-3 text-blue-100 text-sm">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="mdi mdi-check text-xs text-white"></i>
                            </div>
                            AI-powered matching
                        </div>
                        <div class="flex items-center gap-3 text-blue-100 text-sm">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="mdi mdi-check text-xs text-white"></i>
                            </div>
                            Verified employers only
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-form>
