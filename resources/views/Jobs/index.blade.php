<x-form>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Browse Jobs</h1>
            <p class="text-gray-500 text-sm mt-1">Find your next opportunity from thousands of listings</p>
        </div>

        <!-- Search & Filter Form -->
        <form action="{{ route('jobs.search') }}" method="GET"
            class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search Input -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-sm"></i>
                    </div>
                    <input type="text" name="query" id="search-input" placeholder="Search jobs, companies, or skills..."
                        value="{{ request('query') }}"
                        class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg w-full text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                        autocomplete="off">
                    <div id="suggestions"
                        class="absolute bg-white border border-gray-200 shadow-lg rounded-lg hidden w-full mt-1 z-50 max-h-60 overflow-auto">
                    </div>
                </div>

                <!-- Filter Dropdowns -->
                <div class="flex flex-wrap gap-3">
                    @php
                        $filters = [
                            'job_type' => ['label' => 'Job Type', 'options' => $jobTypes],
                            'location' => ['label' => 'Location', 'options' => $locations],
                            'company' => ['label' => 'Company', 'options' => $companies],
                            'city' => ['label' => 'City', 'options' => $cities],
                        ];
                    @endphp

                    @foreach ($filters as $name => $filter)
                        <div class="relative">
                            <select name="{{ $name }}" class="appearance-none pl-3 pr-8 py-2.5 border border-gray-300 rounded-lg bg-white text-sm text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors cursor-pointer">
                                <option value="" {{ request($name) ? '' : 'selected' }}>{{ $filter['label'] }}</option>
                                @foreach ($filter['options'] as $option)
                                    <option value="{{ $option }}" {{ request($name) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-2.5 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors whitespace-nowrap">
                        Search
                    </button>
                    <button type="button" id="clear-form" class="border border-gray-300 hover:bg-gray-100 text-gray-600 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors whitespace-nowrap">
                        Clear
                    </button>
                </div>
            </div>
        </form>

        <!-- Results Count -->
        <p class="text-sm text-gray-500 mb-4">
            Showing <span class="font-semibold text-gray-900">{{ $jobs->count() }}</span> of <span class="font-semibold text-gray-900">{{ $jobs->total() }}</span> jobs
        </p>

        <!-- Job Listings -->
        <div class="space-y-4">
            @foreach ($jobs as $job)
                <x-jobcard :job="$job" />
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $jobs->appends(request()->query())->links() }}
        </div>
    </div>
</x-form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let searchInput = document.getElementById('search-input');
        let suggestionsBox = document.getElementById('suggestions');
        let clearButton = document.getElementById('clear-form');

        searchInput.addEventListener('input', function () {
            let query = searchInput.value.trim();
            if (query.length > 1) {
                fetch(`{{ url('/autocomplete') }}?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsBox.innerHTML = "";
                        if (data.length > 0) {
                            data.forEach(item => {
                                let div = document.createElement('div');
                                div.classList.add('px-4', 'py-2.5', 'cursor-pointer', 'hover:bg-gray-50', 'text-sm', 'text-gray-700', 'border-b', 'border-gray-100', 'last:border-0');
                                div.textContent = item;
                                div.addEventListener('click', function () {
                                    searchInput.value = item;
                                    suggestionsBox.classList.add('hidden');
                                });
                                suggestionsBox.appendChild(div);
                            });
                            suggestionsBox.classList.remove('hidden');
                        } else {
                            suggestionsBox.classList.add('hidden');
                        }
                    });
            } else {
                suggestionsBox.classList.add('hidden');
            }
        });

        document.addEventListener('click', function (event) {
            if (!searchInput.contains(event.target) && !suggestionsBox.contains(event.target)) {
                suggestionsBox.classList.add('hidden');
            }
        });

        clearButton.addEventListener("click", function () {
            let form = document.querySelector("form");
            form.reset();
            form.querySelectorAll("select").forEach(select => select.selectedIndex = 0);
            suggestionsBox.classList.add('hidden');
        });
    });
</script>
