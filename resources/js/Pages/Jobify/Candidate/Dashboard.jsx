import JobifyLayout from '../../../Layouts/JobifyLayout';
import { Link } from '@inertiajs/react';
import { ClipboardList, Mic, Bookmark, Sparkles, FileText, Search } from 'lucide-react';

const statusConfig = {
    applied:   { label: 'Applied',   bg: '#EBE6F5', color: '#38009A' },
    screening: { label: 'Screening', bg: '#FFF3CD', color: '#856404' },
    interview: { label: 'Interview', bg: '#D1FAE5', color: '#065F46' },
    offer:     { label: 'Offer',     bg: '#D1FAE5', color: '#065F46' },
    rejected:  { label: 'Rejected',  bg: '#FEE2E2', color: '#991B1B' },
    withdrawn: { label: 'Withdrawn', bg: '#F3F4F6', color: '#6B7280' },
};

function StatCard({ label, value, sub, icon, gradient }) {
    return (
        <div className="bg-white rounded-2xl p-5 shadow-sm ring-1 ring-gray-100 flex items-center gap-4">
            <div className="h-12 w-12 rounded-xl flex items-center justify-center flex-shrink-0" style={{ background: gradient }}>
                {icon}
            </div>
            <div>
                <div className="text-2xl font-bold text-[#1F0055]">{value ?? 0}</div>
                <div className="text-sm text-gray-500">{label}</div>
                {sub && <div className="text-xs text-gray-400 mt-0.5">{sub}</div>}
            </div>
        </div>
    );
}

export default function CandidateDashboard({ profile, recentApplications, stats, recommendations }) {
    const score = profile?.completeness_score ?? 0;

    return (
        <JobifyLayout title="Dashboard">
            <div className="space-y-5 max-w-5xl">

                {/* Welcome banner */}
                <div className="rounded-2xl p-6 text-white relative overflow-hidden" style={{ background: 'linear-gradient(135deg, #38009A 0%, #9E0059 100%)' }}>
                    <div className="relative z-10">
                        <h1 className="text-xl font-bold mb-1">Welcome back! 👋</h1>
                        <p className="text-white/70 text-sm">
                            {score < 80
                                ? `Your profile is ${score}% complete. A complete profile gets 5× more visibility.`
                                : 'Your profile looks great. Keep applying to find your next role.'}
                        </p>
                        {score < 80 && (
                            <Link href="/candidate/profile" className="mt-4 inline-flex items-center gap-2 rounded-full bg-white px-5 py-2 text-sm font-semibold text-[#9E0059] hover:bg-white/90 transition">
                                Complete Profile →
                            </Link>
                        )}
                    </div>
                    {/* decorative circles */}
                    <div className="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/10" />
                    <div className="absolute -right-4 -bottom-10 h-48 w-48 rounded-full bg-white/5" />
                </div>

                {/* Stats */}
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <StatCard label="Total Applications" value={stats?.applications} icon={<ClipboardList size={22} color="#38009A" />} gradient="linear-gradient(135deg,#EBE6F5,#D6C9F0)" />
                    <StatCard label="Interview Invites"  value={stats?.interviews}   icon={<Mic size={22} color="#065F46" />}          gradient="linear-gradient(135deg,#D1FAE5,#A7F3D0)" />
                    <StatCard label="Saved Jobs"         value={stats?.saved_jobs}   icon={<Bookmark size={22} color="#92400E" />}     gradient="linear-gradient(135deg,#FEF3C7,#FDE68A)" />
                </div>

                {/* AI Tools */}
                <div>
                    <h2 className="text-base font-bold text-[#1F0055] mb-3">AI Tools</h2>
                    <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <Link href="/candidate/skill-gap" className="group bg-white rounded-2xl p-5 shadow-sm ring-1 ring-gray-100 hover:ring-[#9E0059]/30 hover:shadow-md transition-all">
                            <div className="h-10 w-10 rounded-xl flex items-center justify-center mb-3" style={{ background: '#FFF8E6' }}><Sparkles size={20} color="#D97706" /></div>
                            <div className="font-semibold text-sm text-[#1F0055] group-hover:text-[#9E0059] transition-colors">Skill Gap Analysis</div>
                            <div className="text-xs text-gray-400 mt-1">Identify & bridge skill gaps with AI</div>
                        </Link>
                        <Link href="/candidate/interview-prep" className="group bg-white rounded-2xl p-5 shadow-sm ring-1 ring-gray-100 hover:ring-[#9E0059]/30 hover:shadow-md transition-all">
                            <div className="h-10 w-10 rounded-xl flex items-center justify-center mb-3" style={{ background: '#EBE6F5' }}><Mic size={20} color="#38009A" /></div>
                            <div className="font-semibold text-sm text-[#1F0055] group-hover:text-[#9E0059] transition-colors">Interview Prep</div>
                            <div className="text-xs text-gray-400 mt-1">Tailored questions for any role</div>
                        </Link>
                        <Link href="/candidate/resume-generator" className="group bg-white rounded-2xl p-5 shadow-sm ring-1 ring-gray-100 hover:ring-[#9E0059]/30 hover:shadow-md transition-all">
                            <div className="h-10 w-10 rounded-xl flex items-center justify-center mb-3" style={{ background: '#D1FAE5' }}><FileText size={20} color="#065F46" /></div>
                            <div className="font-semibold text-sm text-[#1F0055] group-hover:text-[#9E0059] transition-colors">Resume Generator</div>
                            <div className="text-xs text-gray-400 mt-1">AI-optimised resume for any role</div>
                        </Link>
                    </div>
                </div>

                {/* Recent Applications */}
                <div className="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 overflow-hidden">
                    <div className="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <h2 className="font-bold text-[#1F0055]">Recent Applications</h2>
                        <Link href="/candidate/applications" className="text-sm font-medium text-[#9E0059] hover:underline">View all</Link>
                    </div>
                    {recentApplications?.length > 0 ? (
                        <div className="divide-y divide-gray-50">
                            {recentApplications.map(app => {
                                const sc = statusConfig[app.status] ?? statusConfig.applied;
                                return (
                                    <div key={app.id} className="flex items-center gap-4 px-5 py-4 hover:bg-gray-50/50 transition-colors">
                                        <div className="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                            {app.job?.organization?.logo
                                                ? <img src={`/storage/${app.job.organization.logo}`} alt="" className="w-full h-full object-cover" />
                                                : <span className="font-bold text-gray-400 text-sm">{app.job?.organization?.name?.[0]}</span>}
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <div className="font-semibold text-sm text-gray-900 truncate">{app.job?.title}</div>
                                            <div className="text-xs text-gray-400">{app.job?.organization?.name} · {app.job?.location}</div>
                                        </div>
                                        <div className="flex items-center gap-3 flex-shrink-0">
                                            {app.match_score && (
                                                <span className="text-xs font-bold" style={{ color: '#9E0059' }}>{app.match_score}% match</span>
                                            )}
                                            <span className="text-xs font-semibold px-3 py-1 rounded-full" style={{ background: sc.bg, color: sc.color }}>{sc.label}</span>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    ) : (
                        <div className="px-5 py-12 text-center">
                            <div className="flex justify-center mb-3"><Search size={36} className="text-gray-300" /></div>
                            <p className="text-gray-500 text-sm">No applications yet.</p>
                            <Link href="/find-jobs" className="mt-3 inline-block text-sm font-semibold text-[#9E0059] hover:underline">Browse jobs →</Link>
                        </div>
                    )}
                </div>

                {/* Recommendations */}
                {recommendations?.length > 0 && (
                    <div className="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-5">
                        <h2 className="font-bold text-[#1F0055] mb-4">Recommended for You</h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                            {recommendations.slice(0, 4).map((rec, i) => (
                                <Link key={i} href={`/find-jobs/${rec.job_uuid}`}
                                    className="rounded-xl border border-gray-100 p-4 hover:border-[#9E0059]/30 hover:bg-[#fdf0f7]/40 transition-all">
                                    <div className="font-semibold text-sm text-gray-900">{rec.title}</div>
                                    <div className="text-xs text-gray-400 mt-0.5">{rec.company}</div>
                                    <div className="text-xs font-bold mt-2" style={{ color: '#9E0059' }}>{rec.match_score}% match</div>
                                </Link>
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </JobifyLayout>
    );
}
