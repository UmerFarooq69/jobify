<div class="p-6 bg-white shadow-md rounded-lg flex flex-col text-left mx-4">
    <img src="{{ asset('storage/' . $job->image) }}" alt="Job Image" class="w-[350px] h-[170px] object-cover mx-auto rounded-md shadow-lg">

    <h3 class="text-xl mt-2"><span class="text-blue-500 font-bold">{{ $job->job_title }}</span></h3>
    <p class="text-xl font-bold">Rs: <span class="font-bold">{{ number_format($job->job_salary, 0) }}</span></p>
    <h3 class="text-lg"><i class="mdi mdi-briefcase-variant-outline text-2xl mr-2"></i><span class="font-normal text-gray-500">{{ $job->job_type }}</span></h3>   
    <h3 class="text-lg"><i class="mdi mdi-map-marker-outline text-2xl mr-2"></i><span class="font-normal text-gray-500">{{ $job->company->location }}</span></h3>
        <p class="text-lg">Applicants: 
        <span class="font-normal {{ $job->applications->count() < 5 ? 'text-red-500' : 'text-green-500' }}">
            {{ $job->applications->count() }}
        </span>
    </p>    
    <div class="flex justify-end">
        <button class="bg-blue-600 text-white text-sm font-bold cursor-pointer flex items-center gap-2 px-4 py-2 rounded-full hover:bg-blue-700 transition duration-300"
                data-modal="jobDescriptionModal-{{ $job->id }}">
            <i class="fas fa-eye"></i> Quick review
        </button>
    </div>    
    <div 
    id="jobDescriptionModal-{{ $job->id }}" 
    class="fixed inset-0 bg-black bg-opacity-60 hidden flex justify-center items-center z-50 transition-opacity duration-300"
>
    <div class="bg-gradient-to-br from-white to-gray-100 p-10 rounded-2xl shadow-2xl w-full max-w-4xl h-auto max-h-[90vh] overflow-y-auto transform transition-transform duration-300 scale-95">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-900">📌 Job Description</h2>
            <button 
                class="text-gray-600 hover:text-red-500 transition-all duration-300 text-3xl font-bold"
                data-close="jobDescriptionModal-{{ $job->id }}">
                &times;
            </button>
        </div>
        <p class="text-gray-800 text-lg leading-relaxed border-l-4 border-blue-500 pl-6 italic">
            {{ $job->description }}
        </p>
        <div class="flex justify-end mt-8">
            <button 
                class="bg-gradient-to-r from-blue-500 to-blue-700 text-white py-3 px-8 rounded-full shadow-lg hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-300"
                data-close="jobDescriptionModal-{{ $job->id }}">Close
            </button>
            </div>
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