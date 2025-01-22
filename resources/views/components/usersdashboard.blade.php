<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Dashboard</title>
    <link rel="icon" href="{{ asset('storage/img/logo.jpeg') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body class="bg-gray-100">

<div class="flex h-screen">
    <div class="w-64 bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6">
        <div class="text-3xl font-semibold mb-10 text-center">
            User Panel
        </div>
        <ul>
            <li class="mb-6">
                <a href="{{route('Users.dashboard')}}" class="flex items-center text-lg hover:bg-blue-700 p-3 rounded-md transition-all">
                    <i class="fas fa-tacjobs.welcometer-alt mr-4"></i> Dashboard
                </a>
            </li>
            <li class="mb-6">
                <a href="{{route('Users.jobs')}}" class="flex items-center text-lg hover:bg-blue-700 p-3 rounded-md transition-all">
                    <i class="fas fa-briefcase mr-4"></i> Manage Jobs
                </a>
            </li>
            <li class="mb-6">
                <a href="{{ route('Users.company') }}" class="flex items-center text-lg hover:bg-blue-700 p-3 rounded-md transition-all">
                    <i class="fas fa-building mr-4"></i> Manage Companies
                </a>
            </li>
            <li class="mb-6">
                <a href="{{route('Users.application')}}" class="flex items-center text-lg hover:bg-blue-700 p-3 rounded-md transition-all">
                    <i class="fas fa-users mr-4"></i> Manage Applications
                </a>
            </li>
        </ul>
    </div>
    <div class="flex-1 p-8 overflow-auto">

        <div class="flex justify-between items-center mb-6">
            <div class="text-3xl font-semibold text-gray-900">
                User Dashboard
            </div>
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('jobs.welcome') }}" class="inline-block bg-gray-800 text-white py-2 px-6 rounded-lg shadow-md hover:bg-gray-700 transition duration-300">
                        Main Page
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white py-2 px-6 rounded-lg shadow-md hover:bg-red-500 transition duration-300">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="inline-block bg-gray-800 text-white py-2 px-6 rounded-lg shadow-md hover:bg-gray-700 transition duration-300">
                        Login
                    </a>
                @endauth
            </div>
        </div>

        {{ $slot }}
    </div>
</body>
</html>
