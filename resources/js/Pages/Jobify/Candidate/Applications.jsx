import JobifyLayout from '../../../Layouts/JobifyLayout';
import { Link } from '@inertiajs/react';

const statusColors = {
    applied: 'bg-blue-50 text-blue-700',
    screening: 'bg-yellow-50 text-yellow-700',
    interview: 'bg-purple-50 text-purple-700',
    offer: 'bg-green-50 text-green-700',
    rejected: 'bg-red-50 text-red-700',
    withdrawn: 'bg-gray-100 text-gray-600',
};

export default function Applications({ applications }) {
    return (
        <JobifyLayout title="My Applications">
            <div className="space-y-4">
                {applications?.data?.length > 0 ? (
                    applications.data.map(app => (
                        <div key={app.id} className="bg-white rounded-xl border border-gray-200 p-5">
                            <div className="flex items-start justify-between">
                                <div className="flex items-center gap-4">
                                    <div className="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center overflow-hidden">
                                        {app.job?.organization?.logo ? <img src={`/storage/${app.job.organization.logo}`} alt="" className="w-full h-full object-cover" /> : <span className="font-bold text-gray-400">{app.job?.organization?.name?.[0]}</span>}
                                    </div>
                                    <div>
                                        <h3 className="font-semibold text-gray-900">{app.job?.title}</h3>
                                        <p className="text-gray-500 text-sm">{app.job?.organization?.name}</p>
                                        <p className="text-gray-400 text-xs mt-0.5">Applied {new Date(app.applied_at).toLocaleDateString()}</p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-3 flex-shrink-0">
                                    {app.match_score && <span className="text-indigo-600 text-sm font-bold">{app.match_score}%</span>}
                                    <span className={`text-xs font-medium px-3 py-1 rounded-full capitalize ${statusColors[app.status] ?? 'bg-gray-100 text-gray-600'}`}>{app.status}</span>
                                </div>
                            </div>
                            {app.stage && (
                                <div className="mt-3 text-xs text-gray-500">Pipeline stage: <span className="font-medium text-gray-700">{app.stage.name}</span></div>
                            )}
                        </div>
                    ))
                ) : (
                    <div className="bg-white rounded-xl border border-gray-200 p-12 text-center">
                        <div className="text-4xl mb-3">📋</div>
                        <h3 className="font-semibold text-gray-900 mb-1">No applications yet</h3>
                        <p className="text-gray-500 text-sm mb-4">Start applying to jobs to track your applications here</p>
                        <Link href="/find-jobs" className="bg-indigo-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">Browse Jobs</Link>
                    </div>
                )}
            </div>
        </JobifyLayout>
    );
}
