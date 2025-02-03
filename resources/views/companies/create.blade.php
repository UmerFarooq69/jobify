<x-form>
    <div class="h-screen flex items-center justify-center bg-gradient-to-r from-teal-500 to-indigo-600 px-6">
        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden max-w-4xl w-full p-8 space-y-6">
            
            <h2 class="text-3xl font-extrabold text-center text-gray-900">Post a New Company</h2>
            <p class="text-gray-500 text-center">Fill in the details to post a new company on the platform.</p>
            
            <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="text-gray-700 text-sm font-medium">Company Name</label>
                    <input type="text" id="name" name="name" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 placeholder-gray-400 border border-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 transition-all">
                </div>

                <div>
                    <label for="city" class="text-gray-700 text-sm font-medium">City</label>
                    <input type="text" id="city" name="city" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 placeholder-gray-400 border border-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 transition-all">
                </div>

                <div>
                    <label for="location" class="text-gray-700 text-sm font-medium">Location</label>
                    <input type="text" id="location" name="location" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 placeholder-gray-400 border border-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 transition-all">
                </div>

                <div>
                    <label for="image" class="text-gray-700 text-sm font-medium">Company Image</label>
                    <input type="file" id="image" name="image" accept="image/*" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 placeholder-gray-400 border border-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 transition-all">
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition duration-300 focus:outline-none focus:ring-4 focus:ring-blue-400 shadow-md text-lg font-semibold">
                    Post Company
                </button>
            </form>
        </div>
    </div>
</x-form>
