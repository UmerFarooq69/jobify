<x-form>
    <div class="min-h-screen flex items-center justify-center bg-gray-900">
        <form action="{{ route('jobs.store') }}" method="POST" enctype="multipart/form-data" class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
            @csrf
            <div class="pt-4">
                <label for="company_id" class="text-white font-semibold">Company</label>
                <select name="company_id" id="company_id" required class="w-full mt-2 p-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select a Company</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="pt-4">
                <label for="job_title" class="text-white font-semibold">Job Title</label>
                <input type="text" id="job_title" name="job_title" required class="w-full mt-2 p-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="pt-4">
                <label for="job_type" class="text-white font-semibold">Job Type</label>
                <select id="job_type" name="job_type" required class="w-full mt-2 p-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Job Type</option>
                    @foreach ($jobTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="pt-4">
                <label for="description" class="text-white font-semibold">Job Description</label>
                <input type="text" id="description" name="description" required class="w-full mt-2 p-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="pt-4">
                <label for="job_salary" class="text-white font-semibold">Job Salary</label>
                <input type="text" id="job_salary" name="job_salary" required class="w-full mt-2 p-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full mt-6 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Post Job
            </button>
        </form>
    </div>
</x-form>
