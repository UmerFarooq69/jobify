<x-admin>
    <!-- Toolbar -->
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <form method="GET" action="{{ route('admin.search') }}" class="flex items-center gap-2">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-xs"></i>
                </div>
                <input type="text" name="job_uuid" value="{{ request('job_uuid') }}" placeholder="Search by Job UUID"
                    class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                Search
            </button>
        </form>
        <a href="{{ route('jobs.create') }}" class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-plus text-xs text-gray-500"></i> Create New Job
        </a>
    </div>

    <!-- Jobs Grid -->
    @if(request('job_id') && $jobs->isEmpty())
        <div class="flex flex-col items-center justify-center p-16 bg-white border border-gray-200 rounded-xl">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-search text-gray-400 text-xl"></i>
            </div>
            <p class="text-base font-semibold text-gray-700">No jobs found</p>
            <p class="text-sm text-gray-400 mt-1">Try searching with a different UUID</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($jobs as $job)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
                <img src="{{ asset('storage/' . $job->image) }}" alt="{{ $job->job_title }}" class="w-full h-36 object-cover">
                <div class="p-4 flex-1 flex flex-col">
                    <h2 class="text-sm font-semibold text-gray-900 truncate mb-2">{{ $job->job_title }}</h2>
                    <div class="space-y-1 text-xs text-gray-500 flex-1">
                        <div class="flex items-center gap-2"><i class="fas fa-building w-3 text-gray-400"></i> {{ $job->company->name }}</div>
                        <div class="flex items-center gap-2"><i class="fas fa-briefcase w-3 text-gray-400"></i> {{ $job->job_type }}</div>
                        <div class="flex items-center gap-2"><i class="fas fa-money-bill w-3 text-gray-400"></i> {{ $job->job_salary }}</div>
                        <div class="flex items-center gap-2 font-mono"><i class="fas fa-fingerprint w-3 text-gray-400"></i> {{ substr($job->job_uuid, 0, 12) }}...</div>
                    </div>
                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                        <a href="{{ route('jobs.edit', $job->id) }}" class="inline-flex items-center gap-1 text-xs font-medium text-amber-600 hover:text-amber-700 transition-colors">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1 text-xs font-medium text-red-500 hover:text-red-600 transition-colors delete-btn">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</x-admin>
