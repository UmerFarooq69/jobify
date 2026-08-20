<x-usersdashboard>
    <!-- Toolbar -->
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <a href="{{ route('companies.create') }}" class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-plus text-xs text-gray-500"></i> Create New Company
        </a>
    </div>

    <!-- Companies Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($companies as $company)
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
            <img src="{{ asset('storage/' . $company->image) }}" alt="{{ $company->name }}" class="w-full h-36 object-cover">
            <div class="p-4 flex-1 flex flex-col">
                <h2 class="text-sm font-semibold text-gray-900 truncate mb-2">{{ $company->name }}</h2>
                <div class="space-y-1 text-xs text-gray-500 flex-1">
                    <div class="flex items-center gap-2"><i class="fas fa-map-marker-alt w-3 text-gray-400"></i> {{ $company->location }}</div>
                    <div class="flex items-center gap-2"><i class="fas fa-city w-3 text-gray-400"></i> {{ $company->city }}</div>
                </div>
                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('companies.show', $company) }}" class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 hover:text-blue-700 transition-colors">
                        <i class="fas fa-briefcase"></i> View Jobs
                    </a>
                    <a href="{{ route('company.edit', $company) }}" class="inline-flex items-center gap-1 text-xs font-medium text-amber-600 hover:text-amber-700 transition-colors">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('user.company.destroy', $company) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1 text-xs font-medium text-red-500 hover:text-red-600 transition-colors delete-btn">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</x-usersdashboard>
