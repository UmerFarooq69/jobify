<x-form>
    <!-- Page Header -->
    <div class="bg-white border-b border-gray-100 py-10 px-6">
        <div class="max-w-7xl mx-auto">
            <span class="inline-block text-xs font-semibold text-blue-600 uppercase tracking-widest mb-2">Browse</span>
            <h1 class="text-3xl font-bold text-gray-900">Explore Companies</h1>
            <p class="text-gray-500 text-sm mt-2 max-w-xl">Discover top employers and learn what it's like to work there before making your next move.</p>

            <!-- Filter Chips -->
            <div class="flex flex-wrap gap-2 mt-5">
                <span class="bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-medium px-4 py-1.5 rounded-full border border-blue-200 cursor-pointer transition-colors">Work/life balance</span>
                <span class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-1.5 rounded-full border border-gray-200 cursor-pointer transition-colors">Diversity &amp; inclusion</span>
                <span class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-1.5 rounded-full border border-gray-200 cursor-pointer transition-colors">Compensation &amp; benefits</span>
                <span class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-1.5 rounded-full border border-gray-200 cursor-pointer transition-colors">Career growth</span>
            </div>
        </div>
    </div>

    <!-- Company Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-gray-500"><span class="font-semibold text-gray-900">{{ $companies->count() }}</span> companies found</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($companies as $company)
                <x-companycard :company="$company" />
            @endforeach
        </div>
    </div>
</x-form>
