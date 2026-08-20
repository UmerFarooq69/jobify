<x-form>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Create Company</h1>
                <p class="text-gray-500 text-sm mt-1">Fill in the details to register your company on the platform</p>
            </div>

            <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Company Name</label>
                    <input type="text" id="name" name="name" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                        placeholder="e.g. Acme Corporation">
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1.5">City</label>
                    <input type="text" id="city" name="city" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                        placeholder="e.g. Lahore">
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1.5">Full Address</label>
                    <input type="text" id="location" name="location" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                        placeholder="e.g. Canal Road, Lahore, Punjab">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Company Description</label>
                    <textarea id="description" name="description" rows="4" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors resize-none"
                        placeholder="Describe your company, culture, and what you do..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Company Logo / Image</label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 transition-colors cursor-pointer">
                        <input type="file" id="image" name="image" accept="image/*" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <i class="fas fa-image text-gray-400 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-500">Drag & drop or <span class="text-blue-600 font-medium">browse</span></p>
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF up to 10MB</p>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg transition-colors font-semibold text-sm shadow-sm">
                    Create Company
                </button>
            </form>
        </div>
    </div>
</x-form>
