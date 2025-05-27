<x-admin>
    <div class="container mx-auto mt-8">
        <h2 class="text-3xl font-semibold text-center mb-6">Payments Received</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto bg-white border border-gray-200 rounded-lg shadow-md">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Payment ID</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Payment Method</th>
                        <th class="px-4 py-2 text-left">Image</th>
                        <th class="px-4 py-2 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($payments as $payment)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 text-left">{{ $payment->payment_uuid }}</td>
                            <td class="px-4 py-2 text-left">{{ $payment->name }}</td>
                            <td class="px-4 py-2 text-left">{{ $payment->email }}</td>
                            <td class="px-4 py-2 text-left">{{ ucfirst($payment->payment_method) }}</td>
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin>
