<x-usersdashboard>
    <div class="max-w-6xl">
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('jobs.create') }}" class="bg-blue-600 text-white py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
                + Create New Job
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($jobs as $job)
                <div class="bg-white p-4 border border-gray-200 rounded-lg shadow-md flex flex-col h-full transition hover:shadow-lg">
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $job->image) }}" alt="{{ $job->job_title }}" class="w-full h-44 object-cover rounded-md shadow-sm">
                    </div>

                    <div class="flex-grow">
                        <h2 class="text-gray-800 font-semibold truncate mb-1">
                            <strong>Job Title:</strong> {{ $job->job_title }}
                        </h2>
                        <p class="text-gray-700 mb-1"><strong>Company:</strong> {{ $job->company->name }}</p>
                        <p class="text-gray-700 mb-1"><strong>Job Type:</strong> {{ $job->job_type }}</p>
                        <p class="text-gray-700"><strong>Salary:</strong> PKR {{ number_format($job->job_salary) }}</p>
                    </div>
                    <div class="flex justify-between items-center mt-4 border-t pt-3">
                        <a href="{{ route('jobs.edit', $job->id) }}" class="text-yellow-500 hover:text-yellow-600">Edit</a>
                        <form action="{{ route('Users.jobs.destroy', $job->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-600">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-600">No jobs available for this company.</p>
            @endforelse
        </div>
    </div>
</x-usersdashboard>
