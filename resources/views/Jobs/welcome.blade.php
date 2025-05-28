<x-form>
    <section>
        <!-- Page Title -->
        <div class="font-bold text-2xl px-4 sm:px-6 md:px-10 pt-4 sm:pt-6">
            All jobs
        </div>

        <!-- Search & Filter Form -->
        <div class="px-4 sm:px-6 md:px-10 mt-6">
            <form action="{{ route('jobs.search') }}" method="GET" 
                  class="bg-white p-4 sm:p-6 rounded-xl shadow-lg flex flex-col md:flex-row md:flex-wrap gap-4 items-stretch md:items-center">

                <!-- Search Input -->
                <div class="relative w-full md:flex-1">
                    <input 
                        type="text" 
                        name="query" 
                        id="search-input"
                        placeholder="Search for jobs..." 
                        value="{{ request('query') }}" 
                        class="px-4 py-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        autocomplete="off"
                    >
                    <div id="suggestions" 
                         class="absolute bg-white shadow-lg rounded-md hidden w-full mt-1 z-50 max-h-60 overflow-auto">
                    </div>
                </div>

                <!-- Filter Dropdowns -->
                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto flex-wrap">
                    @php
                        $filters = [
                            'job_type' => ['label' => 'Select Job Type', 'options' => $jobTypes],
                            'location' => ['label' => 'Select Location', 'options' => $locations],
                            'company' => ['label' => 'Select Company', 'options' => $companies],
                            'city' => ['label' => 'Select City', 'options' => $cities],
                        ];
                    @endphp
                    
                    @foreach ($filters as $name => $filter)
                        <div class="relative w-full sm:w-48">
                            <select name="{{ $name }}" 
                                    class="appearance-none px-4 py-3 border border-gray-300 rounded-lg bg-white 
                                           focus:ring-2 focus:ring-blue-500 focus:outline-none w-full cursor-pointer 
                                           text-gray-700 font-medium shadow-sm transition hover:border-blue-400">
                                <option value="" disabled selected class="text-gray-400">
                                    {{ $filter['label'] }}
                                </option>
                                @foreach ($filter['options'] as $option)
                                    <option value="{{ $option }}" {{ request($name) == $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg 
                                   transition duration-200 w-full sm:w-auto">
                        Search
                    </button>
                    <button type="button" id="clear-form" 
                            class="bg-gray-400 hover:bg-gray-500 text-white font-semibold px-6 py-3 rounded-lg 
                                   transition duration-200 w-full sm:w-auto">
                        Clear Form
                    </button>
                </div>
            </form>
        </div>

        <!-- Job Count -->
        <div class="mt-4 px-4 sm:px-6 md:px-10">
            <p class="text-lg text-gray-500">
                Showing {{ $jobs->count() }} jobs of {{ $jobs->total() }}
            </p>
        </div>
        
        <!-- Job Listings -->
        <div class="mt-6 px-4 sm:px-6 md:px-10">
            <div class="grid grid-cols-1 gap-6">
                @foreach ($jobs as $job)
                    <x-jobcard :job="$job" />
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 px-4 sm:px-6 md:px-10">
            {{ $jobs->appends(request()->query())->links() }}
        </div>
    </section>
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
                            div.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-200');
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
            // Reset selects explicitly to placeholder (first option)
            form.querySelectorAll("select").forEach(select => select.selectedIndex = 0);
            suggestionsBox.classList.add('hidden');
        });
    });
</script>
