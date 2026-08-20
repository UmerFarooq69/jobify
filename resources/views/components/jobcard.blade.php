<div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 p-5">
    <div class="flex items-start gap-4">
        <!-- Job Image -->
        <div class="flex-shrink-0">
            <img src="{{ asset('storage/' . $job->image) }}" alt="{{ $job->job_title }}"
                class="w-16 h-16 object-cover rounded-lg border border-gray-100">
        </div>

        <!-- Job Info -->
        <div class="flex-1 min-w-0">
            <h3 class="text-base font-semibold text-gray-900 truncate">{{ $job->job_title }}</h3>
            <p class="text-sm text-gray-500 mt-0.5">{{ $job->company->name }}</p>
            <div class="flex flex-wrap items-center gap-3 mt-2">
                <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                    <i class="fas fa-map-marker-alt text-gray-400"></i> {{ $job->company->location }}
                </span>
                <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                    <i class="fas fa-briefcase text-gray-400"></i> {{ $job->job_type }}
                </span>
                <span class="inline-flex items-center gap-1 text-xs {{ $job->applications->count() < 5 ? 'text-amber-600' : 'text-green-600' }}">
                    <i class="fas fa-users text-current"></i> {{ $job->applications->count() }} applicants
                </span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2 flex-shrink-0">
            <button data-modal="jobDescriptionModal-{{ $job->id }}"
                class="p-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors"
                title="View Description">
                <i class="fas fa-eye text-sm"></i>
            </button>
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($job->company->location) }}"
                target="_blank"
                class="p-2 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 transition-colors"
                title="View on Map">
                <i class="fas fa-map-marker-alt text-sm"></i>
            </a>
            <button onclick="copyJobLink('{{ route('jobs.show', $job->id) }}')"
                class="p-2 rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-600 transition-colors"
                title="Share Job">
                <i class="fas fa-share-alt text-sm"></i>
            </button>
            <a href="/problem?job_id={{ $job->job_uuid }}"
                class="p-2 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition-colors"
                title="Report Job">
                <i class="fas fa-flag text-sm"></i>
            </a>
        </div>
    </div>

    <!-- Description Preview -->
    <p class="text-sm text-gray-500 mt-3 line-clamp-2">{{ $job->description }}</p>

    <!-- Apply Button -->
    <div class="mt-4 flex items-center justify-between">
        <span class="text-xs text-gray-400">Job ID: <span class="font-mono">{{ substr($job->job_uuid, 0, 8) }}</span></span>
        <a href="{{ route('jobs.apply', $job->id) }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-5 rounded-lg transition-colors">
            Apply Now <i class="fas fa-arrow-right text-xs"></i>
        </a>
    </div>
</div>

<!-- Job Description Modal -->
<div id="jobDescriptionModal-{{ $job->id }}"
    class="fixed inset-0 bg-black/60 hidden flex justify-center items-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[85vh] flex flex-col">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $job->job_title }}</h2>
                <p class="text-sm text-gray-500">{{ $job->company->name }}</p>
            </div>
            <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition-colors"
                data-close="jobDescriptionModal-{{ $job->id }}">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="px-6 py-5 overflow-y-auto flex-1">
            <p class="text-gray-700 text-sm leading-relaxed">{{ $job->description }}</p>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
            <button class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                data-close="jobDescriptionModal-{{ $job->id }}">Close</button>
            <a href="{{ route('jobs.apply', $job->id) }}" class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                Apply Now
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('[data-modal]').forEach(trigger => {
            trigger.addEventListener('click', function () {
                document.getElementById(this.getAttribute('data-modal')).classList.remove('hidden');
            });
        });
        document.querySelectorAll('[data-close]').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById(this.getAttribute('data-close')).classList.add('hidden');
            });
        });
        window.addEventListener('click', function (event) {
            document.querySelectorAll('.fixed.inset-0').forEach(modal => {
                if (event.target === modal) modal.classList.add('hidden');
            });
        });
    });

    function copyJobLink(link) {
        navigator.clipboard.writeText(link).then(() => {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Link copied!', showConfirmButton: false, timer: 2500, timerProgressBar: true });
        }).catch(() => {
            Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Failed to copy link', showConfirmButton: false, timer: 2500, timerProgressBar: true });
        });
    }
</script>
