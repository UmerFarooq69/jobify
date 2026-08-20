<x-form>
    <!-- Page Hero -->
    <div class="bg-gradient-to-br from-slate-900 to-blue-950 py-14 px-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-white mb-2">Explore Companies</h1>
            <p class="text-slate-300 text-base mb-6">Discover what an employer is really like before you make your next move.</p>
            <div class="flex flex-wrap gap-2">
                <span class="bg-white/10 hover:bg-white/20 text-white text-sm font-medium px-4 py-2 rounded-full border border-white/20 cursor-pointer transition-colors">Work/life balance</span>
                <span class="bg-white/10 hover:bg-white/20 text-white text-sm font-medium px-4 py-2 rounded-full border border-white/20 cursor-pointer transition-colors">Diversity &amp; inclusion</span>
                <span class="bg-white/10 hover:bg-white/20 text-white text-sm font-medium px-4 py-2 rounded-full border border-white/20 cursor-pointer transition-colors">Compensation &amp; benefits</span>
            </div>
        </div>
    </div>

    <!-- Company Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-gray-500">{{ $companies->count() }} companies found</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($companies as $company)
                <x-companycard :company="$company" />
            @endforeach
        </div>
    </div>
</x-form>
