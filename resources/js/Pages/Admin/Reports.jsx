import AdminLayout from '../../Layouts/AdminLayout';
import { router } from '@inertiajs/react';
import Icon from '@mdi/react';
import { mdiMagnify, mdiDelete, mdiAlertCircle, mdiAccount, mdiEmail, mdiOfficeBuilding, mdiBriefcase, mdiImage } from '@mdi/js';
import { useState } from 'react';

export default function Reports({ problems = [] }) {
    const [search, setSearch] = useState('');

    const filtered = problems.filter(p =>
        p.name?.toLowerCase().includes(search.toLowerCase()) ||
        p.problem?.toLowerCase().includes(search.toLowerCase()) ||
        p.report_type?.toLowerCase().includes(search.toLowerCase())
    );

    function deleteProblem(id) {
        window.Swal?.fire({
            title: 'Delete this report?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
        }).then(r => {
            if (r.isConfirmed) router.delete(`/problems/${id}`);
        });
    }

    const typeBadge = (type) => {
        const map = {
            'job':     'bg-blue-50 text-blue-700 ring-blue-100',
            'company': 'bg-purple-50 text-purple-700 ring-purple-100',
        };
        return map[type?.toLowerCase()] ?? 'bg-gray-100 text-gray-600 ring-gray-200';
    };

    return (
        <AdminLayout title="Problem Reports">
            <div className="space-y-5">
                <div className="flex items-center justify-between gap-4">
                    <div className="relative flex-1 max-w-sm">
                        <Icon path={mdiMagnify} size={0.75} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                        <input
                            type="text"
                            placeholder="Search reports…"
                            value={search}
                            onChange={e => setSearch(e.target.value)}
                            className="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        />
                    </div>
                    <span className="text-sm text-gray-500">{filtered.length} report{filtered.length !== 1 ? 's' : ''}</span>
                </div>

                <div className="space-y-3">
                    {filtered.length === 0 ? (
                        <div className="text-center py-16 text-gray-400 text-sm bg-white rounded-xl border border-gray-200">
                            No problem reports found
                        </div>
                    ) : filtered.map(problem => (
                        <div key={problem.id} className="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                            <div className="flex items-start justify-between gap-4">
                                <div className="flex-1">
                                    <div className="flex items-center gap-3 flex-wrap mb-3">
                                        <div className="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                                            <Icon path={mdiAlertCircle} size={0.7} className="text-red-600" />
                                        </div>
                                        <span className="font-semibold text-gray-900 text-sm">{problem.name}</span>
                                        <span className="flex items-center gap-1 text-xs text-gray-500">
                                            <Icon path={mdiEmail} size={0.55} />
                                            {problem.email}
                                        </span>
                                        <span className={`inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ${typeBadge(problem.report_type)}`}>
                                            {problem.report_type}
                                        </span>
                                    </div>

                                    {(problem.company || problem.job) && (
                                        <div className="flex items-center gap-4 mb-2 pl-11 text-xs text-gray-500">
                                            {problem.company && (
                                                <span className="flex items-center gap-1">
                                                    <Icon path={mdiOfficeBuilding} size={0.55} />
                                                    {problem.company.name}
                                                </span>
                                            )}
                                            {problem.job && (
                                                <span className="flex items-center gap-1">
                                                    <Icon path={mdiBriefcase} size={0.55} />
                                                    {problem.job.job_title}
                                                </span>
                                            )}
                                        </div>
                                    )}

                                    <p className="text-sm text-gray-600 pl-11 leading-relaxed">{problem.problem}</p>

                                    {problem.image && (
                                        <div className="mt-3 pl-11">
                                            <a href={`/storage/${problem.image}`} target="_blank" rel="noreferrer" className="inline-flex items-center gap-1 text-xs text-blue-600 hover:underline">
                                                <Icon path={mdiImage} size={0.6} />
                                                View Attachment
                                            </a>
                                        </div>
                                    )}

                                    <p className="text-xs text-gray-400 pl-11 mt-2">
                                        {problem.created_at ? new Date(problem.created_at).toLocaleString() : ''}
                                    </p>
                                </div>
                                <button
                                    onClick={() => deleteProblem(problem.id)}
                                    className="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded transition-colors flex-shrink-0"
                                >
                                    <Icon path={mdiDelete} size={0.6} />
                                    Delete
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </AdminLayout>
    );
}
