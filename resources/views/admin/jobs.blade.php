<x-admin>
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-200 text-green-700 rounded" style="min-height: 50px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-wrap gap-6 mb-6">
            <a href="{{ route('jobs.create') }}" class="inline-block bg-gray-800 text-white py-2 px-6 rounded-lg shadow-md hover:bg-gray-700 hover:shadow-lg transition duration-300">Create New Job</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-6">
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
                        <p class="text-gray-700 mb-4"><strong>Salary:</strong> {{ $job->job_salary }}</p>
                    </div>
                    <div class="flex justify-between items-center mt-4">
                        <a href="" class="text-yellow-500 hover:underline">Edit</a>
                        <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</x-admin>
