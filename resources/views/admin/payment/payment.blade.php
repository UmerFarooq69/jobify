<x-admin>
    <!-- Header + Search -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h2 class="text-base font-semibold text-gray-900">Payments Received</h2>
        <form method="GET" action="{{ route('payment.index') }}" class="flex items-center gap-2">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search payments..."
                    class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                Search
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Payment ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Receipt</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($payments as $payment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-xs font-mono text-gray-500">{{ substr($payment->payment_uuid, 0, 12) }}...</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $payment->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $payment->email }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $payment->number }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center text-xs font-medium bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full">{{ $payment->plan }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $payment->payment_method }}</td>
                        <td class="px-4 py-3">
                            @if ($payment->attachment)
                                <a href="{{ asset('storage/' . $payment->attachment) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $payment->attachment) }}" alt="Receipt" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                                </a>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('payment.destroy', $payment->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition-colors delete-btn">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-600">No results found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin>
