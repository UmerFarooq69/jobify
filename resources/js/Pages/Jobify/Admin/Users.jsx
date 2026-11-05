import JobifyLayout from '../../../Layouts/JobifyLayout';
import { Link, router, useForm } from '@inertiajs/react';
import { useState } from 'react';

const ROLE_LABELS = {
    candidate:     { label: 'Candidate',      bg: '#EBE6F5', color: '#38009A' },
    company_admin: { label: 'Employer',       bg: '#D1FAE5', color: '#065F46' },
    platform_admin:{ label: 'Platform Admin', bg: '#FEE2E2', color: '#991B1B' },
};

function RoleBadge({ role }) {
    const cfg = ROLE_LABELS[role] ?? { label: role ?? 'User', bg: '#F3F4F6', color: '#6B7280' };
    return (
        <span className="text-xs font-semibold px-3 py-1 rounded-full" style={{ background: cfg.bg, color: cfg.color }}>
            {cfg.label}
        </span>
    );
}

function StatusBadge({ status }) {
    if (status === 'suspended') return <span className="text-xs font-semibold px-3 py-1 rounded-full bg-red-50 text-red-600">Suspended</span>;
    if (status === 'deleted')   return <span className="text-xs font-semibold px-3 py-1 rounded-full bg-gray-100 text-gray-400">Deleted</span>;
    return <span className="text-xs font-semibold px-3 py-1 rounded-full bg-green-50 text-green-700">Active</span>;
}

export default function AdminUsers({ users, filters }) {
    const [search, setSearch] = useState(filters?.search ?? '');
    const [role,   setRole]   = useState(filters?.role   ?? '');

    function applyFilters(e) {
        e.preventDefault();
        router.get('/platform-admin/users', { search, role }, { preserveState: true, replace: true });
    }

    function toggleStatus(userId) {
        router.patch(`/platform-admin/users/${userId}/toggle-status`, {}, { preserveState: true });
    }

    return (
        <JobifyLayout title="Users">
            <div className="space-y-5 max-w-5xl">

                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-xl font-bold text-[#1F0055]">Users</h1>
                        <p className="text-sm text-gray-400 mt-0.5">{users.total} total users</p>
                    </div>
                </div>

                {/* Filters */}
                <form onSubmit={applyFilters} className="bg-white rounded-2xl p-4 shadow-sm ring-1 ring-gray-100 flex flex-wrap gap-3 items-end">
                    <div className="flex-1 min-w-48">
                        <label className="text-xs font-medium text-gray-500 mb-1 block">Search</label>
                        <input
                            value={search}
                            onChange={e => setSearch(e.target.value)}
                            placeholder="Name or email…"
                            className="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-[#38009A]/30"
                        />
                    </div>
                    <div className="min-w-40">
                        <label className="text-xs font-medium text-gray-500 mb-1 block">Role</label>
                        <select
                            value={role}
                            onChange={e => setRole(e.target.value)}
                            className="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-[#38009A]/30 bg-white"
                        >
                            <option value="">All roles</option>
                            <option value="candidate">Candidate</option>
                            <option value="company_admin">Employer</option>
                            <option value="platform_admin">Platform Admin</option>
                        </select>
                    </div>
                    <button type="submit"
                        className="px-5 py-2 rounded-xl text-sm font-semibold text-white transition hover:opacity-90"
                        style={{ background: 'linear-gradient(135deg,#38009A,#9E0059)' }}>
                        Search
                    </button>
                    {(search || role) && (
                        <button type="button" onClick={() => { setSearch(''); setRole(''); router.get('/platform-admin/users'); }}
                            className="px-4 py-2 rounded-xl text-sm font-medium text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                            Clear
                        </button>
                    )}
                </form>

                {/* Table */}
                <div className="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="border-b border-gray-100 bg-gray-50/60">
                                    <th className="text-left px-5 py-3 font-semibold text-xs text-gray-500 uppercase tracking-wide">User</th>
                                    <th className="text-left px-4 py-3 font-semibold text-xs text-gray-500 uppercase tracking-wide">Role</th>
                                    <th className="text-left px-4 py-3 font-semibold text-xs text-gray-500 uppercase tracking-wide">Status</th>
                                    <th className="text-left px-4 py-3 font-semibold text-xs text-gray-500 uppercase tracking-wide">Joined</th>
                                    <th className="px-4 py-3" />
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-50">
                                {users.data?.length > 0 ? users.data.map(u => (
                                    <tr key={u.id} className="hover:bg-gray-50/50 transition-colors">
                                        <td className="px-5 py-4">
                                            <div className="flex items-center gap-3">
                                                <div className="h-9 w-9 rounded-full flex items-center justify-center font-bold text-white text-sm flex-shrink-0"
                                                    style={{ background: 'linear-gradient(135deg,#38009A,#9E0059)' }}>
                                                    {u.name?.[0]?.toUpperCase()}
                                                </div>
                                                <div>
                                                    <div className="font-semibold text-gray-900">{u.name}</div>
                                                    <div className="text-xs text-gray-400">{u.email}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="px-4 py-4"><RoleBadge role={u.role_type} /></td>
                                        <td className="px-4 py-4"><StatusBadge status={u.status} /></td>
                                        <td className="px-4 py-4 text-xs text-gray-400">
                                            {u.created_at ? new Date(u.created_at).toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' }) : '—'}
                                        </td>
                                        <td className="px-4 py-4 text-right">
                                            <button
                                                onClick={() => toggleStatus(u.id)}
                                                className="text-xs font-medium px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:border-[#9E0059] hover:text-[#9E0059] transition-colors">
                                                {u.status === 'active' ? 'Suspend' : 'Activate'}
                                            </button>
                                        </td>
                                    </tr>
                                )) : (
                                    <tr>
                                        <td colSpan={5} className="px-5 py-12 text-center text-gray-400 text-sm">No users found.</td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination */}
                    {users.last_page > 1 && (
                        <div className="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                            <span className="text-xs text-gray-400">
                                Showing {users.from}–{users.to} of {users.total}
                            </span>
                            <div className="flex gap-2">
                                {users.links?.filter(l => l.url).map((link, i) => (
                                    <Link key={i} href={link.url}
                                        className={`px-3 py-1.5 rounded-lg text-xs font-medium transition-colors ${link.active ? 'text-white' : 'text-gray-600 bg-gray-100 hover:bg-gray-200'}`}
                                        style={link.active ? { background: 'linear-gradient(135deg,#38009A,#9E0059)' } : {}}>
                                        <span dangerouslySetInnerHTML={{ __html: link.label }} />
                                    </Link>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </JobifyLayout>
    );
}
