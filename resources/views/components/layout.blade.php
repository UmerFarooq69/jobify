<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Jobify</title>
    <link href="{{ asset('css/output.css') }}" rel="stylesheet">
</head>
<body class="bg-black text-white">
    <div class="px-10">
        <nav class="flex justify-between py-4 border-b border-white/10">
            <div>
                <a href="#">

                </a>
            </div>
            <div class="space-x-6 text-2xl">
                <a href="{{'/'}}">Jobs</a>
                <a href="#">Careers</a>
                <a href="#">Salaries</a>
                <a href="{{'/company'}}">Companies</a>
            </div>  
            <div>
                <a href="{{ route('jobs.create') }}" class="inline-block bg-gray-800 text-white py-2 px-6 rounded-lg shadow-md hover:bg-gray-700 hover:shadow-lg transition duration-300">
                    Post a Job
                </a>     
                <a href="{{ route('admin.dashboard') }}" class="inline-block bg-gray-800 text-white py-2 px-6 rounded-lg shadow-md hover:bg-gray-700 hover:shadow-lg transition duration-300">
                    Dashboard
                </a>                         
            </div>             
        </nav>
        <main class="mt-1 max-w-[986px]">
            {{ $slot }}
        </main>        
    </div>
</body>
</html>
