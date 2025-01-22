<div class="p-6 bg-white shadow-md rounded-lg flex flex-col text-left mx-4">
    <img src="{{ asset('storage/' . $job->image) }}" alt="Job Image" class="w-full md:w-2/3 lg:w-1/2 mx-auto rounded-md shadow-lg">
    <h1 class="text-xl font-semibold mt-6">
        Company: <span class="font-normal text-gray-500">{{ ($job->company)->name }}</span>
    </h1>
    <h3 class="text-lg mt-2">Job: <span class="font-normal text-gray-500">{{ $job->job_title }}</span></h3>
    <h3 class="text-lg">Job Type: <span class="font-normal text-gray-500">{{ $job->job_type }}</span></h3>
    <p class="text-lg">PKR: <span class="font-normal text-gray-500">{{ number_format($job->job_salary, 0) }}</span></p>
    <div class="text-center mt-6">
        <a href="{{ route('jobs.apply', $job->id) }}" class="bg-gradient-to-r from-green-400 to-green-600 text-white py-3 px-6 rounded-md shadow-lg hover:from-green-500 hover:to-green-700 transition-all duration-300 text-center flex items-center justify-center">
            Apply for this job
        </a>
    </div>
</div>

