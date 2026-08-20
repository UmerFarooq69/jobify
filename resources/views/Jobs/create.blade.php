<x-form>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Post a New Job</h1>
                <p class="text-gray-500 text-sm mt-1">Fill in the details to publish your job listing</p>
            </div>

            <form action="{{ route('jobs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div>
                    <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1.5">Company</label>
                    <select name="company_id" id="company_id" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-sm text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors">
                        <option value="">Select a Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="job_title" class="block text-sm font-medium text-gray-700 mb-1.5">Job Title</label>
                    <input type="text" id="job_title" name="job_title" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                        placeholder="e.g. Senior Software Engineer">
                </div>

                <div>
                    <label for="job_type" class="block text-sm font-medium text-gray-700 mb-1.5">Job Type</label>
                    <select id="job_type" name="job_type" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-sm text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors">
                        <option value="">Select Job Type</option>
                        @foreach ($jobTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Job Description</label>
                    <textarea id="description" name="description" required rows="4"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors resize-none"
                        placeholder="Describe the role, responsibilities, and requirements..."></textarea>
                </div>

                <div>
                    <label for="job_salary" class="block text-sm font-medium text-gray-700 mb-1.5">Salary (PKR)</label>
                    <input type="text" id="job_salary" name="job_salary" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                        placeholder="e.g. 80000">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Job Image <span class="text-gray-400 font-normal">(optional)</span></label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-5 text-center hover:border-blue-400 transition-colors cursor-pointer">
                        <input type="file" name="image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <i class="fas fa-image text-gray-400 text-xl mb-2"></i>
                        <p class="text-sm text-gray-500">Upload a job image</p>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg transition-colors font-semibold text-sm shadow-sm">
                    Post Job
                </button>
            </form>
        </div>
    </div>
</x-form>
