<x-usersdashboard>
    <form method="GET" action="{{ route('Users.application') }}">
        <div class="relative w-72 mx-auto mb-6">
            <label for="company-select" class="block text-sm font-medium text-gray-700 mb-2">
                Select a Company
            </label>
            <div class="relative">
                <select id="company-select" name="company_id" onchange="this.form.submit()"
                        class="block w-full py-3 pl-4 pr-10 text-base border-2 border-blue-500 rounded-lg bg-gradient-to-r from-blue-200 via-blue-300 to-blue-400 hover:bg-gradient-to-r hover:from-blue-300 hover:via-blue-400 hover:to-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-600 transition-all duration-300 ease-in-out">
                    <option value="" class="text-gray-500">-- Choose a Company --</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }} class="hover:bg-blue-100">
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7l3-3m0 0l3 3m-3-3v12" />
                    </svg>
                </div>
            </div>
        </div>
    </form>    
    
    @if($applications->isEmpty())
        <div class="flex flex-col items-center justify-center p-10 bg-gray-50 border border-gray-200 rounded-lg">
            <svg class="w-16 h-16 text-gray-400 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a3 3 0 013-3h1m6 0a3 3 0 00-3-3H6a3 3 0 00-3 3v12a3 3 0 003 3h9a3 3 0 003-3v-6" />
            </svg>
            <p class="text-lg font-semibold text-gray-700">No Applications yet</p>
            <p class="text-gray-500 text-sm">Please select a company or check back later.</p>
        </div>
    @else
        @foreach ($applications as $application)
            @if(request('company_id') == null || $application->job->company->id == request('company_id'))
                <div class="border border-gray-300 p-4 my-4 rounded-lg bg-gray-50 shadow">
                    <div class="text-lg font-semibold text-gray-800">
                        Applicant name: <span class="text-blue-500"> {{ $application->applicant_name}}</span>
                    </div>
                    <div class="text-lg font-semibold text-gray-800">
                        Job: {{ $application->job->job_title}}
                    </div>
                    <a href="{{ asset('storage/' . $application->cv) }}" download 
                       class="text-blue-500 font-medium hover:text-blue-700 transition flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a3 3 0 013-3h1m6 0a3 3 0 00-3-3H6a3 3 0 00-3 3v12a3 3 0 003 3h9a3 3 0 003-3v-6" />
                        </svg>
                        Download CV
                    </a>
                </div>
            @endif
        @endforeach
    @endif
</x-usersdashboard>
