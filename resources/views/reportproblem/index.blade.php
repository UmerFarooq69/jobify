<x-admin>
    <div class="container mx-auto mt-8">
        <h2 class="text-3xl font-semibold text-center mb-6">Problems Reported</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto bg-white border border-gray-200 rounded-lg shadow-md">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Problem</th>
                        <th class="px-4 py-2 text-left">Report time</th>
                        <th class="px-4 py-2 text-left">Image</th>
                        <th class="px-12 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($problems as $problem)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $problem->name }}</td>
                            <td class="px-4 py-2">{{ $problem->email }}</td>                            
                            <td class="px-4 py-2 text-blue-700 font-bold">{{ $problem->problem }}</td>
                            <td class="px-4 py-2">{{ $problem->created_at->timezone('Asia/Karachi')->format('d M Y, h:i A') }}</td>
                            
                            <td class="px-4 py-2">
                                @if ($problem->image)
                                    <a href="{{ asset('storage/' . $problem->image) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $problem->image) }}" alt="Reported Image" class="w-16 h-16 object-cover rounded shadow">
                                    </a>
                                @else
                                    <span class="text-gray-500">No Image</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center">
                                    <form action="{{ route('problems.destroy', $problem->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 cursor-pointer">
                                            <i class="fas fa-trash-alt hover:scale-105 transition-all duration-200"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin>
