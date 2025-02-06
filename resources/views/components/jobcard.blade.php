<div class="p-6 bg-white shadow-md rounded-lg flex flex-col text-left mx-4">
    <img src="{{ asset('storage/' . $job->image) }}" alt="Job Image" class="w-full md:w-2/3 lg:w-1/2 mx-auto rounded-md shadow-lg">
    <h1 class="text-xl font-semibold mt-6">
        Company: <span class="font-normal text-gray-500">{{ ($job->company)->name }}</span>
    </h1>
    <h3 class="text-lg mt-2">Job: <span class="font-normal text-gray-500">{{ $job->job_title }}</span></h3>
    <h3 class="text-lg">Job Type: <span class="font-normal text-gray-500">{{ $job->job_type }}</span></h3>
    <p class="text-lg">PKR: <span class="font-normal text-gray-500">{{ number_format($job->job_salary, 0) }}</span></p>
    <span class="text-blue-600 text-sm cursor-pointer items-center gap-1 justify-end block ml-auto"
    id="jobDescriptionTrigger">
            <i class="fas fa-eye"></i> Quick review
    </span>
        <div 
    id="jobDescriptionModal" 
    class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50"
        >
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-lg transform transition-transform duration-300">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Job Description</h2>
        <p class="text-gray-700">{{ $job->description }}</p>
        <button 
            class="mt-4 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            id="closeModal">
            Close
        </button>
    </div>
</div>

    <div class="text-center mt-2">
        <a href="{{ route('jobs.apply', $job->id) }}" class="bg-gradient-to-r from-green-400 to-green-600 text-white py-3 px-6 rounded-md shadow-lg hover:from-green-500 hover:to-green-700 transition-all duration-300 text-center flex items-center justify-center">
            Apply for this job
        </a>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modals = document.querySelectorAll('[id^="jobDescriptionModal"]');
        const modalTriggers = document.querySelectorAll('[id^="jobDescriptionTrigger"]');
        const closeButtons = document.querySelectorAll('[id^="closeModal"]');

        modalTriggers.forEach((trigger, index) => {
            trigger.addEventListener('click', function () {
                modals[index].classList.remove('hidden');
            });
        });

        closeButtons.forEach((button, index) => {
            button.addEventListener('click', function () {
                modals[index].classList.add('hidden');
            });
        });

        window.addEventListener('click', function (event) {
            modals.forEach((modal) => {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    });
</script>