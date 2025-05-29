<x-admin>
    <div class="container mx-auto mt-8">
        <h2 class="text-3xl font-semibold text-center mb-6">Payments Received</h2>
        <form method="GET" action="{{ route('payment.index') }}" class="mb-6 flex justify-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search payments..." 
            class="border border-gray-300 rounded px-4 py-2 mr-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Search
            </button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto bg-white border border-gray-200 rounded-lg shadow-md">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Payment ID</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Mobile Number</th>
                        <th class="px-4 py-2 text-left">Plan</th>
                        <th class="px-4 py-2 text-left">Payment Method</th>
                        <th class="px-4 py-2 text-left">Image</th>
                        <th class="px-4 py-2 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($payments as $payment)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 text-left">{{ $payment->payment_uuid }}</td>
                        <td class="px-4 py-2 text-left">{{ $payment->name }}</td>
                        <td class="px-4 py-2 text-left">{{ $payment->email }}</td>
                        <td class="px-4 py-2 text-left">{{ $payment->number }}</td>
                        <td class="px-4 py-2 text-left">{{ $payment->plan }}</td>
                        <td class="px-4 py-2 text-left">{{ ($payment->payment_method) }}</td>
                        <td class="px-4 py-2 text-left">
                            @if ($payment->attachment)
                            <a href="{{ asset('storage/' . $payment->attachment) }}" target="_blank">
                                <img src="{{ asset('storage/' . $payment->attachment) }}" alt="Payment Image" class="w-16 h-16 object-cover rounded shadow">
                            </a>
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center">
                            <form action="{{ route('payment.destroy', $payment->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline delete-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-red-500 font-semibold text-6xl">
                            <i class="fas fa-search"></i>
                            Oops! No result found.
                        </td>
                    </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
    </div>
</x-admin>
