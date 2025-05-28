<x-form>
    <div class="h-full min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-900 to-blue-100">
        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden max-w-2xl w-full p-8 space-y-6 mt-4">
            
            <h2 class="text-3xl font-extrabold text-center text-gray-900">Post a New Company</h2>
            <p class="text-gray-500 text-center">Fill in the details to post a new company on the platform.</p>
            
            <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="text-gray-700 text-sm font-medium">Company Name</label>
                    <input type="text" id="name" name="name" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 shadow-sm">
                </div>

                <div>
                    <label for="city" class="text-gray-700 text-sm font-medium">City</label>
                    <input type="text" id="city" name="city" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 shadow-sm">
                </div>

                <div>
                    <label for="location" class="text-gray-700 text-sm font-medium">Location</label>
                    <input type="text" id="location" name="location" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 shadow-sm">
                </div>

                <div>
                    <label for="description" class="text-gray-700 text-sm font-medium">Company Description</label>
                    <textarea id="description" name="description" rows="4" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 shadow-sm"></textarea>
                </div>

                <div>
                    <label for="image" class="text-gray-700 text-sm font-medium">Company Image</label>
                    <input type="file" id="image" name="image" accept="image/*" required class="w-full mt-2 px-4 py-3 rounded-lg bg-gray-100 text-gray-900 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 shadow-sm">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-md text-lg font-semibold">
                    Post Company
                </button>
            </form>
        </div>
    </div>
</x-form>
