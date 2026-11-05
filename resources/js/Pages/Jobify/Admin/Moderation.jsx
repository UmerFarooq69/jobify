import JobifyLayout from '../../../Layouts/JobifyLayout';
import { router } from '@inertiajs/react';
import { useState } from 'react';

export default function Moderation({ pendingJobs, pendingOrgs }) {
    const [tab, setTab] = useState('jobs');

    const moderateJob = (id, status) => {
        router.patch(`/platform-admin/jobs/${id}/moderate`, { status }, { preserveScroll: true });
    };
    const moderateOrg = (id, status) => {
        router.patch(`/platform-admin/orgs/${id}/moderate`, { status }, { preserveScroll: true });
    };

    return (
        <JobifyLayout title="Moderation Queue">
            <div className="space-y-4">
                <div className="flex gap-2 border-b border-gray-200 pb-1">
                    <button onClick={() => setTab('jobs')} className={`px-4 py-2 text-sm font-medium rounded-t-lg transition-colors ${tab==='jobs' ? 'bg-white border border-b-white text-indigo-600 -mb-px' : 'text-gray-500 hover:text-gray-700'}`}>
                        Jobs ({pendingJobs?.length ?? 0})
                    </button>
                    <button onClick={() => setTab('orgs')} className={`px-4 py-2 text-sm font-medium rounded-t-lg transition-colors ${tab==='orgs' ? 'bg-white border border-b-white text-indigo-600 -mb-px' : 'text-gray-500 hover:text-gray-700'}`}>
                        Organizations ({pendingOrgs?.length ?? 0})
                    </button>
                </div>

                {tab === 'jobs' && (
                    <div className="space-y-3">
                        {pendingJobs?.length > 0 ? pendingJobs.map(job => (
                            <div key={job.id} className="bg-white rounded-xl border border-gray-200 p-5">
                                <div className="flex items-start justify-between">
                                    <div>
                                        <h3 className="font-semibold text-gray-900">{job.title}</h3>
                                        <p className="text-gray-500 text-sm">{job.organization?.name} · {job.location}</p>
                                        <div className="flex gap-2 mt-2">
                                            <span className="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full capitalize">{job.employment_type?.replace('_',' ')}</span>
                                            {job.salary_min && <span className="text-xs text-gray-500">{job.currency} {job.salary_min?.toLocaleString()}–{job.salary_max?.toLocaleString()}</span>}
                                        </div>
                                    </div>
                                    <div className="flex gap-2">
                                        <button onClick={() => moderateJob(job.id, 'approved')} className="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">Approve</button>
                                        <button onClick={() => moderateJob(job.id, 'flagged')} className="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">Reject</button>
                                    </div>
                                </div>
                                {job.description && <p className="text-gray-600 text-sm mt-3 line-clamp-2">{job.description?.replace(/<[^>]*>/g,'')}</p>}
                            </div>
                        )) : (
                            <div className="bg-white rounded-xl border border-gray-200 p-12 text-center text-gray-500">
                                <div className="text-4xl mb-3">✅</div>No pending jobs to review
                            </div>
                        )}
                    </div>
                )}

                {tab === 'orgs' && (
                    <div className="space-y-3">
                        {pendingOrgs?.length > 0 ? pendingOrgs.map(org => (
                            <div key={org.id} className="bg-white rounded-xl border border-gray-200 p-5">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <h3 className="font-semibold text-gray-900">{org.name}</h3>
                                        <p className="text-gray-500 text-sm">{org.industry} · {org.city}</p>
                                    </div>
                                    <div className="flex gap-2">
                                        <button onClick={() => moderateOrg(org.id, 'approved')} className="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">Approve</button>
                                        <button onClick={() => moderateOrg(org.id, 'flagged')} className="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">Reject</button>
                                    </div>
                                </div>
                            </div>
                        )) : (
                            <div className="bg-white rounded-xl border border-gray-200 p-12 text-center text-gray-500">
                                <div className="text-4xl mb-3">✅</div>No pending organizations
                            </div>
                        )}
                    </div>
                )}
            </div>
        </JobifyLayout>
    );
}
