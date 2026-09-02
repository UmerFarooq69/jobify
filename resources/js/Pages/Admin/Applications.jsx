import AdminLayout from '../../Layouts/AdminLayout';
import { router } from '@inertiajs/react';
import Icon from '@mdi/react';
import { mdiMagnify, mdiDelete, mdiBriefcase, mdiOfficeBuilding, mdiAccount, mdiCalendar } from '@mdi/js';
import { useState } from 'react';

export default function Applications({ applications = [], companies = [] }) {
    const [search, setSearch] = useState('');
    const [companyFilter, setCompanyFilter] = useState('');

    const filtered = applications.filter(a => {
        const matchSearch =
            a.job?.job_title?.toLowerCase().includes(search.toLowerCase()) ||
            a.job?.company?.name?.toLowerCase().includes(search.toLowerCase());
        const matchCompany = !companyFilter || a.job?.company?.id == companyFilter;
        return matchSearch && matchCompany;
    });

    function deleteApplication(id) {
        window.Swal?.fire({
            title: 'Delete application?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
        }).then(r => {
            if (r.isConfirmed) router.delete(`/applications/${id}`);
        });
    }

    return (
        <AdminLayout title="Applications">
            <div className="space-y-5">
                <div className="flex items-center gap-3 flex-wrap">
                    <div className="relative flex-1 min-w-[200px] max-w-sm">
                        <Icon path={mdiMagnify} size={0.75} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                        <input
                            type="text"
                            placeholder="Search applications…"
                            value={search}
                            onChange={e => setSearch(e.target.value)}
                            className="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        />
                    </div>
                    <select
                        value={companyFilter}
                        onChange={e => setCompanyFilter(e.target.value)}
                        className="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 text-gray-700"
                    >
                        <option value="">All Companies</option>
                        {companies.map(c => (
                            <option key={c.id} value={c.id}>{c.name}</option>
                        ))}
                    </select>
                    <span className="text-sm text-gray-500">{filtered.length} application{filtered.length !== 1 ? 's' : ''}</span>
                </div>

                <div className="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="border-b border-gray-100 bg-gray-50">
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Applicant</th>
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Job</th>
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Company</th>
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Date</th>
                                <th className="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-50">
                            {filtered.length === 0 ? (
                                <tr>
                                    <td colSpan={5} className="text-center py-12 text-gray-400 text-sm">No applications found</td>
                                </tr>
                            ) : filtered.map(app => (
                                <tr key={app.id} className="hover:bg-gray-50 transition-colors">
                                    <td className="px-4 py-3">
                                        <span className="flex items-center gap-2">
                                            <Icon path={mdiAccount} size={0.65} className="text-gray-400" />
                                            <span className="font-medium text-gray-900">{app.name ?? '—'}</span>
                                        </span>
                                    </td>
                                    <td className="px-4 py-3 text-gray-600">
                                        <span className="flex items-center gap-1.5">
                                            <Icon path={mdiBriefcase} size={0.6} className="text-gray-400" />
                                            {app.job?.job_title ?? '—'}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3 text-gray-600">
                                        <span className="flex items-center gap-1.5">
                                            <Icon path={mdiOfficeBuilding} size={0.6} className="text-gray-400" />
                                            {app.job?.company?.name ?? '—'}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3 text-gray-500 text-xs">
                                        <span className="flex items-center gap-1">
                                            <Icon path={mdiCalendar} size={0.6} className="text-gray-400" />
                                            {app.created_at ? new Date(app.created_at).toLocaleDateString() : '—'}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3 text-right">
                                        <button
                                            onClick={() => deleteApplication(app.id)}
                                            className="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded transition-colors"
                                        >
                                            <Icon path={mdiDelete} size={0.6} />
                                            Delete
                                        </button>
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
