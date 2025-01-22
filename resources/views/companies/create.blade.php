<x-form>
    <div class="min-h-screen flex items-center justify-center bg-gray-900">
        <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data" class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
            @csrf
            <div class="pt-4">
                <label for="name" class="text-white font-semibold">Company Name</label>
                <input type="text" id="name" name="name" required class="w-full mt-2 p-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="pt-4">
                <label for="city" class="text-white font-semibold">City</label>
                <input type="text" id="city" name="city" required class="w-full mt-2 p-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="pt-4">
                <label for="location" class="text-white font-semibold">Location</label>
                <input type="text" id="location" name="location" required class="w-full mt-2 p-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="pt-4">
                <label for="image" class="text-white font-semibold">Company Image</label>
                <input type="file" id="image" name="image" accept="image/*" required class="w-full mt-2 p-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full mt-6 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Post Company
            </button>
        </form>
    </div>
</x-form>
