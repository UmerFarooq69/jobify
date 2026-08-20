<x-form>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if ($jobs->isEmpty())
            <div class="flex flex-col items-center justify-center py-20">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-briefcase text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No job openings right now</h3>
                <p class="text-sm text-gray-400">This company hasn't posted any jobs yet. Check back later!</p>
                <a href="{{ route('companies') }}" class="mt-6 inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors">
                    <i class="fas fa-arrow-left text-xs"></i> Back to Companies
                </a>
            </div>
        @else
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Jobs at this Company</h2>
                <p class="text-gray-500 text-sm mt-1">{{ $jobs->count() }} position{{ $jobs->count() !== 1 ? 's' : '' }} available</p>
            </div>
            <div class="space-y-4">
                @foreach ($jobs as $job)
                    <x-jobcard :job="$job" />
                @endforeach
            </div>
        @endif
    </div>
</x-form>
