<x-form>
    @php $jobId = request('job_id'); @endphp

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Report a Problem</h1>
                <p class="text-gray-500 text-sm mt-1">Help us keep the platform safe and trustworthy</p>
            </div>

            <form action="{{ route('report.problem') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-5">
                        <div>
                            <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1.5">Report For</label>
                            <div class="relative">
                                <select id="purpose" name="purpose" required
                                    class="appearance-none w-full pl-4 pr-10 py-2.5 border border-gray-300 rounded-lg bg-white text-sm text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors">
                                    <option value="report_problem" {{ !$jobId ? 'selected' : '' }}>Report a Problem</option>
                                    <option value="report_job" {{ $jobId ? 'selected' : '' }}>Report a Job</option>
                                    <option value="report_company">Report a Company</option>
                                </select>
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="report_type" class="block text-sm font-medium text-gray-700 mb-1.5">Issue Type</label>
                            <div class="relative">
                                <select id="report_type" name="report_type" required
                                    class="appearance-none w-full pl-4 pr-10 py-2.5 border border-gray-300 rounded-lg bg-white text-sm text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors">
                                    <option value="spam">Spam</option>
                                    <option value="fraud">Fraud</option>
                                    <option value="harassment">Harassment</option>
                                    <option value="misinformation">Misinformation</option>
                                    <option value="other">Other</option>
                                </select>
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div id="job-container" class="{{ $jobId ? '' : 'hidden' }}">
                            <label for="job_id" class="block text-sm font-medium text-gray-700 mb-1.5">Job ID</label>
                            <input type="number" id="job_id" name="job_id" value="{{ $jobId }}"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors">
                        </div>

                        <div id="company-container" class="hidden">
                            <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1.5">Company ID</label>
                            <input type="number" id="company_id" name="company_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors">
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Your Name</label>
                            <input type="text" id="name" name="name" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                                placeholder="Enter your full name">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                            <input type="email" id="email" name="email" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                                placeholder="Enter your email">
                        </div>
                        <div>
                            <label for="problem" class="block text-sm font-medium text-gray-700 mb-1.5">Describe the Issue</label>
                            <textarea id="problem" name="problem" rows="4" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors resize-none"
                                placeholder="Provide details about the issue..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Attach Screenshot <span class="text-gray-400 font-normal">(optional)</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 transition-colors cursor-pointer">
                            <input type="file" id="image" name="image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-500">Drag & drop or <span class="text-blue-600 font-medium">browse</span></p>
                        </div>
                        <div id="preview-container" class="hidden">
                            <div class="border border-gray-200 rounded-xl p-3 text-center">
                                <p class="text-xs text-gray-500 mb-2">Preview</p>
                                <img id="preview" class="max-h-32 mx-auto rounded-lg" />
                                <button type="button" id="remove-image" class="mt-2 text-xs text-red-500 hover:text-red-600 font-medium hidden">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold text-sm shadow-sm transition-colors">
                    Submit Report
                </button>
            </form>
        </div>
    </div>
</x-form>

<script>
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('preview-container').classList.remove('hidden');
                document.getElementById('remove-image').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('remove-image').addEventListener('click', function() {
        document.getElementById('image').value = "";
        document.getElementById('preview').src = "";
        document.getElementById('preview-container').classList.add('hidden');
        document.getElementById('remove-image').classList.add('hidden');
    });

    document.getElementById('purpose').addEventListener('change', function () {
        document.getElementById('job-container').classList.toggle('hidden', this.value !== 'report_job');
        document.getElementById('company-container').classList.toggle('hidden', this.value !== 'report_company');
    });
</script>
