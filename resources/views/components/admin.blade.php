<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="{{ asset('storage/img/logo.jpeg') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body class="bg-gray-100">

<div class="flex h-screen">
    <div class="w-68 bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6">
        <div class="text-3xl font-semibold mb-10 text-center">
            Admin Panel
        </div>
        <ul>
            <li class="mb-6">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center text-lg p-3 rounded-md transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                    <i class="fas fa-tachometer-alt mr-4"></i> Dashboard
                </a>
            </li>
            <li class="mb-6">
                <a href="{{ route('admin.jobs') }}" 
                   class="flex items-center text-lg p-3 rounded-md transition-all {{ request()->routeIs('admin.jobs') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                    <i class="fas fa-briefcase mr-4"></i> Manage Jobs
                </a>
            </li>
            <li class="mb-6">
                <a href="{{ route('admin.companies') }}" 
                   class="flex items-center text-lg p-3 rounded-md transition-all {{ request()->routeIs('admin.companies') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                    <i class="fas fa-building mr-4"></i> Manage Companies
                </a>
            </li>
            <li class="mb-6">
                <a href="{{ route('users.index') }}" 
                   class="flex items-center text-lg p-3 rounded-md transition-all {{ request()->routeIs('users.index') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                    <i class="fas fa-users mr-4"></i> Manage Users
                </a>
            </li>
            <li class="mb-6">
                <a href="{{ route('applications.index') }}" 
                   class="flex items-center text-lg p-3 rounded-md transition-all {{ request()->routeIs('applications.index') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                    <i class="fas fa-file-alt mr-4"></i> Manage Applications
                </a>
            </li>
            <li class="mb-6">
                <a href="{{ route('contact.index') }}" 
                   class="flex items-center text-lg p-3 rounded-md transition-all {{ request()->routeIs('contact.index') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                    <i class="fas fa-phone-alt mr-4"></i> Who Contact Us
                </a>
            </li>
            <li class="mb-6">
                <a href="{{ route('problems.index') }}" 
                   class="flex items-center text-lg p-3 rounded-md transition-all {{ request()->routeIs('problems.index') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                    <i class="fas fa-triangle-exclamation mr-4"></i> Reported Problems
                </a>
            </li>
        </ul>        
    </div>
    <div class="flex-1 p-8 overflow-auto">

        <div class="flex justify-between items-center mb-6">
            <!-- Dashboard Title -->
            <div class="text-4xl font-bold text-gray-900 tracking-tight">
                🚀 Admin Dashboard
            </div>
        
            <!-- Navigation Buttons -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- Main Page Button -->
                    <a href="{{ route('jobs.welcome') }}"
                        class="inline-block bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-semibold py-2 px-6 rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105 hover:from-blue-500 hover:to-cyan-400 hover:shadow-xl">
                        🏠 Main Page
                    </a>
        
                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit"
                            class="bg-gradient-to-r from-red-600 to-pink-500 text-white font-semibold py-2 px-6 rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105 hover:from-red-500 hover:to-pink-400 hover:shadow-xl">
                            🚪 Logout
                        </button>
                    </form>
                @else
                    <!-- Login Button -->
                    <a href="{{ route('login') }}"
                        class="inline-block bg-gradient-to-r from-green-600 to-lime-500 text-white font-semibold py-2 px-6 rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105 hover:from-green-500 hover:to-lime-400 hover:shadow-xl">
                        🔑 Login
                    </a>
                @endauth
            </div>
        </div>
        

        {{ $slot }}
    </div>
</body>
</html>
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

        setTimeout(() => {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    let form = this.closest('form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        }, 500);
    });
</script>
