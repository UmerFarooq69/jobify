<x-form>
    <div class="min-h-full flex flex-col items-center justify-center bg-gradient-to-r from-teal-500 to-blue-500 p-8">
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-4xl transform hover:scale-105 transition-transform duration-300">

            <div class="flex justify-end">
                <a href="{{ url()->previous() }}" class="bg-blue-500 text-white hover:bg-blue-600 hover:text-white font-semibold py-3 px-8 mt-2 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Go Back
                </a>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mt-4">Apply for {{ $job->job_title }}</h2>
            <p class="text-gray-700 mb-6">Company: <span class="font-semibold text-gray-800">{{ $job->company->name }}</span></p>

            <form action="{{ route('jobs.submitApplication', $job->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label for="applicant_name" class="text-gray-700 font-semibold">Your Name</label>
                    <input 
                        type="text" 
                        id="applicant_name" 
                        name="applicant_name" 
                        required 
                        class="w-full mt-2 p-4 rounded-lg bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter your name"
                    >
                </div>

                <div>
                    <label for="applicant_email" class="text-gray-700 font-semibold">Your Email</label>
                    <input 
                        type="email" 
                        id="applicant_email" 
                        name="applicant_email" 
                        required 
                        class="w-full mt-2 p-4 rounded-lg bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter your email"
                    >
                </div>

                <div>
                    <label for="cv" class="text-gray-700 font-semibold">Attach Your CV (PDF, DOCX)</label>
                    <input 
                        type="file" 
                        id="cv" 
                        name="cv" 
                        accept=".pdf,.docx" 
                        required 
                        class="w-full mt-2 p-4 rounded-lg bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <p class="text-sm text-gray-500 mt-2">Max file size: 5MB. Acceptable formats: PDF, DOCX.</p>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 text-white py-4 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Submit Application
                </button>
            </form>
        </div>
    </div>
</x-form>
