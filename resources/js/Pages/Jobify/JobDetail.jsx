import JobifyPublicLayout from '../../Layouts/JobifyPublicLayout';
import { Link, router, usePage } from '@inertiajs/react';

export default function JobDetail({ job, related, hasApplied, isSaved }) {
    const { props } = usePage();
    const user = props.auth?.user;

    const handleApply = () => {
        if (!user) { window.location.href = '/login'; return; }
        router.post(`/find-jobs/${job.uuid}/apply`);
    };

    return (
        <JobifyPublicLayout>
            <div className="max-w-5xl mx-auto px-4 py-8">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Main content */}
                    <div className="lg:col-span-2 space-y-6">
                        {/* Header */}
                        <div className="bg-white rounded-xl border border-gray-200 p-6">
                            <div className="flex items-start gap-4">
                                <div className="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                    {job.organization?.logo ? <img src={`/storage/${job.organization.logo}`} alt="" className="w-full h-full object-cover" /> : <span className="text-2xl font-bold text-gray-400">{job.organization?.name?.[0]}</span>}
                                </div>
                                <div className="flex-1">
                                    <h1 className="text-xl font-bold text-gray-900">{job.title}</h1>
                                    <p className="text-gray-500">{job.organization?.name}</p>
                                    <div className="flex flex-wrap gap-2 mt-3">
                                        <span className="bg-indigo-50 text-indigo-700 text-xs px-3 py-1 rounded-full font-medium">{job.employment_type?.replace('_',' ')}</span>
                                        {job.location && <span className="bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded-full">📍 {job.location}</span>}
                                        {job.visa_sponsorship && <span className="bg-green-50 text-green-700 text-xs px-3 py-1 rounded-full">Visa Sponsorship</span>}
                                        {job.urgent_hire && <span className="bg-red-50 text-red-600 text-xs px-3 py-1 rounded-full">Urgent</span>}
                                    </div>
                                    {(job.salary_min || job.salary_max) && (
                                        <p className="text-gray-700 font-semibold mt-2">{job.currency} {job.salary_min?.toLocaleString()} – {job.salary_max?.toLocaleString()}</p>
                                    )}
                                </div>
                            </div>

                            <div className="flex gap-3 mt-5">
                                {hasApplied ? (
                                    <span className="bg-green-50 text-green-700 font-medium px-6 py-2.5 rounded-lg text-sm">✓ Applied</span>
                                ) : (
                                    <button onClick={handleApply} className="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2.5 rounded-lg text-sm transition-colors">
                                        Apply Now
                                    </button>
                                )}
                                <button className="border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium px-6 py-2.5 rounded-lg text-sm transition-colors">
                                    {isSaved ? '♥ Saved' : '♡ Save'}
                                </button>
                            </div>
                        </div>

                        {/* Description */}
                        {job.description && (
                            <div className="bg-white rounded-xl border border-gray-200 p-6">
                                <h2 className="font-bold text-gray-900 mb-3">Job Description</h2>
                                <div className="prose prose-sm max-w-none text-gray-700" dangerouslySetInnerHTML={{ __html: job.description }} />
                            </div>
                        )}

                        {/* Requirements */}
                        {job.requirements && (
                            <div className="bg-white rounded-xl border border-gray-200 p-6">
                                <h2 className="font-bold text-gray-900 mb-3">Requirements</h2>
                                <div className="prose prose-sm max-w-none text-gray-700" dangerouslySetInnerHTML={{ __html: job.requirements }} />
                            </div>
                        )}

                        {/* Skills */}
                        {job.skills?.length > 0 && (
                            <div className="bg-white rounded-xl border border-gray-200 p-6">
                                <h2 className="font-bold text-gray-900 mb-3">Required Skills</h2>
                                <div className="flex flex-wrap gap-2">
                                    {job.skills.map((s, i) => <span key={i} className="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full">{s}</span>)}
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Sidebar */}
                    <div className="space-y-4">
                        <div className="bg-white rounded-xl border border-gray-200 p-5">
                            <h3 className="font-semibold text-gray-900 mb-4">Job Details</h3>
                            <dl className="space-y-3 text-sm">
                                {job.department && <><dt className="text-gray-500">Department</dt><dd className="font-medium">{job.department?.name}</dd></>}
                                <dt className="text-gray-500">Type</dt><dd className="font-medium capitalize">{job.employment_type?.replace('_',' ')}</dd>
                                {job.location && <><dt className="text-gray-500">Location</dt><dd className="font-medium">{job.location}</dd></>}
                                {job.expires_at && <><dt className="text-gray-500">Deadline</dt><dd className="font-medium">{new Date(job.expires_at).toLocaleDateString()}</dd></>}
                            </dl>
                        </div>

                        {/* Related Jobs */}
                        {related?.length > 0 && (
                            <div className="bg-white rounded-xl border border-gray-200 p-5">
                                <h3 className="font-semibold text-gray-900 mb-4">Similar Jobs</h3>
                                <div className="space-y-3">
                                    {related.map(j => (
                                        <Link key={j.id} href={`/find-jobs/${j.uuid}`} className="block hover:bg-gray-50 -mx-2 px-2 py-2 rounded-lg transition-colors">
                                            <div className="font-medium text-sm text-gray-900">{j.title}</div>
                                            <div className="text-xs text-gray-500">{j.organization?.name}</div>
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </JobifyPublicLayout>
    );
}
