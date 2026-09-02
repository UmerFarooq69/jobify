<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Jobify</title>
    <script src="{{ asset('js/autocomplete.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="{{ asset('assets/images/logo/logo.jpg') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body, html { height: 100%; margin: 0; }
        .page-wrapper { display: flex; flex-direction: column; min-height: 100%; }
        .content { flex-grow: 1; }

        #loader {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.9);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999; visibility: visible; opacity: 1;
            transition: visibility 0.4s ease, opacity 0.4s ease;
        }
        #loader.hidden { visibility: hidden; opacity: 0; }
        .loader-inner { text-align: center; }
        .spinner {
            width: 44px; height: 44px;
            border: 3px solid rgba(255,255,255,0.15);
            border-top-color: #1d4ed8;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
            margin: 0 auto;
        }
        .loader-logo {
            width: 48px; height: 48px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 16px;
            display: block;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <div id="loader">
        <div class="loader-inner">
            <img src="{{ asset('assets/images/logo/logo.jpg') }}" class="loader-logo" alt="Jobify">
            <div class="spinner"></div>
        </div>
    </div>

    <div class="page-wrapper">
        <!-- Navbar -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <a href="/" class="flex items-center space-x-2 flex-shrink-0">
                        <img src="{{ asset('assets/images/logo/logo.jpg') }}" alt="Jobify" class="w-9 h-9 rounded-full object-cover ring-2 ring-blue-100">
                        <span class="text-xl font-bold text-gray-900 tracking-tight">Jobify</span>
                    </a>

                    <!-- Desktop Nav Links -->
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('jobs.index') }}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('jobs.index') ? 'text-blue-700 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Jobs
                        </a>
                        <a href="{{ route('salaries.index') }}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('salaries.index') ? 'text-blue-700 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Salaries
                        </a>
                        <a href="{{ route('companies') }}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('companies') ? 'text-blue-700 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Companies
                        </a>
                        <a href="{{ route('contact.submit') }}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('contact.submit') ? 'text-blue-700 bg-blue-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Contact
                        </a>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="hidden md:flex items-center space-x-3">
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors">
                                    <i class="mdi mdi-view-dashboard text-sm"></i> Dashboard
                                </a>
                            @else
                                <a href="{{ route('Users.dashboard') }}" class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors">
                                    <i class="mdi mdi-account text-sm"></i> Dashboard
                                </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 border border-gray-300 hover:bg-gray-100 text-gray-600 text-sm font-medium py-2 px-4 rounded-lg transition-colors">
                                    <i class="mdi mdi-logout text-sm"></i> Sign Out
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors">
                                <i class="mdi mdi-login text-sm"></i> Sign In
                            </a>
                        @endauth
                    </div>

                    <!-- Hamburger -->
                    <button id="hamburger" class="md:hidden p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden border-t border-gray-100 bg-white">
                <div class="px-4 py-3 space-y-1">
                    <a href="{{ route('jobs.index') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('jobs.index') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }}">Jobs</a>
                    <a href="{{ route('salaries.index') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('salaries.index') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }}">Salaries</a>
                    <a href="{{ route('companies') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('companies') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }}">Companies</a>
                    <a href="{{ route('contact.submit') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('contact.submit') ? 'text-blue-700 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }}">Contact</a>
                    <div class="pt-2 border-t border-gray-100 space-y-1">
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                            @else
                                <a href="{{ route('Users.dashboard') }}" class="block px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100">My Dashboard</a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left block px-3 py-2 text-sm font-medium rounded-md text-red-600 hover:bg-red-50">Sign Out</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-3 py-2 text-sm font-medium rounded-md text-blue-700 hover:bg-blue-50">Sign In</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <div class="content">{{ $slot }}</div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 mt-auto">
            <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="space-y-3">
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset('assets/images/logo/logo.jpg') }}" alt="Jobify" class="h-9 w-9 rounded-full object-cover">
                        <span class="text-white font-bold text-lg">Jobify</span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">The leading HR outsourcing platform with two decades of specialized experience connecting talent with opportunity.</p>
                    <div class="flex space-x-3 pt-1">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="mdi mdi-facebook text-lg"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="mdi mdi-linkedin text-lg"></i></a>
                    </div>
                </div>
                <!-- Quick Links -->
                <div>
                    <h3 class="text-white font-semibold text-xs uppercase tracking-wider mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Resources</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
                <!-- Partners -->
                <div>
                    <h3 class="text-white font-semibold text-xs uppercase tracking-wider mb-4">Our Partners</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>Intellisense</li>
                        <li>Ecare</li>
                        <li>MCare360</li>
                        <li>World Health Corporation</li>
                        <li>MONUSCO</li>
                    </ul>
                </div>
                <!-- Contact -->
                <div>
                    <h3 class="text-white font-semibold text-xs uppercase tracking-wider mb-4">Contact Us</h3>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex items-start gap-2">
                            <i class="mdi mdi-map-marker mt-0.5 text-blue-400 flex-shrink-0"></i>
                            Ross Residentia, Canal Rd, Lahore, Punjab, Pakistan
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="mdi mdi-phone text-blue-400 flex-shrink-0"></i>
                            +92-301-4370259
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="mdi mdi-email text-blue-400 flex-shrink-0"></i>
                            info@jobify.official
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800">
                <div class="max-w-7xl mx-auto px-6 py-4 text-center text-xs text-gray-500">
                    &copy; {{ date('Y') }} Jobify. All rights reserved.
                </div>
            </div>
        </footer>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            Swal.fire({ toast: true, position: "top-end", icon: "success", title: "{{ session('success') }}", showConfirmButton: false, timer: 3000, timerProgressBar: true });
        @endif
        @if(session('error'))
            Swal.fire({ toast: true, position: "top-end", icon: "error", title: "{{ session('error') }}", showConfirmButton: false, timer: 3000, timerProgressBar: true });
        @endif

        const hamburger = document.getElementById("hamburger");
        const mobileMenu = document.getElementById("mobile-menu");
        const loader = document.getElementById("loader");

        hamburger.addEventListener("click", () => mobileMenu.classList.toggle("hidden"));
        document.addEventListener("click", (e) => {
            if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) mobileMenu.classList.add("hidden");
        });
        setTimeout(() => loader.classList.add("hidden"), 450);
    });
    </script>
</body>
</html>
