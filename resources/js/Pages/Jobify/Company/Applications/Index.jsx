import JobifyLayout from '../../../../Layouts/JobifyLayout';
import { Link, router } from '@inertiajs/react';
import { useState, useRef, useEffect } from 'react';

function stageDot(name) {
    const n = (name ?? '').toLowerCase();
    if (n.includes('applied'))   return '#F97316';
    if (n.includes('screen'))    return '#6366F1';
    if (n.includes('interview')) return '#7C3AED';
    if (n.includes('offer'))     return '#EAB308';
    if (n.includes('hired'))     return '#16A34A';
    if (n.includes('reject'))    return '#DC2626';
    return '#9CA3AF';
}
function stageBadge(name) {
    const n = (name ?? '').toLowerCase();
    if (n.includes('applied'))   return 'bg-orange-100 text-orange-700';
    if (n.includes('screen'))    return 'bg-indigo-100 text-indigo-700';
    if (n.includes('interview')) return 'bg-purple-100 text-purple-700';
    if (n.includes('offer'))     return 'bg-yellow-100 text-yellow-700';
    if (n.includes('hired'))     return 'bg-green-100 text-green-700';
    if (n.includes('reject'))    return 'bg-red-100 text-red-700';
    return 'bg-gray-100 text-gray-600';
}

const PALETTE = ['#9E0059','#38009A','#0D9488','#F97316','#6366F1','#059669'];
function avatarColor(str) {
    let h = 0;
    for (let i = 0; i < str.length; i++) h = (h * 31 + str.charCodeAt(i)) & 0xffff;
    return PALETTE[h % PALETTE.length];
}
function initials(app) {
    const u = app.candidate?.user ?? {};
    const name = [u.first_name ?? u.name, u.last_name].filter(Boolean).join(' ') || 'U';
    return name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
}
function candidateName(app) {
    const u = app.candidate?.user ?? {};
    return [u.first_name ?? u.name, u.last_name].filter(Boolean).join(' ') || 'Unknown';
}
function shortDate(val) {
    if (!val) return '—';
    return new Date(val).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
}
function scoreColor(score) {
    if (score == null) return null;
    if (score >= 70) return 'bg-green-100 text-green-700';
    if (score >= 40) return 'bg-yellow-100 text-yellow-700';
    return 'bg-red-100 text-red-600';
}

export default function CompanyApplications({ jobs, selectedJob, stages, stageCounts, applications, filters }) {
    const [search, setSearch] = useState(filters?.search ?? '');
    const [jobOpen, setJobOpen] = useState(false);
    const jobRef = useRef(null);

    useEffect(() => {
        const h = e => { if (jobRef.current && !jobRef.current.contains(e.target)) setJobOpen(false); };
        document.addEventListener('click', h);
        return () => document.removeEventListener('click', h);
    }, []);

    const items = applications?.data ?? [];
    const totalCount = stageCounts?.all ?? 0;

    const tabs = [{ id: 'all', name: 'All', count: stageCounts?.all ?? 0, dot: '#9CA3AF' }];
    if ((stageCounts?.unassigned ?? 0) > 0 || !stages?.length) {
        tabs.push({ id: 'unassigned', name: 'Applied', count: stageCounts?.unassigned ?? 0, dot: '#F97316' });
    }
    stages?.forEach(s => tabs.push({ id: s.id, name: s.name, count: stageCounts?.[s.id] ?? 0, dot: stageDot(s.name) }));

    const activeStage = (() => {
        const f = filters?.stage;
        if (!f || f === 'all') return 'all';
        return isNaN(f) ? f : parseInt(f);
    })();

    function switchJob(uuid) {
        setJobOpen(false);
        router.get('/employer/applications', { job: uuid ?? 'all', stage: 'all', search: '' });
    }
    function switchStage(stageId) {
        router.get('/employer/applications', { job: filters?.job, stage: stageId, search }, { preserveScroll: true });
    }
    function doSearch(e) {
        e.preventDefault();
        router.get('/employer/applications', { job: filters?.job, stage: filters?.stage, search }, { preserveScroll: true });
    }

    function appliedStageName(app) {
        if (!app.pipeline_stage_id) return 'Applied';
        const s = stages?.find(s => s.id === app.pipeline_stage_id);
        return s?.name ?? 'Applied';
    }

    return (
        <JobifyLayout title="Applications">
            <div className="space-y-4 max-w-6xl">

                {/* Toolbar */}
                <div className="flex flex-wrap items-center gap-2">
                    {/* Job selector */}
                    <div className="relative" ref={jobRef}>
                        <button onClick={() => setJobOpen(o => !o)}
                            className="flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 shadow-sm hover:bg-gray-50 transition">
                            {selectedJob?.title ?? 'All Jobs'}
                            <svg className={`h-4 w-4 text-gray-400 transition-transform ${jobOpen ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        {jobOpen && (
                            <div className="absolute left-0 top-full z-30 mt-1 w-80 rounded-xl border border-gray-100 bg-white shadow-xl overflow-hidden">
                                <button onClick={() => switchJob('all')}
                                    className={`flex w-full items-center justify-between px-4 py-3 text-left text-sm hover:bg-gray-50 transition border-b border-gray-50 ${!filters?.job || filters?.job === 'all' ? 'bg-indigo-50 text-[#38009A] font-semibold' : 'text-gray-800'}`}>
                                    <span>All Jobs</span>
                                    <span className="ml-3 shrink-0 rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-500">{jobs?.reduce((s, j) => s + (j.count ?? 0), 0)}</span>
                                </button>
                                {jobs?.map(j => (
                                    <button key={j.uuid} onClick={() => switchJob(j.uuid)}
                                        className={`flex w-full items-center justify-between px-4 py-3 text-left text-sm hover:bg-gray-50 transition ${j.uuid === filters?.job ? 'bg-indigo-50 text-[#38009A] font-semibold' : 'text-gray-800'}`}>
                                        <span className="truncate">{j.title}</span>
                                        <span className="ml-3 shrink-0 rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-500">{j.count ?? 0}</span>
                                    </button>
                                ))}
                            </div>
                        )}
                    </div>

                    {/* Search */}
                    <form onSubmit={doSearch} className="relative flex-1 min-w-48">
                        <input value={search} onChange={e => setSearch(e.target.value)}
                            placeholder="Search candidates by name or email…"
                            className="w-full rounded-xl border border-gray-200 bg-white pl-4 pr-9 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:border-[#38009A] focus:outline-none" />
                        <button type="submit" className="absolute right-3 top-1/2 -translate-y-1/2">
                            <svg className="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                    </form>
                </div>

                {!jobs?.length ? (
                    <div className="rounded-2xl bg-white py-20 text-center text-sm text-gray-400 shadow-sm ring-1 ring-gray-100">
                        No jobs posted yet.{' '}
                        <Link href="/employer/jobs/create" className="text-[#9E0059] font-semibold hover:underline">Post a job →</Link>
                    </div>
                ) : (
                    <>
                        {selectedJob && (
                            <div className="text-xs text-gray-400 flex flex-wrap gap-x-3 gap-y-1 items-center">
                                <span>{totalCount} candidate{totalCount !== 1 ? 's' : ''}</span>
                                {selectedJob.employment_type && <><span>·</span><span className="capitalize">{selectedJob.employment_type.replace('_', ' ')}</span></>}
                                {selectedJob.location && <><span>·</span><span>{selectedJob.location}</span></>}
                            </div>
                        )}

                        <div className="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                            {/* Stage tabs */}
                            <div className="border-b border-gray-100 px-5 py-3 flex flex-wrap items-center gap-1.5">
                                <span className="shrink-0 text-xs font-semibold text-gray-500 mr-2">
                                    {selectedJob
                                        ? `Hiring pipeline · ${totalCount} candidate${totalCount !== 1 ? 's' : ''}`
                                        : `All applications · ${totalCount}`}
                                </span>
                                {selectedJob && tabs.map(tab => (
                                    <button key={tab.id} onClick={() => switchStage(tab.id)}
                                        className={`flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium transition whitespace-nowrap ${activeStage === tab.id ? 'bg-[#38009A] text-white' : 'text-gray-600 hover:bg-gray-100'}`}>
                                        <span className="h-1.5 w-1.5 rounded-full shrink-0" style={{ background: tab.dot }} />
                                        {tab.name} {tab.count}
                                    </button>
                                ))}
                            </div>

                            {items.length === 0 ? (
                                <div className="py-16 text-center text-sm text-gray-400">No candidates match this filter.</div>
                            ) : (
                                <div className="overflow-x-auto">
                                    <table className="w-full text-sm">
                                        <thead>
                                            <tr className="border-b border-gray-100 bg-gray-50/60 text-left">
                                                <th className="px-4 py-3 text-xs font-semibold text-gray-500">Candidate</th>
                                                <th className="px-4 py-3 text-xs font-semibold text-gray-500">Applied</th>
                                                <th className="px-4 py-3 text-xs font-semibold text-gray-500">CV Score</th>
                                                <th className="px-4 py-3 text-xs font-semibold text-gray-500">Stage</th>
                                                <th className="px-4 py-3" />
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-gray-50">
                                            {items.map(app => (
                                                <tr key={app.id} className="hover:bg-gray-50/60 transition">
                                                    <td className="px-4 py-3">
                                                        <div className="flex items-center gap-3">
                                                            <div className="h-9 w-9 shrink-0 rounded-full flex items-center justify-center text-[11px] font-bold text-white"
                                                                style={{ background: avatarColor(initials(app)) }}>
                                                                {initials(app)}
                                                            </div>
                                                            <div>
                                                                <p className="font-semibold text-gray-900 text-sm leading-tight">{candidateName(app)}</p>
                                                                <p className="text-xs text-gray-400 mt-0.5">{app.candidate?.user?.email}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td className="px-4 py-3 text-sm text-gray-600">{shortDate(app.applied_at)}</td>
                                                    <td className="px-4 py-3">
                                                        {app.match_score != null ? (
                                                            <span className={`inline-flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold ${scoreColor(app.match_score)}`}>
                                                                {app.match_score}
                                                            </span>
                                                        ) : <span className="text-gray-300">—</span>}
                                                    </td>
                                                    <td className="px-4 py-3">
                                                        <span className={`rounded-full px-2.5 py-0.5 text-xs font-semibold ${stageBadge(appliedStageName(app))}`}>
                                                            {appliedStageName(app)}
                                                        </span>
                                                    </td>
                                                    <td className="px-4 py-3">
                                                        <Link href={`/employer/applications/${app.id}`}
                                                            className="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                                                            View
                                                        </Link>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            )}

                            <div className="border-t border-gray-100 px-5 py-3 text-xs text-gray-500 flex items-center justify-between">
                                <span>Showing {items.length} of {applications?.total ?? 0}</span>
                                {(applications?.last_page ?? 1) > 1 && (
                                    <div className="flex gap-2">
                                        {applications.prev_page_url && <Link href={applications.prev_page_url} className="rounded-lg border border-gray-200 px-3 py-1.5 hover:bg-gray-50">Prev</Link>}
                                        {applications.next_page_url && <Link href={applications.next_page_url} className="rounded-lg border border-gray-200 px-3 py-1.5 hover:bg-gray-50">Next</Link>}
                                    </div>
                                )}
                            </div>
                        </div>
                    </>
                )}
            </div>
        </JobifyLayout>
    );
}
