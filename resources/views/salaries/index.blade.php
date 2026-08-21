<x-form>
    <!-- Page Header -->
    <div class="bg-white border-b border-gray-100 py-10 px-6">
        <div class="max-w-7xl mx-auto">
            <span class="inline-block text-xs font-semibold text-blue-600 uppercase tracking-widest mb-2">Insights</span>
            <h1 class="text-3xl font-bold text-gray-900">Salary Insights</h1>
            <p class="text-gray-500 text-sm mt-2">Explore real compensation data across roles and industries to make informed career decisions.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($jobs as $job)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                <!-- Card Header -->
                <div class="bg-blue-50 border-b border-blue-100 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-white text-sm"></i>
                        </div>
                        <span class="text-xs font-semibold text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full">Salary</span>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-900">PKR {{ number_format($job->job_salary, 0) }}</p>
                    <p class="text-blue-700 text-sm font-medium mt-0.5">{{ $job->job_title }}</p>
                </div>

                <!-- Card Body -->
                <div class="p-5">
                    <div x-data="{
                        showFull: false,
                        shortDesc: '{{ addslashes(\Illuminate\Support\Str::limit($job->description, 80)) }}',
                        fullDesc: '{{ addslashes($job->description) }}'
                    }">
                        <p class="text-sm text-gray-600 leading-relaxed">
                            <span x-html="showFull ? fullDesc : shortDesc"></span>
                            @if(strlen($job->description) > 80)
                            <button @click="showFull = !showFull" class="text-blue-600 hover:text-blue-700 text-sm ml-1 font-medium">
                                <span x-show="!showFull">Read more</span>
                                <span x-show="showFull">Show less</span>
                            </button>
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('jobs.show', $job->id) }}" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors group-hover:gap-2.5">
                        View Job <i class="fas fa-arrow-right text-xs transition-all"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-form>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
