<x-form>
    <div class="py-3 flex justify-center">
        <form action="{{ route('report.problem') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl w-full bg-white p-8 rounded-2xl shadow-lg space-y-6">
            @csrf
            <h2 class="text-3xl font-bold text-center text-gray-800">Report a Problem</h2>
            
            <!-- Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6"> 
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Purpose Dropdown -->
                    <div class="relative">
                        <label for="purpose" class="block text-gray-800 font-semibold mb-2">Purpose</label>
                        <div class="relative">
                            <select id="purpose" name="purpose" required
                            class="appearance-none w-full border border-gray-300 bg-white text-gray-700 px-4 py-3 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-300 hover:border-blue-400">
                            <option value="report_problem">📌 Report a Problem</option>
                            <option value="report_job">📢 Report a Job</option>
                            <option value="report_company">🏢 Report a Company</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500 transition-transform duration-200 transform rotate-0" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Report Type Dropdown -->
            <div class="relative">
                <label for="report_type" class="block text-gray-800 font-semibold mb-2">Report Type</label>
                <div class="relative">
                    <select id="report_type" name="report_type" required
                    class="appearance-none w-full border border-gray-300 bg-white text-gray-700 px-4 py-3 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-300 hover:border-blue-400">
                    <option value="spam">🚨 Spam</option>
                    <option value="fraud">⚠️ Fraud</option>
                    <option value="harassment">😡 Harassment</option>
                    <option value="misinformation">❌ Misinformation</option>
                    <option value="other">❓ Other</option>
                </select>
                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500 transition-transform duration-200 transform rotate-0" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>
    </div>
    
    
    <!-- Job ID (Shown Only for Job Reports) -->
    <div id="job-container" class="hidden space-y-4">
        <label for="job_id" class="block text-gray-700 font-medium mb-1">Job ID</label>
        <input type="number" id="job_id" name="job_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>
    
    <!-- Company ID (Shown Only for Company Reports) -->
    <div id="company-container" class="hidden space-y-4">
        <label for="company_id" class="block text-gray-700 font-medium mb-1">Company ID</label>
        <input type="number" id="company_id" name="company_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>
</div>

<!-- Right Column -->
<div class="space-y-6">
    <!-- Name -->
    <div class="relative">
        <label for="name" class="block text-gray-700 font-medium mb-1">Your Name</label>
        <input type="text" id="name" name="name" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>
    
    <!-- Email -->
    <div class="relative">
        <label for="email" class="block text-gray-700 font-medium mb-1">Your Email</label>
        <input type="email" id="email" name="email" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>
    
    <!-- Problem Description -->
    <div class="relative">
        <label for="problem" class="block text-gray-700 font-medium mb-1">Describe the Problem</label>
        <textarea id="problem" name="problem" rows="4" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
    </div>
</div>

</div> <!-- End of Grid -->

<!-- Image Upload & Preview -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- File Upload -->
    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition">
        <input type="file" id="image" name="image" accept="image/*" class="hidden">
        <label for="image" class="cursor-pointer text-gray-500 flex flex-col items-center">
            <svg class="w-12 h-12 mb-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V12a4 4 0 014-4h2a4 4 0 014 4v4m-5 3v-3m-4 3h8"></path>
            </svg>
            <span class="text-sm">Drag & drop or <span class="text-blue-500 font-medium">browse</span></span>
        </label>
    </div>
    
    <!-- Image Preview -->
    <div id="preview-container" class="hidden text-center">
        <p class="text-gray-500 text-sm">Image Preview:</p>
        <img id="preview" class="mt-2 max-w-xs mx-auto rounded-lg shadow-md" />
        <button type="button" id="remove-image" class="mt-2 text-sm text-red-500 hover:text-red-700 hidden">Remove</button>
    </div>
</div>

<!-- Submit Button -->
<div class="text-center">
    <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-pink-500 text-white py-3 rounded-lg font-semibold shadow-md hover:shadow-lg hover:from-red-600 hover:to-pink-600 transition-all">
        Submit Report
    </button>
</div>
</form>
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
        const jobContainer = document.getElementById('job-container');
        const companyContainer = document.getElementById('company-container');
        
        jobContainer.classList.toggle('hidden', this.value !== 'report_job');
        companyContainer.classList.toggle('hidden', this.value !== 'report_company');
    });
</script>
