<x-form>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
        <!-- Back Link -->
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-6 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i> Back
        </a>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Apply for Position</h1>
                <div class="mt-2">
                    <span class="text-lg font-semibold text-blue-600">{{ $job->job_title }}</span>
                    <span class="text-gray-400 mx-2">·</span>
                    <span class="text-gray-600 text-sm">{{ $job->company->name }}</span>
                </div>
            </div>

            <form action="{{ route('jobs.submitApplication', $job->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div>
                    <label for="applicant_name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                    <input type="text" id="applicant_name" name="applicant_name" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                        placeholder="Enter your full name">
                </div>

                <div>
                    <label for="applicant_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" id="applicant_email" name="applicant_email" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                        placeholder="Enter your email address">
                </div>

                <div>
                    <label for="cv" class="block text-sm font-medium text-gray-700 mb-1.5">Attach CV / Resume</label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors cursor-pointer">
                        <input type="file" id="cv" name="cv" accept=".pdf,.docx" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">Drag & drop or <span class="text-blue-600 font-medium">browse file</span></p>
                        <p class="text-xs text-gray-400 mt-1">PDF or DOCX — Max 5MB</p>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg transition-colors font-semibold text-sm shadow-sm">
                    Submit Application
                </button>
            </form>
        </div>
    </div>
</x-form>
