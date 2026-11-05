import JobifyLayout from '../../../../Layouts/JobifyLayout';
import { Link, router } from '@inertiajs/react';

export default function CompanyJobs({ org, jobs }) {
    const publish = (id) => router.patch(`/employer/jobs/${id}/publish`, {}, { preserveScroll: true });
    const destroy = (id) => { if (confirm('Delete this job?')) router.delete(`/employer/jobs/${id}`); };

    return (
        <JobifyLayout title="Manage Jobs">
            <div className="space-y-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h2 className="text-lg font-semibold text-gray-900">Job Postings</h2>
                        <p className="text-gray-500 text-sm">{jobs?.total ?? 0} total</p>
                    </div>
                    <Link href="/employer/jobs/create" className="bg-indigo-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors flex items-center gap-2">
                        + Post New Job
                    </Link>
                </div>

                <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    {jobs?.data?.length > 0 ? (
                        <table className="w-full">
                            <thead className="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th className="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Job</th>
                                    <th className="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Type</th>
                                    <th className="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Applications</th>
                                    <th className="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                                    <th className="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {jobs.data.map(job => (
                                    <tr key={job.id} className="hover:bg-gray-50 transition-colors">
                                        <td className="px-5 py-4">
                                            <Link href={`/employer/jobs/${job.id}`} className="font-medium text-gray-900 hover:text-indigo-600 text-sm">{job.title}</Link>
                                            <div className="text-xs text-gray-500">{job.location}</div>
                                        </td>
                                        <td className="px-5 py-4 text-sm text-gray-600 capitalize">{job.employment_type?.replace('_',' ')}</td>
                                        <td className="px-5 py-4 text-center">
                                            <Link href={`/employer/applications?job_id=${job.id}`} className="text-sm font-medium text-indigo-600 hover:text-indigo-700">{job.applications_count}</Link>
                                        </td>
                                        <td className="px-5 py-4 text-center">
                                            <span className={`text-xs px-2 py-1 rounded-full font-medium ${job.status === 'active' ? 'bg-green-50 text-green-700' : job.status === 'draft' ? 'bg-gray-100 text-gray-600' : 'bg-amber-50 text-amber-700'}`}>{job.status}</span>
                                        </td>
                                        <td className="px-5 py-4 text-right">
                                            <div className="flex items-center justify-end gap-2">
                                                <button onClick={() => publish(job.id)} className="text-xs text-gray-500 hover:text-indigo-600 px-2 py-1 rounded hover:bg-indigo-50 transition-colors">{job.status === 'active' ? 'Pause' : 'Publish'}</button>
                                                <Link href={`/employer/jobs/${job.id}/edit`} className="text-xs text-gray-500 hover:text-indigo-600 px-2 py-1 rounded hover:bg-indigo-50 transition-colors">Edit</Link>
                                                <button onClick={() => destroy(job.id)} className="text-xs text-gray-500 hover:text-red-600 px-2 py-1 rounded hover:bg-red-50 transition-colors">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    ) : (
                        <div className="p-12 text-center">
                            <div className="text-4xl mb-3">💼</div>
                            <h3 className="font-semibold text-gray-900 mb-1">No jobs yet</h3>
                            <p className="text-gray-500 text-sm mb-4">Post your first job to start receiving applications</p>
                            <Link href="/employer/jobs/create" className="bg-indigo-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">Post a Job</Link>
                        </div>
                    )}
                </div>
            </div>
        </JobifyLayout>
    );
}
