<x-admin>
    <h2 class="text-base font-semibold text-gray-900 mb-6">Reported Problems</h2>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reporter</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Purpose</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Company</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($problems as $problem)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-xs text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium text-gray-900">{{ $problem->name }}</div>
                            <div class="text-xs text-gray-400">{{ $problem->email }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex text-xs font-medium bg-orange-50 text-orange-700 px-2 py-0.5 rounded-full">
                                {{ ucfirst(str_replace('_', ' ', $problem->purpose)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex text-xs font-medium bg-red-50 text-red-700 px-2 py-0.5 rounded-full">
                                {{ ucfirst($problem->report_type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 max-w-xs">
                            <p class="truncate">{{ $problem->problem }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            @if ($problem->company_id)
                                {{ optional($problem->company)->name ?? optional($problem->job->company)->name ?? '—' }}
                            @else
                                {{ optional(optional($problem->job)->company)->name ?? '—' }}
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-400 whitespace-nowrap">
                            {{ $problem->created_at->timezone('Asia/Karachi')->format('d M Y, h:i A') }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($problem->image)
                                <a href="{{ asset('storage/' . $problem->image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $problem->image) }}" alt="Report Image" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                                </a>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('problems.destroy', $problem->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-trash text-sm"></i>
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
