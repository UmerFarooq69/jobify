import AdminLayout from '../../Layouts/AdminLayout';
import { router } from '@inertiajs/react';
import Icon from '@mdi/react';
import { mdiMagnify, mdiDelete, mdiEmail, mdiAccount, mdiClock, mdiCheckCircle } from '@mdi/js';
import { useState } from 'react';

export default function Contacts({ contacts = [] }) {
    const [search, setSearch] = useState('');

    const filtered = contacts.filter(c =>
        c.name?.toLowerCase().includes(search.toLowerCase()) ||
        c.email?.toLowerCase().includes(search.toLowerCase()) ||
        c.message?.toLowerCase().includes(search.toLowerCase())
    );

    function deleteContact(id) {
        window.Swal?.fire({
            title: 'Delete this message?', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
        }).then(r => { if (r.isConfirmed) router.delete(`/contacts/${id}`); });
    }

    function toggleStatus(id) {
        router.post(`/contacts/${id}/toggle-status`);
    }

    return (
        <AdminLayout title="Contact Submissions">
            <div className="space-y-5">
                <div className="flex items-center justify-between gap-4">
                    <div className="relative flex-1 max-w-sm">
                        <Icon path={mdiMagnify} size={0.75} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                        <input type="text" placeholder="Search messages…" value={search} onChange={e => setSearch(e.target.value)}
                            className="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" />
                    </div>
                    <span className="text-sm text-gray-500">{filtered.length} message{filtered.length !== 1 ? 's' : ''}</span>
                </div>

                <div className="space-y-3">
                    {filtered.length === 0 ? (
                        <div className="text-center py-16 text-gray-400 text-sm bg-white rounded-xl border border-gray-200">No contact messages found</div>
                    ) : filtered.map(contact => (
                        <div key={contact.id} className="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                            <div className="flex items-start justify-between gap-4">
                                <div className="flex-1">
                                    <div className="flex items-center gap-3 mb-2 flex-wrap">
                                        <div className="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                            <Icon path={mdiAccount} size={0.7} className="text-blue-700" />
                                        </div>
                                        <div>
                                            <span className="font-semibold text-gray-900 text-sm">{contact.name}</span>
                                            <div className="flex items-center gap-1 text-xs text-gray-500">
                                                <Icon path={mdiEmail} size={0.55} />{contact.email}
                                            </div>
                                        </div>
                                        <span className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium ring-1 ${
                                            contact.status === 'seen'
                                                ? 'bg-emerald-50 text-emerald-700 ring-emerald-100'
                                                : 'bg-amber-50 text-amber-700 ring-amber-100'
                                        }`}>
                                            <Icon path={contact.status === 'seen' ? mdiCheckCircle : mdiClock} size={0.55} />
                                            {contact.status === 'seen' ? 'Seen' : 'Pending'}
                                        </span>
                                    </div>
                                    <p className="text-sm text-gray-600 pl-11 leading-relaxed">{contact.message}</p>
                                    <p className="text-xs text-gray-400 pl-11 mt-2">
                                        {contact.created_at ? new Date(contact.created_at).toLocaleString() : ''}
                                    </p>
                                </div>
                                <div className="flex items-center gap-2 flex-shrink-0">
                                    <button onClick={() => toggleStatus(contact.id)}
                                        className="text-xs text-blue-600 hover:bg-blue-50 px-2 py-1 rounded transition-colors">
                                        {contact.status === 'seen' ? 'Mark Pending' : 'Mark Seen'}
                                    </button>
                                    <button onClick={() => deleteContact(contact.id)}
                                        className="inline-flex items-center gap-1 text-xs text-red-600 hover:bg-red-50 px-2 py-1 rounded transition-colors">
                                        <Icon path={mdiDelete} size={0.6} /> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </AdminLayout>
    );
}
