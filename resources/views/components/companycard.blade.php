<a href="{{ route('company.jobs', $company->id) }}" class="block group">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm group-hover:shadow-md transition-all duration-200 p-5">
        <div class="flex items-start gap-4 mb-3">
            <img src="{{ asset('storage/' . $company->image) }}" alt="{{ $company->name }}"
                class="w-14 h-14 object-cover rounded-lg border border-gray-100 flex-shrink-0">
            <div class="min-w-0">
                <h3 class="text-base font-semibold text-gray-900 truncate">{{ $company->name }}</h3>
                <div class="flex items-center gap-1 mt-1">
                    <i class="fas fa-map-marker-alt text-gray-400 text-xs"></i>
                    <span class="text-xs text-gray-500">{{ $company->city }}</span>
                </div>
            </div>
        </div>
        <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">
            {{ \Illuminate\Support\Str::limit($company->description, 140) }}
        </p>
        <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
            <div class="flex items-center gap-1 text-xs text-gray-400">
                <i class="fas fa-map-marker-alt"></i> {{ $company->location }}
            </div>
            <span class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">
                <i class="fas fa-briefcase text-xs"></i> {{ $company->jobs_count }} jobs
            </span>
        </div>
    </div>
</a>
