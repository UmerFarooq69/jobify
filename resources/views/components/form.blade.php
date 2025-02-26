<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Jobify</title>
    <script src="{{ asset('js/autocomplete.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="{{ asset('storage/img/logo.jpeg') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Ensuring the body takes the full height of the screen */
        body, html {
            height: 100%;
            margin: 0;
        }

        /* Flexbox layout to ensure footer stays at the bottom */
        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }

        /* Ensuring content grows and pushes footer to the bottom */
        .content {
            flex-grow: 1;
        }

        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(0, 123, 255, 0.4), rgba(0, 0, 0, 0.6));
            backdrop-filter: blur(15px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            visibility: visible;
            opacity: 1;
            transition: visibility 0.3s ease, opacity 0.3s ease;
        }

        #loader.hidden {
            visibility: hidden;
            opacity: 0;
        }

        .spinner {
            position: relative;
            width: 120px;
            height: 120px;
            border: 8px solid transparent;
            border-top: 8px solid #4caf50;
            border-right: 8px solid #2196f3;
            border-radius: 50%;
            animation: rotate 1s linear infinite;
        }

        .spinner::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 120%;
            height: 120%;
            border: 8px solid transparent;
            border-bottom: 8px solid #ff9800;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            animation: rotate 1.5s linear infinite reverse;
        }

        .logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80px;
            height: 80px;
            background: url('{{ asset('storage/img/logo.jpeg') }}') no-repeat center center;
            background-size: contain;
            border-radius: 50%;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.8), 0 0 25px rgba(0, 123, 255, 0.6);
        }

        @keyframes rotate {
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">
    <div id="loader">
        <div class="spinner">
            <div class="logo"></div>
        </div>
    </div>

    <div class="page-wrapper">
        <!-- Navbar -->
        <nav class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-4 shadow-md">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <a href="/" class="flex items-center space-x-2">
                        <img src="{{ asset('storage/img/logo.jpeg') }}" alt="Jobify Logo" class="w-12 h-12 rounded-full">
                        <span class="text-2xl font-bold text-white">Jobify</span>
                    </a>
                </div>                

                <div class="md:hidden flex items-center">
                    <button id="hamburger" class="text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="hidden md:flex space-x-6 text-lg">
                    <a href="{{ route('jobs.welcome') }}" class="hover:underline">Jobs</a>
                    <a href="{{ route('career.index') }}" class="hover:underline">Careers</a>
                    <a href="{{ route('salaries.index') }}" class="hover:underline">Salaries</a>
                    <a href="{{ route('companies') }}" class="hover:underline">Companies</a>
                    <a href="{{ route('contact.index') }}" class="hover:underline">Contact Us</a>
                </div>                

                <div class="hidden md:flex space-x-4 items-center">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="inline-block bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-2 px-6 rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105 hover:from-purple-500 hover:to-indigo-500 hover:shadow-xl">
                                🚀 Admin Dashboard
                            </a>
                        @else
                            <a href="{{ route('Users.dashboard') }}"
                                class="inline-block bg-gradient-to-r from-blue-600 to-teal-500 text-white font-semibold py-2 px-6 rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105 hover:from-blue-500 hover:to-teal-400 hover:shadow-xl">
                                👤 User Dashboard
                            </a>
                        @endif
                
                        <form action="{{ route('logout') }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit"
                                class="bg-gradient-to-r from-red-600 to-pink-500 text-white font-semibold py-2 px-6 rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105 hover:from-red-500 hover:to-pink-400 hover:shadow-xl">
                                🚪 Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-block bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold py-2 px-6 rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105 hover:from-blue-500 hover:to-indigo-400 hover:shadow-xl">
                            🔑 Login
                        </a>
                    @endauth
                </div>
                
                     
            </div>

            <div id="mobile-menu" class="md:hidden hidden bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-4">
                <a href="{{ route('jobs.welcome') }}" class="hover:underline">Jobs</a>
                    <a href="{{ route('career.index') }}" class="hover:underline">Careers</a>
                    <a href="{{ route('salaries.index') }}" class="hover:underline">Salaries</a>
                    <a href="{{ route('companies') }}" class="hover:underline">Companies</a>
                    <a href="{{ route('contact.index') }}" class="hover:underline">Contact Us</a>
            
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="inline-block bg-gray-800 text-white py-2 px-6 rounded-lg shadow-md hover:bg-gray-700 transition duration-300 mb-4">
                        Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline-block w-full">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white py-2 px-6 rounded-lg shadow-md hover:bg-red-500 transition duration-300 w-full mb-4">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="inline-block bg-gray-800 text-white py-2 px-6 rounded-lg shadow-md hover:bg-gray-700 transition duration-300 w-full mb-4">
                        Login
                    </a>
                @endauth
            </div>             
        </nav>

        <div class="content">
            {{$slot}}
        </div>

        <footer class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white mt-4">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <p class="text-lg">© 2025 Jobify. All rights reserved.</p>
                <div class="flex justify-center space-x-6 mt-4">
                    <a href="#" class="hover:underline">Privacy Policy</a>
                    <a href="#" class="hover:underline">Terms of Service</a>
                    <a href="/contact" class="hover:underline">Contact Us</a>
                </div>
            </div>
        </footer>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if(session('error'))
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "error",
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif
    });

        const hamburger = document.getElementById("hamburger");
        const mobileMenu = document.getElementById("mobile-menu");
    
        hamburger.addEventListener("click", () => {
            mobileMenu.classList.toggle("hidden");
        });
 
        document.addEventListener("click", (event) => {
            if (!hamburger.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add("hidden");
            }
        });
    
        document.addEventListener("DOMContentLoaded", () => {
            const loader = document.getElementById("loader");
            setTimeout(() => {
                loader.classList.add("hidden");
            }, 500);
        });
    </script>
    
</body>
</html>
