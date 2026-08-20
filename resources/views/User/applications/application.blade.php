<x-usersdashboard>
    <!-- Filter Bar -->
    <form method="GET" action="{{ route('User.applications.application') }}" class="mb-6">
        <div class="flex items-center gap-3">
            <div class="relative">
                <select id="company-select" name="company_id" onchange="this.form.submit()"
                    class="appearance-none pl-4 pr-10 py-2.5 border border-gray-300 rounded-lg bg-white text-sm text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors cursor-pointer min-w-[220px]">
                    <option value="">All Companies</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                </div>
            </div>
        </div>
    </form>

    <!-- Count Summary -->
    <div class="mb-5 text-sm font-medium text-gray-700">
        @if(request('company_id'))
            Applications for <span class="text-blue-600">{{ $companies->firstWhere('id', request('company_id'))->name }}</span>:
            <span class="font-bold text-gray-900">{{ $applications->where('job.company_id', request('company_id'))->count() }}</span>
        @else
            Total Applications: <span class="font-bold text-gray-900">{{ $applications->count() }}</span>
        @endif
    </div>

    @if($applications->isEmpty())
        <div class="flex flex-col items-center justify-center p-16 bg-white border border-gray-200 rounded-xl">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-file-alt text-gray-400 text-xl"></i>
            </div>
            <p class="text-sm font-medium text-gray-600">No applications yet</p>
            <p class="text-xs text-gray-400 mt-1">Select a company or check back later</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($applications as $application)
                @if(request('company_id') == null || $application->job->company->id == request('company_id'))
                <div class="bg-white border border-gray-200 rounded-xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="text-sm font-semibold text-gray-900">{{ $application->applicant_name }}</div>
                        <div class="text-xs text-gray-500">{{ $application->applicant_email }}</div>
                        <span class="inline-flex items-center gap-1 text-xs text-gray-600 bg-gray-100 px-2 py-0.5 rounded-full mt-1">
                            <i class="fas fa-briefcase text-gray-400"></i> {{ $application->job->job_title }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <a href="{{ asset('storage/' . $application->cv) }}" download
                            class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fas fa-download text-xs"></i> Download CV
                        </a>
                        <form action="{{ route('application.destroy', $application->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition-colors delete-btn">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    @endif
</x-usersdashboard>
