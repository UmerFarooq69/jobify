<x-form>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-teal-500 to-indigo-600 px-6">
        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden max-w-4xl w-full p-8 space-y-6">
            
            <h2 class="text-3xl font-extrabold text-center text-gray-900">Post a New Job</h2>
            <p class="text-gray-500 text-center">Fill in the details to post a new job on the platform.</p>
            
            <form action="{{ route('jobs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label for="company_id" class="text-gray-700 text-sm font-medium">Company</label>
                    <select name="company_id" id="company_id" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 placeholder-gray-400 border border-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 transition-all">
                        <option value="">Select a Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="job_title" class="text-gray-700 text-sm font-medium">Job Title</label>
                    <input type="text" id="job_title" name="job_title" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 placeholder-gray-400 border border-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 transition-all">
                </div>

                <div>
                    <label for="job_type" class="text-gray-700 text-sm font-medium">Job Type</label>
                    <select id="job_type" name="job_type" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 placeholder-gray-400 border border-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 transition-all">
                        <option value="">Select Job Type</option>
                        @foreach ($jobTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="description" class="text-gray-700 text-sm font-medium">Job Description</label>
                    <input type="text" id="description" name="description" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 placeholder-gray-400 border border-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 transition-all">
                </div>

                <div>
                    <label for="job_salary" class="text-gray-700 text-sm font-medium">Job Salary</label>
                    <input type="text" id="job_salary" name="job_salary" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 placeholder-gray-400 border border-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 transition-all">
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition duration-300 focus:outline-none focus:ring-4 focus:ring-blue-400 shadow-md text-lg font-semibold">
                    Post Job
                </button>
            </form>
        </div>
    </div>
</x-form>
