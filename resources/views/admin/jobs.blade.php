<x-admin>
    <div class="flex items-center gap-8 mb-6">
        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.search') }}" class="flex items-center gap-2">
            <input type="text" name="job_uuid" value="{{ request('job_uuid') }}" placeholder="Enter Job UUID" 
                class="border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                Search
            </button>
        </form>        
        <!-- Create New Job Button -->
        <a href="{{ route('jobs.create') }}" 
        class="bg-blue-600 text-white py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">           + Create New Job
        </a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @if(request('job_id') && $jobs->isEmpty())
        <div class="flex flex-col items-center justify-center p-10 bg-gray-50 border border-gray-200 rounded-lg">
            <svg class="w-16 h-16 text-gray-400 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M4 17h16M4 13h16M7 9h10M9 5h6M4 21h16a1 1 0 001-1V4a1 1 0 00-1-1H4a1 1 0 00-1 1v16a1 1 0 001 1z" />
            </svg>
            <p class="text-lg font-semibold text-gray-700">No Jobs with this ID</p>
        </div>        
         @else
            @foreach($jobs as $job)
                <div class="bg-white p-4 border border-gray-200 rounded-lg shadow-md flex flex-col justify-between h-full">
                    <div class="mb-4 flex-grow-0">
                        <img src="{{ asset('storage/' . $job->image) }}" alt="{{ $job->job_title }}" class="w-full h-40 object-cover rounded">
                    </div>
                    <div class="flex-grow">
                        <h2 class="text-gray-800 font-semibold truncate mb-2">
                            <strong>Job Title:</strong> {{ $job->job_title }}
                        </h2>
                        <p class="text-gray-700 mb-1"><strong>Company:</strong> {{ $job->company->name }}</p>
                        <p class="text-gray-700 mb-1"><strong>Job Type:</strong> {{ $job->job_type }}</p>
                        <p class="text-gray-700 mb-1"><strong>Salary:</strong> {{ $job->job_salary }}</p>
                        <p class="text-gray-700"><strong>Job ID:</strong> {{ $job->job_uuid }}</p>
                    </div>
                    <div class="flex justify-between items-center mt-4">
                        <a href="{{ route('jobs.edit', $job->id) }}" class="text-yellow-500 hover:underline">Edit</a>
                        <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline delete-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</x-admin>
