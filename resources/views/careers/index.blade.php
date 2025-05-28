<x-form>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 px-6 mt-3">
        @foreach ($jobs as $job)
        <div class="w-full">
            <div class="bg-gradient-to-r from-blue-900 to-blue-500 text-white py-6 px-8 rounded-2xl shadow-xl transition-all transform hover:scale-105 hover:shadow-2xl hover:bg-teal-600 w-full">
                <div class="text-left mb-4">
                    <i class="fas fa-briefcase text-4xl mb-3 transition-transform transform hover:scale-110"></i>
                    <h4 class="text-2xl font-bold text-gradient-to-r from-blue-900 to-blue-500">{{ $job->company->name }}</h4>
                    <h3 class="text-xl font-bold text-white">{{ $job->job_title }}</h3>
                    <p class="text-lg font-medium text-gradient-to-r from-blue-900 to-blue-500 mt-1">{{ $job->job_type }}</p>
                </div>
                
                <a href="{{ route('jobs.show', $job->id) }}" class="w-full">
                    <div class="bg-gradient-to-r from-blue-900 to-blue-100 text-white py-3 px-8 rounded-md shadow-md transition-all duration-300 text-center mt-4 transform hover:scale-105">
                        <p class="text-lg font-semibold">Explore Job</p>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</x-form>
