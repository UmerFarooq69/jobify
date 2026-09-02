<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Jobify</title>
    <link rel="icon" href="{{ asset('assets/images/logo/logo.jpg') }}" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 antialiased">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-60 flex-shrink-0 bg-gray-900 flex flex-col">
            <div class="h-16 flex items-center px-5 border-b border-gray-700 gap-3">
                <img src="{{ asset('assets/images/logo/logo.jpg') }}" alt="Jobify" class="w-8 h-8 rounded-full object-cover ring-2 ring-gray-600">
                <div>
                    <div class="text-white font-semibold text-sm leading-tight">Jobify</div>
                    <div class="text-gray-400 text-xs">My Dashboard</div>
                </div>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                <a href="{{ route('Users.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('Users.dashboard') ? 'bg-blue-700 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="mdi mdi-view-dashboard w-4 text-center text-base leading-none"></i> Dashboard
                </a>
                <a href="{{ route('User.job.jobs') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('User.job.jobs') ? 'bg-blue-700 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="mdi mdi-briefcase w-4 text-center text-base leading-none"></i> My Jobs
                </a>
                <a href="{{ route('User.company.companies') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('User.company.companies') ? 'bg-blue-700 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="mdi mdi-office-building w-4 text-center text-base leading-none"></i> My Companies
                </a>
                <a href="{{ route('User.applications.application') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('User.applications.application') ? 'bg-blue-700 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="mdi mdi-file-document w-4 text-center text-base leading-none"></i> Applications
                </a>
            </nav>
            <div class="p-3 border-t border-gray-700 space-y-0.5">
                <a href="{{ route('jobs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white transition-colors">
                    <i class="mdi mdi-open-in-new w-4 text-center text-base leading-none"></i> View Site
                </a>
                @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-red-400 transition-colors">
                        <i class="mdi mdi-logout w-4 text-center text-base leading-none"></i> Sign Out
                    </button>
                </form>
                @endauth
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="h-16 bg-white border-b border-gray-200 flex items-center px-6 flex-shrink-0 shadow-sm">
                <h1 class="text-base font-semibold text-gray-900">My Dashboard</h1>
            </header>
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        @if(session('success'))
            Swal.fire({ toast: true, position: "top-end", icon: "success", title: "{{ session('success') }}", showConfirmButton: false, timer: 3000, timerProgressBar: true });
        @endif
        @if(session('error'))
            Swal.fire({ toast: true, position: "top-end", icon: "error", title: "{{ session('error') }}", showConfirmButton: false, timer: 3000, timerProgressBar: true });
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
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => { if (result.isConfirmed) form.submit(); });
                });
            });
        }, 100);
    });
    </script>
</body>
</html>
