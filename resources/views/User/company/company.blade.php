<x-usersdashboard>
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-lg shadow-md">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('companies.create') }}" class="bg-blue-600 text-white py-2 px-6 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
                + Create New Company
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($companies as $company)
                <div class="bg-white p-4 border border-gray-200 rounded-lg shadow-md flex flex-col transition hover:shadow-lg">
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $company->image) }}" alt="Company Image"
                             class="w-full h-40 object-cover rounded-lg shadow">
                    </div>

                    <div class="flex-grow">
                        <h2 class="text-gray-800 font-semibold truncate mb-1">
                            <strong>Company Name:</strong> {{ $company->name }}
                        </h2>
                        <p class="text-gray-700 mb-1"><strong>Location:</strong> {{ $company->location }}</p>
                        <p class="text-gray-700"><strong>City:</strong> {{ $company->city }}</p>
                    </div>

                    <div class="flex justify-between items-center mt-4 border-t pt-3">
                        <a href="{{ route('companies.show', $company) }}" class="text-blue-500 hover:text-blue-600">
                            View Jobs
                        </a>
                        <a href="{{route('company.edit', $company)}}" class="text-yellow-500 hover:text-yellow-600">Edit</a>
                        <form action="{{ route('user.company.destroy', $company) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-600 delete-btn" >
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
</x-usersdashboard>