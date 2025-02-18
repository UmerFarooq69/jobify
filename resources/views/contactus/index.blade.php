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
                            <td class="px-6 py-4 flex items-center space-x-4">
                                <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $contact->email }}" target="_blank" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300">
                                    Reply
                                </a>
                                <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 cursor-pointer">
                                        <i class="fas fa-trash-alt hover:scale-105 transition-all duration-200"></i>
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

<style>
    .table {
        font-size: 14px;
    }
</style>
