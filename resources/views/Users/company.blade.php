<x-usersdashboard>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-200 text-green-700 rounded" style="min-height: 50px;">
            {{ session('success') }}
        </div>
    @endif
    <div class="flex flex-wrap mb-6">
        <a href="{{ route('companies.create') }}" class="inline-block bg-gray-800 text-white py-2 px-6 rounded-lg shadow-md hover:bg-gray-700 hover:shadow-lg transition duration-300">Create New Company</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($companies as $company)
            <div class="bg-white p-4 border border-gray-200 rounded-lg shadow-md h-80 flex flex-col justify-between">
                <img src="{{ asset('storage/' . $company->image) }}" alt="Company Image" 
                     class="w-full h-40 object-cover rounded"> 
                <div>
                    <h2 class="text-gray-700 mb-2 truncate"><strong>Company Name:</strong> {{ $company->name }}</h2>
                    <p class="text-gray-700 mb-2"><strong>Location:</strong> {{ $company->location }}</p>
                    <p class="text-gray-700 mb-1"><strong>City:</strong> {{ $company->city }}</p>
                </div>
                <div class="flex justify-between items-center">
                    <a href="{{ route('companies.show', $company) }}" class="text-blue-500 hover:underline">View Jobs</a>
                    <a href="#" class="text-yellow-500 hover:underline">Edit</a>
                    <form action="{{ route('company.destroy', $company) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
</x-usersdashboard>