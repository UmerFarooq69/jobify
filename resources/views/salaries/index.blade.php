<x-form>
    <h3 class="text-4xl mb-8 font-extrabold text-center text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-indigo-500 to-pink-600">
        Salary Ranges for Jobs
    </h3>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 px-6">
        @foreach ($jobs as $job)
        <div class="w-full">
            <div class="bg-gradient-to-r from-teal-500 to-indigo-700 text-white py-6 px-8 rounded-2xl shadow-lg transition-transform transform hover:scale-105 hover:shadow-2xl hover:bg-teal-600 w-full">
                <div class="text-left mb-4">
                    <i class="fas fa-money-bill-wave text-4xl mb-3"></i>
                    <h3 class="text-xl font-bold mb-2">PKR: {{ number_format($job->job_salary, 0) }}</h3>
                    <h4 class="text-sm font-medium text-teal-200">Job Title: {{ $job->job_title }}</h4>
                
                    <div x-data="{ 
                        showFull: false,
                        shortDesc: '{{ addslashes(\Illuminate\Support\Str::limit($job->description, 20)) }}',
                        fullDesc: '{{ addslashes($job->description) }}'
                        }">
                        <p class="text-sm font-medium text-gray-300 mt-1">
                            <span x-html="showFull ? fullDesc : shortDesc"></span>
                            
                            @if(strlen($job->description) > 20)
                            <button @click="showFull = !showFull" class="text-teal-200 underline text-sm ml-2">
                                <span x-show="!showFull">Read More</span>
                                <span x-show="showFull">Show Less</span>
                            </button>
                            @endif
                        </p>
                    </div>
                </div>                

                <a href="{{ route('jobs.show', $job->id) }}" class="w-full">
                    <div class="bg-teal-700 text-white py-3 px-8 rounded-md shadow-md hover:bg-teal-800 transition-all duration-300 text-center mt-4">
                        <p class="text-md font-semibold">Explore Job</p>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</x-form>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>