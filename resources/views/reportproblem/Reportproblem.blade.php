<x-form>
    <div class="py-12 flex justify-center">
        <form action="{{ route('report.problem') }}" method="POST" enctype="multipart/form-data" class="max-w-lg w-full bg-white p-8 rounded-2xl shadow-lg space-y-6">
            @csrf
            <h2 class="text-2xl font-bold text-center text-gray-800">Report a Problem</h2>

            <!-- Name -->
            <div class="relative">
                <input type="text" id="name" name="name" required class="peer w-full border border-gray-300 rounded-lg px-4 pt-5 pb-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <label for="name" class="absolute left-4 top-2 text-gray-500 text-sm transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-blue-500">Your Name</label>
            </div>

            <!-- Email -->
            <div class="relative">
                <input type="email" id="email" name="email" required class="peer w-full border border-gray-300 rounded-lg px-4 pt-5 pb-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <label for="email" class="absolute left-4 top-2 text-gray-500 text-sm transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-blue-500">Your Email</label>
            </div>

            <!-- Problem Description -->
            <div class="relative">
                <textarea id="problem" name="problem" rows="4" required class="peer w-full border border-gray-300 rounded-lg px-4 pt-5 pb-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                <label for="problem" class="absolute left-4 top-2 text-gray-500 text-sm transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-blue-500">Describe the Problem</label>
            </div>

            <!-- Image Upload -->
            <div id="upload-container" class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition">
                <input type="file" id="image" name="image" accept="image/*" class="hidden">
                <label for="image" class="cursor-pointer text-gray-500 flex flex-col items-center">
                    <svg class="w-12 h-12 mb-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V12a4 4 0 014-4h2a4 4 0 014 4v4m-5 3v-3m-4 3h8"></path>
                    </svg>
                    <span class="text-sm">Drag & drop or <span class="text-blue-500 font-medium">browse</span></span>
                </label>
            </div>

            <!-- Image Preview -->
            <div id="preview-container" class="hidden mt-4">
                <p class="text-gray-500 text-sm">Image Preview:</p>
                <img id="preview" class="mt-2 max-w-xs mx-auto rounded-lg shadow-md" />
            </div>

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
                document.getElementById('upload-container').classList.add('hidden'); // Hide upload section
            };
            reader.readAsDataURL(file);
        }
    });
</script>
