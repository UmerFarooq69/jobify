<x-form>
    <h3 class="text-3xl mb-6 font-extrabold text-center text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-indigo-500 to-pink-600">
        Salaries Range
    </h3>
    <div class="w-full px-6">
        @foreach ($jobs as $job)
        <div class="mb-6 w-full">
            <div class="bg-gradient-to-r from-teal-500 to-indigo-700 text-white py-5 px-7 rounded-xl shadow-xl transition-transform transform hover:scale-103 hover:shadow-xl w-full">
                <div class="text-left mb-4">
                    <i class="fas fa-users text-3xl mb-2"></i>
                    <h3 class="text-lg font-bold">PKR: {{ number_format($job->job_salary, 0) }}</h3>
                    <h4 class="text-sm font-medium">Job Description: {{ $job->description }}</h4>
                    <h4 class="text-sm font-medium">Job Title: {{ $job->job_title }}</h4>
                </div>
                <a href="{{ route('jobs.show', $job->id) }}" class="w-full">
                    <div class="bg-teal-600 text-white py-2 px-7 rounded-md shadow-md hover:bg-teal-800 transition-all duration-300 text-center">
                        <p class="text-md font-semibold">Explore Job</p>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</x-form>
