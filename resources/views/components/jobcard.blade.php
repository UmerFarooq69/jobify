<div class="p-6 bg-white shadow-md rounded-lg flex flex-col text-left mx-4 hover:bg-gray-200 transition-all duration-100">
    <div class="flex items-center mb-2">
        <img src="{{ asset('storage/' . $job->image) }}" alt="Job Image" class="w-[70px] h-[70px] object-contain rounded-md shadow-lg mr-4">
    </div>
    <h1 class="font-bold text-lg">Job ID: <span class="text-blue-800">{{substr($job->job_uuid, 0, 8)}}</span></h1>
    <h3 class="text-xl mt-1"><span class="text-blue-500 font-bold">{{ $job->job_title }}</span></h3>

    <div class="flex items-center justify-between">
        <div class="flex space-x-2">
            <h3 class="text-sm bg-gray-100 px-2 py-1 rounded flex items-center">
                <i class="mdi mdi-map-marker-outline text-lg mr-1"></i>
                <span class="font-normal text-gray-500">{{ $job->company->location }}</span>
            </h3>
            <h3 class="text-sm bg-gray-100 px-2 py-1 rounded flex items-center">
                <i class="mdi mdi-briefcase-variant-outline text-lg mr-1"></i>
                <span class="font-normal text-gray-500">{{ $job->job_type }}</span>
            </h3>
        </div>
        <div class="flex space-x-2">
            <button class="w-10 h-10 bg-blue-600 mt-2 text-white text-lg cursor-pointer flex justify-center items-center rounded-full hover:bg-blue-700 transition duration-300" data-modal="jobDescriptionModal-{{ $job->id }}">
                <i class="fas fa-eye"></i>
            </button>
            <a href="{{ route('jobs.apply', $job->id) }}" class="bg-gradient-to-r from-green-400 to-green-600 text-white py-4 px-4 rounded-md shadow-lg hover:from-green-500 hover:to-green-700 transition-all duration-300 text-lg">
                Apply for this job
            </a>
        </div>
    </div>

    <h3 class="text-gray-500 text-lg mt-1">{{$job->description}}</h3>
    <p class="text-lg mt-1">Applicants: 
        <span class="font-normal {{ $job->applications->count() < 5 ? 'text-red-500' : 'text-green-500' }}">
            {{ $job->applications->count() }}
        </span>
    </p> 
</div>

<div id="jobDescriptionModal-{{ $job->id }}" class="fixed inset-0 bg-black bg-opacity-60 hidden flex justify-center items-center z-50 transition-opacity duration-300">
    <div class="bg-gradient-to-br from-white to-gray-100 p-10 rounded-2xl shadow-2xl w-full max-w-4xl h-auto max-h-[90vh] overflow-y-auto transform transition-transform duration-300 scale-95">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-900">📌 Job Description</h2>
            <button class="text-gray-600 hover:text-red-500 transition-all duration-300 text-3xl font-bold" data-close="jobDescriptionModal-{{ $job->id }}">&times;</button>
        </div>
        <p class="text-gray-800 text-lg leading-relaxed border-l-4 border-blue-500 pl-6 italic">
            {{ $job->description }}
        </p>
        <div class="flex justify-end mt-8">
            <button class="bg-gradient-to-r from-blue-500 to-blue-700 text-white py-3 px-8 rounded-full shadow-lg hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-300" data-close="jobDescriptionModal-{{ $job->id }}">Close</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('[data-modal]').forEach(trigger => {
            trigger.addEventListener('click', function () {
                const modalId = this.getAttribute('data-modal');
                document.getElementById(modalId).classList.remove('hidden');
            });
        });

        document.querySelectorAll('[data-close]').forEach(button => {
            button.addEventListener('click', function () {
                const modalId = this.getAttribute('data-close');
                document.getElementById(modalId).classList.add('hidden');
            });
        });

        window.addEventListener('click', function (event) {
            document.querySelectorAll('.fixed.inset-0').forEach(modal => {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    });
</script>
