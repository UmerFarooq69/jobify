<x-admin>
    <h2 class="text-base font-semibold text-gray-900 mb-6">Contact Submissions</h2>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($contacts as $contact)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-xs text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $contact->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $contact->email }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 max-w-xs">
                            <p class="truncate">{{ $contact->message }}</p>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-400 whitespace-nowrap">
                            {{ $contact->created_at->timezone('Asia/Karachi')->format('d M Y, h:i A') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $contact->email }}" target="_blank"
                                    class="inline-flex items-center gap-1 text-xs font-medium bg-blue-50 text-blue-600 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg transition-colors">
                                    <i class="fas fa-reply text-xs"></i> Reply
                                </a>
                                <form action="{{ route('contacts.toggleStatus', $contact) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1.5 rounded-lg transition-colors {{ $contact->status == 'pending' ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                                        {{ $contact->status == 'pending' ? 'Mark Seen' : 'Mark Pending' }}
                                    </button>
                                </form>
                                <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-trash text-sm"></i>
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
