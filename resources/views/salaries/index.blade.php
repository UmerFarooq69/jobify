<x-form>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Salary Insights</h1>
            <p class="text-gray-500 text-sm mt-1">Explore compensation data across roles and industries</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($jobs as $job)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-br from-slate-800 to-blue-900 p-5">
                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center mb-3">
                        <i class="fas fa-dollar-sign text-blue-300 text-lg"></i>
                    </div>
                    <p class="text-2xl font-bold text-white">PKR {{ number_format($job->job_salary, 0) }}</p>
                    <p class="text-blue-200 text-sm mt-1">{{ $job->job_title }}</p>
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
                    <a href="{{ route('jobs.show', $job->id) }}" class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                        View Job <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-form>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
