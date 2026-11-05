import JobifyLayout from '../../../Layouts/JobifyLayout';
import { Link, router } from '@inertiajs/react';
import { useMemo } from 'react';

// ── helpers ───────────────────────────────────────────────────────────────
function niceAxis(dataMax) {
    if (dataMax === 0) return { max: 6, ticks: [0, 2, 4, 6] };
    const step = dataMax <= 3 ? 1 : dataMax <= 8 ? 2 : dataMax <= 15 ? 3 : 5;
    const niceMax = Math.ceil(dataMax / step) * step;
    const ticks = [];
    for (let i = 0; i <= niceMax; i += step) ticks.push(i);
    return { max: niceMax, ticks };
}

const BAR_PALETTE  = ['#D28AB3','#BE5490','#B1337A','#9E0059','#900051'];
const STAGE_COLORS = ['#9E0059','#38009A','#A07CD8','#D28AB3','#FECA33','#0D9488'];

// Sample trend data (same as QT)
const TREND_BARS    = [
    { label: 'Wk 1', value: 1, recent: false },
    { label: 'Wk 2', value: 2, recent: false },
    { label: 'Wk 3', value: 5, recent: false },
    { label: 'Wk 4', value: 5, recent: false },
    { label: 'Wk 5', value: 7, recent: true },
    { label: 'Wk 6', value: 7, recent: true },
];
const TREND_MAX   = 9;
const TREND_TICKS = [0, 2, 5, 7, 9];

const VA_BARS = [
    { label: 'Wk 1', views: 2,  apps: 1 },
    { label: 'Wk 2', views: 3,  apps: 2 },
    { label: 'Wk 3', views: 4,  apps: 2 },
    { label: 'Wk 4', views: 5,  apps: 3 },
    { label: 'Wk 5', views: 9,  apps: 5 },
    { label: 'Wk 6', views: 11, apps: 6 },
];

const TTR_BARS = [
    { label: 'Wk 1', recent: 11, earlier: 10 },
    { label: 'Wk 2', recent: 9,  earlier: 8  },
    { label: 'Wk 3', recent: 7,  earlier: 6  },
    { label: 'Wk 4', recent: 5,  earlier: 5  },
    { label: 'Wk 5', recent: 3,  earlier: 4  },
    { label: 'Wk 6', recent: 3,  earlier: 5  },
];

const DONUT_DATA = [
    { label: 'Organic search', pct: 50, color: '#9E0059' },
    { label: 'Talent network', pct: 25, color: '#38009A' },
    { label: 'Referrals',      pct: 25, color: '#B48600' },
];
const CIRC = 289.0;

const AVATAR_PALETTE = [
    { bg: '#F5E6EE', text: '#9E0059' },
    { bg: '#EBE6F5', text: '#38009A' },
    { bg: '#FFF8E6', text: '#8C6800' },
    { bg: '#FFEEE6', text: '#7A3000' },
];

// ── sub-components ────────────────────────────────────────────────────────
function KpiCard({ icon, label, value, iconBg }) {
    return (
        <div className="rounded-2xl bg-white shadow-sm px-5 py-4 flex items-center gap-4" style={{ border: '1px solid #F2F3F6' }}>
            <div className="h-10 w-10 rounded-xl flex items-center justify-center shrink-0" style={{ background: iconBg }}>
                {icon}
            </div>
            <div>
                <p className="text-sm font-medium" style={{ color: '#3C334C' }}>{label}</p>
                <p className="text-4xl font-semibold leading-tight" style={{ color: '#0B001F' }}>{value ?? 0}</p>
            </div>
        </div>
    );
}

function BarChart({ bars, yAxis, height = 240, tooltip }) {
    return (
        <div className="flex gap-2">
            <div className="relative shrink-0" style={{ width: 16, height }}>
                {yAxis.ticks.map(tick => (
                    <span key={tick} className="absolute right-0 text-[9px] leading-none -translate-y-1/2"
                        style={{ color: '#5C5469', top: `${((1 - tick / yAxis.max) * 100)}%` }}>{tick}</span>
                ))}
            </div>
            <div className="flex-1">
                <div className="relative" style={{ height }}>
                    {yAxis.ticks.map(tick => (
                        <div key={tick} className="absolute inset-x-0 pointer-events-none border-t"
                            style={{ borderColor: tick === 0 ? '#E5E7EB' : '#F3F4F6', top: `${((1 - tick / yAxis.max) * 100)}%` }} />
                    ))}
                    <div className="absolute inset-0 flex items-end gap-1.5 px-0.5">
                        {bars.map((bar, i) => (
                            <div key={i} className="flex-1 h-full flex items-end relative group">
                                {tooltip && (
                                    <div className="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-20 pointer-events-none">
                                        <div className="text-white text-[10px] font-medium rounded px-2 py-1 whitespace-nowrap shadow-lg" style={{ background: '#0B001F' }}>
                                            {tooltip(bar)}
                                        </div>
                                        <div className="w-1.5 h-1.5 rotate-45 -mt-[3px]" style={{ background: '#0B001F' }} />
                                    </div>
                                )}
                                <div className="w-full rounded-t cursor-pointer transition-opacity hover:opacity-80"
                                    style={{ height: `${Math.max(bar.pct ?? (bar.value / yAxis.max * 100), bar.value > 0 ? 2 : 0)}%`, background: bar.color }} />
                            </div>
                        ))}
                    </div>
                </div>
                <div className="flex gap-1.5 mt-1.5 px-0.5">
                    {bars.map((bar, i) => (
                        <span key={i} className="flex-1 text-center text-[9px] truncate" style={{ color: '#5C5469' }}>{bar.label}</span>
                    ))}
                </div>
            </div>
        </div>
    );
}

function GroupedBarChart({ bars, yAxis, height = 200 }) {
    return (
        <div className="flex gap-2">
            <div className="relative shrink-0" style={{ width: 16, height }}>
                {yAxis.ticks.map(tick => (
                    <span key={tick} className="absolute right-0 text-[9px] leading-none -translate-y-1/2"
                        style={{ color: '#5C5469', top: `${((1 - tick / yAxis.max) * 100)}%` }}>{tick}</span>
                ))}
            </div>
            <div className="flex-1">
                <div className="relative" style={{ height }}>
                    {yAxis.ticks.map(tick => (
                        <div key={tick} className="absolute inset-x-0 pointer-events-none border-t"
                            style={{ borderColor: tick === 0 ? '#E5E7EB' : '#F3F4F6', top: `${((1 - tick / yAxis.max) * 100)}%` }} />
                    ))}
                    <div className="absolute inset-0 flex items-end gap-1 px-0.5">
                        {bars.map((bar, i) => (
                            <div key={i} className="flex-1 flex gap-0.5 items-end h-full relative group">
                                <div className="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-20 pointer-events-none">
                                    <div className="text-white text-[10px] font-medium rounded px-2 py-1 whitespace-nowrap shadow-lg" style={{ background: '#0B001F' }}>
                                        {bar.tooltip}
                                    </div>
                                    <div className="w-1.5 h-1.5 rotate-45 -mt-[3px]" style={{ background: '#0B001F' }} />
                                </div>
                                {bar.cols.map((col, ci) => (
                                    <div key={ci} className="flex-1 rounded-t"
                                        style={{ height: `${Math.max((col.value / yAxis.max) * 100, 3)}%`, background: col.color }} />
                                ))}
                            </div>
                        ))}
                    </div>
                </div>
                <div className="flex gap-1 mt-1.5 px-0.5">
                    {bars.map((bar, i) => (
                        <span key={i} className="flex-1 text-center text-[9px]" style={{ color: '#5C5469' }}>{bar.label}</span>
                    ))}
                </div>
            </div>
        </div>
    );
}

function Legend({ items }) {
    return (
        <div className="mt-3 flex items-center gap-4">
            {items.map(item => (
                <div key={item.label} className="flex items-center gap-1.5">
                    <span className="h-2.5 w-2.5 rounded-sm" style={{ background: item.color }} />
                    <span className="text-[11px]" style={{ color: '#5C5469' }}>{item.label}</span>
                </div>
            ))}
        </div>
    );
}

// ── main ─────────────────────────────────────────────────────────────────
export default function CompanyDashboard({
    org, activeJobsCount, applicationsCount, needsReviewCount,
    applicantsByJob, stageDistribution, pipelineStages,
    recentJobs, recentApplications,
}) {
    // Applicants by job bars
    const jobsYAxis = useMemo(() => niceAxis(Math.max(...(applicantsByJob ?? []).map(j => j.count), 0)), [applicantsByJob]);
    const applicantsBars = useMemo(() => {
        if (!applicantsByJob?.length) return [];
        return applicantsByJob.map((j, i) => ({
            label: j.title.length > 10 ? j.title.slice(0, 10) + '…' : j.title,
            value: j.count,
            pct: jobsYAxis.max > 0 ? (j.count / jobsYAxis.max) * 100 : 0,
            color: j.count > 0 ? BAR_PALETTE[i % BAR_PALETTE.length] : '#F2F3F6',
            fullLabel: j.title,
        }));
    }, [applicantsByJob, jobsYAxis]);

    const vaYAxis  = useMemo(() => niceAxis(Math.max(...VA_BARS.flatMap(b => [b.views, b.apps]))), []);
    const ttrYAxis = useMemo(() => niceAxis(Math.max(...TTR_BARS.flatMap(b => [b.recent, b.earlier]))), []);

    // Donut segments
    const donutSegments = useMemo(() => {
        let offset = 0;
        return DONUT_DATA.map(d => {
            const dash = (d.pct / 100) * CIRC;
            const seg  = { ...d, dash, gap: CIRC - dash, offset };
            offset += dash;
            return seg;
        });
    }, []);

    const pipelineMax = useMemo(() => Math.max(...(pipelineStages ?? []).map(s => s.count), 1), [pipelineStages]);

    function getBadgeStyle(status) {
        const map = {
            active:   { background: '#E1F7E8', color: '#0E9133' },
            draft:    { background: '#F2F3F6', color: '#5C5469' },
            paused:   { background: '#FFF8E6', color: '#8C6800' },
            closed:   { background: '#FFEEE6', color: '#7A2000' },
        };
        return map[status] ?? map.draft;
    }

    return (
        <JobifyLayout title="Dashboard">
            <div className="space-y-5">

                {/* ── Heading ── */}
                <div className="flex items-center justify-between">
                    <div>
                        <h2 className="text-[28px] font-semibold leading-tight" style={{ color: '#0B001F' }}>Hiring overview</h2>
                        <p className="text-sm mt-0.5" style={{ color: '#5C5469' }}>A clear view of your jobs and candidates.</p>
                    </div>
                    <Link href="/employer/jobs/create"
                        className="flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition hover:opacity-90"
                        style={{ background: '#38009A' }}>
                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2.5}><path strokeLinecap="round" strokeLinejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Post a New Job
                    </Link>
                </div>

                {/* ── KPI cards ── */}
                <div className="grid grid-cols-4 gap-4">
                    <KpiCard label="Active Jobs" value={activeJobsCount}
                        iconBg="#F5E6EE"
                        icon={<svg className="h-5 w-5" style={{ color: '#9E0059' }} fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={1.8}><path strokeLinecap="round" strokeLinejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zm-9-4h2a2 2 0 012 2v2H9V5a2 2 0 012-2z"/></svg>} />
                    <KpiCard label="Applications" value={applicationsCount}
                        iconBg="#EBE6F5"
                        icon={<svg className="h-5 w-5" style={{ color: '#38009A' }} fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={1.8}><path strokeLinecap="round" strokeLinejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>} />
                    <KpiCard label="Needs Review" value={needsReviewCount}
                        iconBg="#FFF8E6"
                        icon={<svg className="h-5 w-5" style={{ color: '#8C6800' }} fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={1.8}><path strokeLinecap="round" strokeLinejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>} />
                    <KpiCard label="Upcoming Interviews" value={0}
                        iconBg="#FFEEE6"
                        icon={<svg className="h-5 w-5" style={{ color: '#9E4B00' }} fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={1.8}><path strokeLinecap="round" strokeLinejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>} />
                </div>

                {/* ── Hiring analytics ── */}
                <div className="rounded-2xl bg-white shadow-sm overflow-hidden" style={{ border: '1px solid #F2F3F6' }}>
                    <div className="px-6 pt-5 pb-4">
                        <h3 className="text-lg font-semibold" style={{ color: '#0B001F' }}>Hiring analytics</h3>
                        <p className="text-[13px] mt-0.5" style={{ color: '#5C5469' }}>Job and applicant data for {org?.name}</p>
                    </div>
                    <div className="grid grid-cols-3 divide-x divide-gray-100" style={{ borderTop: '1px solid #F2F3F6' }}>

                        {/* Applicants by job */}
                        <div className="px-5 py-5 overflow-hidden min-w-0">
                            <p className="text-base font-semibold" style={{ color: '#0B001F' }}>Applicants by job</p>
                            <p className="text-xs mt-0.5" style={{ color: '#5C5469' }}>Current active jobs · {applicationsCount ?? 0} total applicants</p>
                            <div className="mt-4">
                                {applicantsBars.length > 0
                                    ? <BarChart bars={applicantsBars} yAxis={jobsYAxis} height={240} tooltip={b => `${b.fullLabel}: ${b.value} applicant${b.value !== 1 ? 's' : ''}`} />
                                    : <div className="flex items-center justify-center text-xs" style={{ height: 240, color: '#5C5469' }}>No active jobs</div>
                                }
                            </div>
                            <Legend items={[{ label: 'Has applicants', color: '#9E0059' }, { label: 'No applicants', color: '#F2F3F6' }]} />
                        </div>

                        {/* Application trend */}
                        <div className="px-5 py-5 overflow-hidden min-w-0">
                            <div className="flex items-start justify-between gap-2">
                                <div>
                                    <p className="text-base font-semibold" style={{ color: '#0B001F' }}>Application trend</p>
                                    <p className="text-xs mt-0.5" style={{ color: '#5C5469' }}>Last 6 weeks · Illustrative data</p>
                                </div>
                                <span className="text-[11px] font-medium rounded px-1.5 py-0.5 shrink-0" style={{ background: '#FFF8E6', color: '#8C6800' }}>Sample</span>
                            </div>
                            <div className="mt-4">
                                <BarChart
                                    bars={TREND_BARS.map(b => ({ ...b, pct: (b.value / TREND_MAX) * 100, color: b.recent ? '#38009A' : '#B8A7FF' }))}
                                    yAxis={{ max: TREND_MAX, ticks: TREND_TICKS }}
                                    height={240}
                                    tooltip={b => `${b.label}: ${b.value} applications`}
                                />
                            </div>
                            <Legend items={[{ label: 'Recent weeks', color: '#38009A' }, { label: 'Earlier weeks', color: '#B8A7FF' }]} />
                        </div>

                        {/* Stage distribution */}
                        <div className="px-5 py-5 overflow-hidden min-w-0">
                            <p className="text-base font-semibold" style={{ color: '#0B001F' }}>Stage distribution</p>
                            <p className="text-xs mt-0.5" style={{ color: '#5C5469' }}>{applicationsCount ?? 0} applicants · Current snapshot</p>
                            {stageDistribution?.length > 0 ? (
                                <div className="mt-4">
                                    <div className="flex h-3 rounded-full overflow-hidden" style={{ background: '#F2F3F6' }}>
                                        {stageDistribution.map((s, si) => (
                                            <div key={s.name} style={{ width: `${s.pct}%`, background: STAGE_COLORS[si] ?? '#C8BDE8' }} className="transition-all" />
                                        ))}
                                    </div>
                                    <div className="flex gap-2 mt-1.5 flex-wrap">
                                        {stageDistribution.map((s, si) => (
                                            <span key={s.name} className="text-[10px] font-semibold" style={{ color: STAGE_COLORS[si] ?? '#9CA3AF' }}>
                                                {s.name} {s.pct}%
                                            </span>
                                        ))}
                                    </div>
                                    <div className="mt-3 space-y-3 overflow-y-auto" style={{ maxHeight: 200 }}>
                                        {stageDistribution.map((s, si) => (
                                            <div key={s.name}>
                                                <div className="flex items-center justify-between mb-1">
                                                    <div className="flex items-center gap-2">
                                                        <span className="h-2.5 w-2.5 rounded-full shrink-0" style={{ background: STAGE_COLORS[si] ?? '#C8BDE8' }} />
                                                        <span className="text-xs font-medium" style={{ color: '#3C334C' }}>{s.name}</span>
                                                    </div>
                                                    <span className="text-xs" style={{ color: '#5C5469' }}>{s.count} {s.count === 1 ? 'applicant' : 'applicants'}</span>
                                                </div>
                                                <div className="h-1.5 rounded-full overflow-hidden" style={{ background: '#F2F3F6' }}>
                                                    <div className="h-full rounded-full" style={{ width: `${s.pct}%`, background: STAGE_COLORS[si] ?? '#C8BDE8' }} />
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            ) : (
                                <div className="mt-4 flex items-center justify-center rounded-lg text-xs" style={{ height: 140, background: '#F8F9FB', color: '#5C5469' }}>No pipeline data</div>
                            )}
                            <div className="mt-3 -mx-5 -mb-5 px-5 py-3 flex items-start gap-2" style={{ background: '#F2F3F6' }}>
                                <svg className="h-3.5 w-3.5 shrink-0 mt-px" style={{ color: '#6F7D99' }} fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><circle cx="12" cy="12" r="10"/><path strokeLinecap="round" strokeLinejoin="round" d="M12 16v-4m0-4h.01"/></svg>
                                <p className="text-[11px]" style={{ color: '#6F7D99' }}>Reflects current applicant stages across all active jobs.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* ── Hiring pipeline ── */}
                {pipelineStages?.length > 0 && (
                    <div className="rounded-2xl bg-white shadow-sm px-6 py-5" style={{ border: '1px solid #F2F3F6' }}>
                        <h3 className="text-base font-semibold" style={{ color: '#0B001F' }}>Hiring pipeline</h3>
                        <p className="text-[13px] mt-0.5" style={{ color: '#5C5469' }}>{applicationsCount ?? 0} applicants across active jobs</p>
                        <div className="mt-4 flex gap-4">
                            {pipelineStages.map(stage => (
                                <div key={stage.name} className="flex-1 min-w-0">
                                    <p className="text-xs font-medium truncate" style={{ color: '#5C5469' }}>{stage.name}</p>
                                    <p className="text-[22px] font-bold mt-1" style={{ color: '#0B001F' }}>{stage.count}</p>
                                    <div className="h-1.5 rounded-full mt-2 overflow-hidden" style={{ background: '#F5E6EE' }}>
                                        <div className="h-full rounded-full transition-all"
                                            style={{ width: `${pipelineMax > 0 ? (stage.count / pipelineMax) * 100 : 0}%`, background: '#9E0059' }} />
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}

                {/* ── Employer analytics ── */}
                <div className="rounded-2xl bg-white shadow-sm overflow-hidden" style={{ border: '1px solid #F2F3F6' }}>
                    <div className="px-6 pt-5 pb-4">
                        <h3 className="text-lg font-semibold" style={{ color: '#0B001F' }}>Employer analytics</h3>
                        <p className="text-[13px] mt-0.5" style={{ color: '#5C5469' }}>Visibility, sourcing, and responsiveness trends for {org?.name}</p>
                    </div>
                    <div className="grid grid-cols-3 divide-x divide-gray-100" style={{ borderTop: '1px solid #F2F3F6' }}>

                        {/* Job views vs applications */}
                        <div className="px-5 py-5 overflow-hidden min-w-0">
                            <div className="flex items-start justify-between gap-2">
                                <div>
                                    <p className="text-base font-semibold" style={{ color: '#0B001F' }}>Job views vs applications</p>
                                    <p className="text-xs mt-0.5" style={{ color: '#5C5469' }}>Last 6 weeks · Illustrative data</p>
                                </div>
                                <span className="text-[11px] font-medium rounded px-1.5 py-0.5 shrink-0" style={{ background: '#FFF8E6', color: '#8C6800' }}>Sample</span>
                            </div>
                            <div className="mt-4">
                                <GroupedBarChart height={200} yAxis={vaYAxis}
                                    bars={VA_BARS.map(b => ({
                                        label: b.label,
                                        tooltip: `${b.label}: ${b.views} views · ${b.apps} apps`,
                                        cols: [{ value: b.views, color: '#38009A' }, { value: b.apps, color: '#9E0059' }],
                                    }))} />
                            </div>
                            <Legend items={[{ label: 'Job views', color: '#38009A' }, { label: 'Applications', color: '#9E0059' }]} />
                        </div>

                        {/* Candidate source breakdown */}
                        <div className="px-5 py-5 overflow-hidden min-w-0 flex flex-col">
                            <p className="text-base font-semibold" style={{ color: '#0B001F' }}>Candidate source breakdown</p>
                            <p className="text-xs mt-0.5" style={{ color: '#5C5469' }}>Last 6 weeks · Illustrative data</p>
                            <div className="mt-4 flex flex-col items-center flex-1">
                                <div className="relative">
                                    <svg viewBox="0 0 120 120" className="h-44 w-44 -rotate-90">
                                        <circle cx="60" cy="60" r="46" fill="none" stroke="#F2F3F6" strokeWidth="22"/>
                                        {donutSegments.map(seg => (
                                            <circle key={seg.label} cx="60" cy="60" r="46" fill="none"
                                                stroke={seg.color} strokeWidth="22"
                                                strokeDasharray={`${seg.dash} ${seg.gap}`}
                                                strokeDashoffset={-seg.offset} />
                                        ))}
                                    </svg>
                                    <div className="absolute inset-0 flex flex-col items-center justify-center">
                                        <p className="text-[11px]" style={{ color: '#5C5469' }}>Source</p>
                                        <p className="text-2xl font-bold leading-tight" style={{ color: '#0B001F' }}>{applicationsCount || 0}</p>
                                        <p className="text-[11px]" style={{ color: '#5C5469' }}>applicants</p>
                                    </div>
                                </div>
                                <div className="mt-3 w-full space-y-2">
                                    {DONUT_DATA.map(d => (
                                        <div key={d.label} className="flex items-center justify-between">
                                            <div className="flex items-center gap-2">
                                                <span className="h-2.5 w-2.5 rounded-sm shrink-0" style={{ background: d.color }} />
                                                <span className="text-[13px]" style={{ color: '#5C5469' }}>{d.label}</span>
                                            </div>
                                            <span className="text-[13px] font-semibold" style={{ color: '#0B001F' }}>{d.pct}%</span>
                                        </div>
                                    ))}
                                </div>
                            </div>
                            <div className="mt-4 -mx-5 -mb-5 px-5 py-3 flex items-start gap-2" style={{ background: '#F2F3F6' }}>
                                <svg className="h-3.5 w-3.5 shrink-0 mt-px" style={{ color: '#6F7D99' }} fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><circle cx="12" cy="12" r="10"/><path strokeLinecap="round" strokeLinejoin="round" d="M12 16v-4m0-4h.01"/></svg>
                                <p className="text-[12px]" style={{ color: '#6F7D99' }}>Illustrative source mix.</p>
                            </div>
                        </div>

                        {/* Time to first review */}
                        <div className="px-5 py-5 overflow-hidden min-w-0">
                            <div className="flex items-start justify-between gap-2">
                                <div>
                                    <p className="text-base font-semibold" style={{ color: '#0B001F' }}>Time to first review</p>
                                    <p className="text-xs mt-0.5" style={{ color: '#5C5469' }}>Last 6 weeks · Days · Illustrative data</p>
                                </div>
                                <span className="text-[11px] font-medium rounded px-1.5 py-0.5 shrink-0" style={{ background: '#FFF8E6', color: '#8C6800' }}>Sample</span>
                            </div>
                            <div className="mt-4">
                                <GroupedBarChart height={200} yAxis={ttrYAxis}
                                    bars={TTR_BARS.map(b => ({
                                        label: b.label,
                                        tooltip: `${b.label}: ${b.recent}d (recent) · ${b.earlier}d (earlier)`,
                                        cols: [{ value: b.recent, color: '#38009A' }, { value: b.earlier, color: '#B8A7FF' }],
                                    }))} />
                            </div>
                            <Legend items={[{ label: 'Recent weeks', color: '#38009A' }, { label: 'Earlier weeks', color: '#B8A7FF' }]} />
                        </div>
                    </div>
                </div>

                {/* ── Bottom 2/3 + 1/3 ── */}
                <div className="grid grid-cols-3 gap-4 items-start">
                    <div className="col-span-2 space-y-4">

                        {/* Active jobs table */}
                        <div className="rounded-2xl bg-white shadow-sm overflow-hidden" style={{ border: '1px solid #F2F3F6' }}>
                            <div className="flex items-center justify-between px-5 py-4" style={{ borderBottom: '1px solid #E7E6E9' }}>
                                <h3 className="text-base font-semibold" style={{ color: '#0B001F' }}>Active jobs</h3>
                                <Link href="/employer/jobs" className="text-[13px] font-medium hover:underline" style={{ color: '#38009A' }}>View all</Link>
                            </div>
                            {!recentJobs?.length
                                ? <div className="px-5 py-10 text-center text-sm" style={{ color: '#5C5469' }}>No jobs posted yet.</div>
                                : (
                                    <table className="w-full text-sm">
                                        <thead>
                                            <tr style={{ background: '#F2F3F6' }}>
                                                <th className="px-5 py-2.5 text-left text-xs font-semibold" style={{ color: '#5C5469' }}>Job</th>
                                                <th className="px-3 py-2.5 text-center text-xs font-semibold w-24" style={{ color: '#5C5469' }}>Applicants</th>
                                                <th className="px-3 py-2.5 text-center text-xs font-semibold w-28" style={{ color: '#5C5469' }}>Needs review</th>
                                                <th className="px-3 py-2.5 text-center text-xs font-semibold w-20" style={{ color: '#5C5469' }}>Status</th>
                                                <th className="px-3 py-2.5 w-8" />
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {recentJobs.map(job => (
                                                <tr key={job.id} className="hover:bg-gray-50/60 transition-colors cursor-pointer" style={{ borderTop: '1px solid #E7E6E9' }}
                                                    onClick={() => router.visit(`/employer/jobs/${job.id}`)}>
                                                    <td className="px-5 py-3.5">
                                                        <p className="text-sm font-medium" style={{ color: '#0B001F' }}>{job.title}</p>
                                                        <p className="text-[13px] mt-0.5" style={{ color: '#5C5469' }}>{[job.location, job.employment_type?.replace('_',' ')].filter(Boolean).join(' · ')}</p>
                                                    </td>
                                                    <td className="px-3 py-3.5 text-center">
                                                        <span className="text-sm font-medium" style={{ color: (job.applications_count ?? 0) > 0 ? '#9E0059' : '#5C5469' }}>
                                                            {job.applications_count ?? 0}
                                                        </span>
                                                    </td>
                                                    <td className="px-3 py-3.5 text-center">
                                                        {(job.needs_review_count ?? 0) > 0
                                                            ? <span className="inline-flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-medium" style={{ background: '#FFF8E6', color: '#926A0A' }}>{job.needs_review_count}</span>
                                                            : <span className="text-sm" style={{ color: '#5C5469' }}>–</span>}
                                                    </td>
                                                    <td className="px-3 py-3.5 text-center">
                                                        <span className="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize" style={getBadgeStyle(job.status)}>
                                                            {job.status}
                                                        </span>
                                                    </td>
                                                    <td className="px-3 py-3.5 text-center">
                                                        <svg className="h-4 w-4 mx-auto" style={{ color: '#C2C9D7' }} fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7"/></svg>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                )}
                        </div>

                        {/* New applications */}
                        <div className="rounded-2xl bg-white shadow-sm overflow-hidden" style={{ border: '1px solid #F2F3F6' }}>
                            <div className="flex items-center justify-between px-5 py-4" style={{ borderBottom: '1px solid #E7E6E9' }}>
                                <h3 className="text-base font-semibold" style={{ color: '#0B001F' }}>New applications</h3>
                                <Link href="/employer/applications" className="text-[13px] font-medium hover:underline" style={{ color: '#38009A' }}>View all</Link>
                            </div>
                            {!recentApplications?.length
                                ? <div className="px-5 py-8 text-center text-sm" style={{ color: '#5C5469' }}>No recent applications.</div>
                                : (
                                    <ul>
                                        {recentApplications.map((app, idx) => (
                                            <li key={app.id} className="flex items-center gap-3 px-5 py-4 hover:bg-gray-50/60 transition-colors" style={{ borderTop: '1px solid #E7E6E9' }}>
                                                <div className="h-9 w-9 rounded-lg shrink-0 flex items-center justify-center text-xs font-bold"
                                                    style={{ background: AVATAR_PALETTE[idx % AVATAR_PALETTE.length].bg, color: AVATAR_PALETTE[idx % AVATAR_PALETTE.length].text }}>
                                                    {(app.candidate_name || '?').slice(0, 2).toUpperCase()}
                                                </div>
                                                <div className="min-w-0 flex-1">
                                                    <p className="text-sm font-semibold" style={{ color: '#0B001F' }}>{app.candidate_name || 'Unknown'}</p>
                                                    <p className="text-[13px] truncate mt-0.5" style={{ color: '#5C5469' }}>{app.job_title}</p>
                                                </div>
                                                <div className="flex flex-col items-end gap-1 shrink-0">
                                                    <span className="text-[11px]" style={{ color: '#5C5469' }}>{app.applied_at}</span>
                                                    <Link href="/employer/applications" className="text-[13px] font-semibold hover:underline" style={{ color: '#9E0059' }}>Review</Link>
                                                </div>
                                            </li>
                                        ))}
                                    </ul>
                                )}
                        </div>
                    </div>

                    {/* Right column */}
                    <div className="space-y-4">
                        {/* Upcoming interviews placeholder */}
                        <div className="rounded-2xl bg-white shadow-sm px-5 py-5" style={{ border: '1px solid #F2F3F6' }}>
                            <h3 className="text-base font-semibold" style={{ color: '#0B001F' }}>Upcoming interviews</h3>
                            <div className="mt-4 rounded-xl flex flex-col items-center justify-center gap-3 py-10" style={{ background: '#F8F9FB' }}>
                                <div className="h-11 w-11 rounded-xl flex items-center justify-center" style={{ background: '#EBE6F5' }}>
                                    <svg className="h-5 w-5" style={{ color: '#38009A' }} fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div className="text-center">
                                    <p className="text-sm font-semibold" style={{ color: '#3C334C' }}>No interviews scheduled</p>
                                    <p className="text-[13px] mt-0.5 px-4" style={{ color: '#5C5469' }}>Shortlist a candidate to plan an interview.</p>
                                </div>
                            </div>
                        </div>

                        {/* Improve listings */}
                        <div className="rounded-2xl px-5 py-5" style={{ background: '#EBE6F5', border: '1px solid #F2F3F6' }}>
                            <div className="flex items-center gap-2 mb-2">
                                <div className="h-6 w-6 rounded-full flex items-center justify-center">
                                    <svg className="h-5 w-5" style={{ color: '#38009A' }} fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={1.5}><path strokeLinecap="round" strokeLinejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                                </div>
                                <p className="text-[15px] font-semibold" style={{ color: '#0B001F' }}>Improve your listings</p>
                            </div>
                            <p className="text-[13px]" style={{ color: '#5C5469' }}>Complete listing details to reach more candidates and improve visibility.</p>
                            <Link href="/employer/jobs"
                                className="mt-4 inline-flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition hover:opacity-90"
                                style={{ background: '#38009A' }}>
                                Edit Listing
                            </Link>
                        </div>
                    </div>
                </div>

            </div>
        </JobifyLayout>
    );
}
