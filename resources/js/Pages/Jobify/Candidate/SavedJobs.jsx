import JobifyLayout from '../../../Layouts/JobifyLayout';
import { Link, router } from '@inertiajs/react';

export default function SavedJobs({ savedJobs }) {
    const unsave = (id) => router.delete(`/candidate/saved-jobs/${id}`, { preserveScroll: true });

    return (
        <JobifyLayout title="Saved Jobs">
            <div className="space-y-4">
                {savedJobs?.data?.length > 0 ? (
                    savedJobs.data.map(saved => {
                        const job = saved.job;
                        return (
                            <div key={saved.id} className="bg-white rounded-xl border border-gray-200 p-5 flex items-center justify-between">
                                <div className="flex items-center gap-4">
                                    <div className="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center overflow-hidden">
                                        {job?.organization?.logo ? <img src={`/storage/${job.organization.logo}`} alt="" className="w-full h-full object-cover" /> : <span className="font-bold text-gray-400">{job?.organization?.name?.[0]}</span>}
                                    </div>
                                    <div>
                                        <Link href={`/find-jobs/${job?.uuid}`} className="font-semibold text-gray-900 hover:text-indigo-600 transition-colors">{job?.title}</Link>
                                        <p className="text-gray-500 text-sm">{job?.organization?.name}</p>
                                        <div className="flex items-center gap-2 mt-1">
                                            <span className="text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full">{job?.employment_type?.replace('_',' ')}</span>
                                            {job?.location && <span className="text-xs text-gray-500">📍 {job.location}</span>}
                                        </div>
                                    </div>
                                </div>
                                <div className="flex items-center gap-3">
                                    <Link href={`/find-jobs/${job?.uuid}`} className="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">Apply</Link>
                                    <button onClick={() => unsave(saved.id)} className="text-gray-400 hover:text-red-500 text-sm px-3 py-2 rounded-lg hover:bg-red-50 transition-colors">Remove</button>
                                </div>
                            </div>
                        );
                    })
                ) : (
                    <div className="bg-white rounded-xl border border-gray-200 p-12 text-center">
                        <div className="text-4xl mb-3">🔖</div>
                        <h3 className="font-semibold text-gray-900 mb-1">No saved jobs</h3>
                        <p className="text-gray-500 text-sm mb-4">Save jobs to review them later</p>
                        <Link href="/find-jobs" className="bg-indigo-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">Browse Jobs</Link>
                    </div>
                )}
            </div>
        </JobifyLayout>
    );
}
