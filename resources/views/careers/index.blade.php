<x-form>
    <div class="w-full bg-gradient-to-r from-teal-400 via-indigo-500 to-pink-600 py-6">
        <h3 class="text-3xl font-extrabold text-center text-white">
            Careers
        </h3>
    </div>   

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 px-6 mt-3">
        @foreach ($jobs as $job)
        <div class="w-full">
            <div class="bg-gradient-to-r from-teal-500 to-indigo-700 text-white py-6 px-8 rounded-2xl shadow-xl transition-all transform hover:scale-105 hover:shadow-2xl hover:bg-teal-600 w-full">
                <div class="text-left mb-4">
                    <i class="fas fa-briefcase text-4xl mb-3 transition-transform transform hover:scale-110"></i>
                    <h4 class="text-2xl font-bold text-teal-100">{{ $job->company->name }}</h4>
                    <h3 class="text-xl font-bold text-white">{{ $job->job_title }}</h3>
                    <p class="text-lg font-medium text-teal-200 mt-1">{{ $job->job_type }}</p>
                </div>

                <a href="{{ route('jobs.show', $job->id) }}" class="w-full">
                    <div class="bg-teal-800 text-white py-3 px-8 rounded-md shadow-md hover:bg-teal-900 transition-all duration-300 text-center mt-4 transform hover:scale-105">
                        <p class="text-lg font-semibold">Explore Job</p>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</x-form>
