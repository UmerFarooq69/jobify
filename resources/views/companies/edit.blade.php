<x-form>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Edit Company</h1>
                <p class="text-gray-500 text-sm mt-1">Update your company information</p>
            </div>

            <form action="{{ route('company.update', $company) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Company Name</label>
                    <input type="text" id="name" name="name" value="{{ $company->name }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors">
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1.5">City</label>
                    <input type="text" id="city" name="city" value="{{ $company->city }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors">
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1.5">Full Address</label>
                    <input type="text" id="location" name="location" value="{{ $company->location }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Company Description</label>
                    <textarea id="description" name="description" rows="4" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors resize-none">{{ old('description', $company->description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Company Image</label>
                    @if(isset($company) && $company->image)
                        <div class="mb-3 flex items-center gap-3">
                            <img src="{{ asset('storage/' . $company->image) }}" alt="{{ $company->name }}" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                            <span class="text-xs text-gray-400">Current image</span>
                        </div>
                    @endif
                    <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-5 text-center hover:border-blue-400 transition-colors cursor-pointer">
                        <input type="file" name="image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <i class="fas fa-image text-gray-400 text-xl mb-2"></i>
                        <p class="text-sm text-gray-500">Upload new image <span class="text-gray-400 text-xs">(leave blank to keep current)</span></p>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg transition-colors font-semibold text-sm shadow-sm">
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</x-form>
