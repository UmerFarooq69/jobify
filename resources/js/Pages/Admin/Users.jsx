import AdminLayout from '../../Layouts/AdminLayout';
import { router, useForm } from '@inertiajs/react';
import Icon from '@mdi/react';
import { mdiMagnify, mdiDelete, mdiPlus, mdiClose, mdiToggleSwitch, mdiToggleSwitchOff, mdiAccount, mdiEmail } from '@mdi/js';
import { useState } from 'react';

function Modal({ open, onClose, title, children }) {
    if (!open) return null;
    return (
        <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" onClick={e => e.target === e.currentTarget && onClose()}>
            <div className="bg-white rounded-xl shadow-2xl w-full max-w-md">
                <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h2 className="text-base font-semibold text-gray-900">{title}</h2>
                    <button onClick={onClose} className="p-1 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                        <Icon path={mdiClose} size={0.8} />
                    </button>
                </div>
                <div className="px-6 py-5">{children}</div>
            </div>
        </div>
    );
}

function Field({ label, error, children }) {
    return (
        <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">{label}</label>
            {children}
            {error && <p className="mt-1 text-xs text-red-600">{error}</p>}
        </div>
    );
}

const inputCls = 'w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent';

export default function Users({ users = [] }) {
    const [search, setSearch]         = useState('');
    const [showCreate, setShowCreate] = useState(false);

    const form = useForm({ name: '', email: '', password: '', role: 'user' });

    const filtered = users.filter(u =>
        u.name?.toLowerCase().includes(search.toLowerCase()) ||
        u.email?.toLowerCase().includes(search.toLowerCase())
    );

    function submitCreate(e) {
        e.preventDefault();
        form.post('/users', { onSuccess: () => { setShowCreate(false); form.reset(); } });
    }

    function deleteUser(id) {
        window.Swal?.fire({
            title: 'Delete this user?', text: 'This cannot be undone.', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
        }).then(r => { if (r.isConfirmed) router.delete(`/admin/users/${id}`); });
    }

    function toggleStatus(id) {
        router.patch(`/admin/users/${id}/toggle-status`);
    }

    return (
        <AdminLayout title="Users">
            <Modal open={showCreate} onClose={() => setShowCreate(false)} title="Add New User">
                <form onSubmit={submitCreate} className="space-y-4">
                    <Field label="Full Name" error={form.errors.name}>
                        <input type="text" value={form.data.name} onChange={e => form.setData('name', e.target.value)} className={inputCls} placeholder="John Doe" required />
                    </Field>
                    <Field label="Email" error={form.errors.email}>
                        <input type="email" value={form.data.email} onChange={e => form.setData('email', e.target.value)} className={inputCls} placeholder="john@example.com" required />
                    </Field>
                    <Field label="Password" error={form.errors.password}>
                        <input type="password" value={form.data.password} onChange={e => form.setData('password', e.target.value)} className={inputCls} placeholder="Min 6 characters" required />
                    </Field>
                    <Field label="Role" error={form.errors.role}>
                        <select value={form.data.role} onChange={e => form.setData('role', e.target.value)} className={inputCls}>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </Field>
                    <div className="flex justify-end gap-3 pt-2">
                        <button type="button" onClick={() => setShowCreate(false)} className="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">Cancel</button>
                        <button type="submit" disabled={form.processing} className="px-4 py-2 text-sm bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-lg transition-colors disabled:opacity-60">
                            {form.processing ? 'Creating…' : 'Create User'}
                        </button>
                    </div>
                </form>
            </Modal>

            <div className="space-y-5">
                <div className="flex items-center justify-between gap-4 flex-wrap">
                    <div className="relative flex-1 min-w-[200px] max-w-sm">
                        <Icon path={mdiMagnify} size={0.75} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                        <input type="text" placeholder="Search users…" value={search} onChange={e => setSearch(e.target.value)}
                            className="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" />
                    </div>
                    <div className="flex items-center gap-3">
                        <span className="text-sm text-gray-500">{filtered.length} user{filtered.length !== 1 ? 's' : ''}</span>
                        <button onClick={() => setShowCreate(true)} className="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                            <Icon path={mdiPlus} size={0.75} /> Add User
                        </button>
                    </div>
                </div>

                <div className="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="border-b border-gray-100 bg-gray-50">
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">User</th>
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Email</th>
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Status</th>
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Joined</th>
                                <th className="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-50">
                            {filtered.length === 0 ? (
                                <tr><td colSpan={5} className="text-center py-12 text-gray-400 text-sm">No users found</td></tr>
                            ) : filtered.map(user => (
                                <tr key={user.id} className="hover:bg-gray-50 transition-colors">
                                    <td className="px-4 py-3">
                                        <div className="flex items-center gap-3">
                                            <div className="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                                                <Icon path={mdiAccount} size={0.7} className="text-blue-700" />
                                            </div>
                                            <span className="font-medium text-gray-900">{user.name}</span>
                                        </div>
                                    </td>
                                    <td className="px-4 py-3 text-gray-600">
                                        <span className="flex items-center gap-1.5">
                                            <Icon path={mdiEmail} size={0.6} className="text-gray-400" />
                                            {user.email}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3">
                                        <span className={`inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ${
                                            user.active ? 'bg-emerald-50 text-emerald-700 ring-emerald-100' : 'bg-red-50 text-red-700 ring-red-100'
                                        }`}>
                                            {user.active ? 'Active' : 'Inactive'}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3 text-gray-500 text-xs">
                                        {user.created_at ? new Date(user.created_at).toLocaleDateString() : '—'}
                                    </td>
                                    <td className="px-4 py-3">
                                        <div className="flex items-center gap-1 justify-end">
                                            <button onClick={() => toggleStatus(user.id)} title={user.active ? 'Deactivate' : 'Activate'}
                                                className="text-gray-400 hover:text-blue-600 transition-colors p-1">
                                                <Icon path={user.active ? mdiToggleSwitch : mdiToggleSwitchOff} size={0.9} />
                                            </button>
                                            <button onClick={() => deleteUser(user.id)}
                                                className="inline-flex items-center gap-1 text-xs text-red-600 hover:bg-red-50 px-2 py-1 rounded transition-colors">
                                                <Icon path={mdiDelete} size={0.6} /> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </AdminLayout>
    );
}
