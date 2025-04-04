<x-admin>
    <div class="container mx-auto mt-8">
        <h2 class="text-3xl font-semibold text-center mb-6">Who Contact Us</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto bg-white border border-gray-200 rounded-lg shadow-md">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Message</th>
                        <th class="px-4 py-2 text-left">Contact time</th>
                        <th class="px-12 py-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($contacts as $contact)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $contact->name }}</td>
                            <td class="px-4 py-2">{{ $contact->email }}</td>                            
                            <td class="px-4 py-2 text-blue-700 font-bold">{{ $contact->message }}</td>
                            <td class="px-4 py-2">{{ $contact->created_at->timezone('Asia/Karachi')->format('d M Y, h:i A') }}</td>
                            <td class="px-6 py-4 flex flex-wrap gap-2 items-center">
                                <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $contact->email }}" target="_blank" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">
                                    Reply
                                </a>
                                
                                <form action="{{ route('contacts.destroy', $contact) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 cursor-pointer">
                                        <i class="fas fa-trash-alt hover:scale-105 transition-all duration-200"></i>
                                    </button>
                                </form>
                            
                                @if($contact->status == 'pending')
                                    <form action="{{ route('contacts.seen', $contact) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">
                                            Seen
                                        </button>
                                    </form>
                            
                                    <form action="{{ route('contacts.pending', $contact) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                            Pending
                                        </button>
                                    </form>
                                @else
                                    <span class="px-3 py-1 rounded text-white {{ $contact->status == 'seen' ? 'bg-green-600' : 'bg-red-600' }}">
                                        {{ ucfirst($contact->status) }}
                                    </span>
                                @endif
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin>

<style>
    .table {
        font-size: 14px;
    }
</style>
