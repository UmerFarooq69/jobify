import { Link, router, usePage } from '@inertiajs/react';
import { useState, useRef, useEffect } from 'react';
import { createPortal } from 'react-dom';
import JobifyAuthModal from '../../Components/JobifyAuthModal';

// ─── constants ──────────────────────────────────────────────────────────────
const EMPLOYMENT_LABELS = {
    full_time: 'Full Time', part_time: 'Part Time', contract: 'Contract',
    freelance: 'Freelance', internship: 'Internship',
};

const POPULAR = ['Software Engineer', 'Marketing', 'Product Manager', 'Data Analyst', 'Designer'];

// ─── helpers ────────────────────────────────────────────────────────────────
function timeAgo(d) {
    if (!d) return '';
    const hrs  = Math.floor((Date.now() - new Date(d)) / 3600000);
    const days = Math.floor(hrs / 24);
    if (hrs  < 1)  return 'Just now';
    if (hrs  < 24) return `${hrs}h ago`;
    if (days < 30) return `${days}d ago`;
    return `${Math.floor(days / 30)}mo ago`;
}

function fmtSalary(job) {
    if (!job?.salary_min && !job?.salary_max) return null;
    const fmt = n => n?.toLocaleString();
    const c = job.currency ?? '';
    if (job.salary_min && job.salary_max) return `${c} ${fmt(job.salary_min)} – ${fmt(job.salary_max)}`.trim();
    if (job.salary_min) return `${c} ${fmt(job.salary_min)}+`.trim();
    return `Up to ${c} ${fmt(job.salary_max)}`.trim();
}

function reqList(job) {
    if (!job?.requirements) return [];
    if (Array.isArray(job.requirements)) return job.requirements.filter(Boolean);
    return job.requirements.split('\n').map(s => s.trim()).filter(Boolean);
}

function getRecents() {
    try { return JSON.parse(localStorage.getItem('jfy_recents') ?? '[]'); } catch { return []; }
}
function saveRecent(val) {
    try {
        const prev = getRecents().filter(s => s.toLowerCase() !== val.toLowerCase());
        localStorage.setItem('jfy_recents', JSON.stringify([val, ...prev].slice(0, 5)));
    } catch {}
}

// ─── icons ──────────────────────────────────────────────────────────────────
const Ico = {
    search:   <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>,
    loc:      <svg className="h-3.5 w-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path strokeLinecap="round" strokeLinejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>,
    bag:      <svg className="h-3.5 w-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>,
    coin:     <svg className="h-3.5 w-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>,
    bookmark: <svg className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth={1.8} viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" d="M5 3a2 2 0 00-2 2v16l7-3 7 3V5a2 2 0 00-2-2H5z"/></svg>,
    chevDown: <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M19 9l-7 7-7-7"/></svg>,
    x:        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>,
    back:     <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M15 19l-7-7 7-7"/></svg>,
    filter:   <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M3 4h18M7 10h10M11 16h2"/></svg>,
    sparkle:  <svg className="h-4 w-4 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.09 6.26L20 10l-5.91 1.74L12 18l-2.09-5.74L4 10l5.91-1.74L12 2z"/></svg>,
    clock:    <svg className="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={1.8}><path strokeLinecap="round" strokeLinejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>,
    home:     <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={1.8}><path strokeLinecap="round" strokeLinejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>,
    user:     <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={1.8}><path strokeLinecap="round" strokeLinejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>,
    lock:     <svg className="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={1.8}><path strokeLinecap="round" strokeLinejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>,
};

// ─── SearchNavbar ────────────────────────────────────────────────────────────
function SearchNavbar({ q, location, onSearch, onOpenMobile, setModal }) {
    const { props } = usePage();
    const user = props.auth?.user ?? null;
    const [kw, setKw] = useState(q ?? '');
    const [loc, setLoc] = useState(location ?? '');

    const submit = () => onSearch(kw, loc);
    const onKey  = e => { if (e.key === 'Enter') submit(); };

    return (
        <nav className="flex-shrink-0 bg-white border-b border-gray-200 z-30">
            <div className="flex h-16 items-center gap-4 px-4 sm:px-6 lg:px-8">
                {/* Logo */}
                <Link href="/" className="flex-shrink-0">
                    <img src="/assets/images/logo/logo.jpg" alt="Jobify" className="h-9 w-auto object-contain" />
                </Link>

                {/* Desktop search pill */}
                <div className="hidden lg:flex flex-1 max-w-2xl mx-auto items-center bg-gray-50 border border-gray-200 rounded-full px-4 gap-3 h-10">
                    <span className="text-gray-400">{Ico.search}</span>
                    <input
                        value={kw}
                        onChange={e => setKw(e.target.value)}
                        onKeyDown={onKey}
                        placeholder="Job title, keyword..."
                        className="flex-1 bg-transparent text-sm text-gray-800 placeholder-gray-400 outline-none"
                    />
                    <span className="h-4 w-px bg-gray-300" />
                    <input
                        value={loc}
                        onChange={e => setLoc(e.target.value)}
                        onKeyDown={onKey}
                        placeholder="Location"
                        className="w-32 bg-transparent text-sm text-gray-800 placeholder-gray-400 outline-none"
                    />
                    <button onClick={submit} className="rounded-full px-4 py-1 text-xs font-semibold text-white transition"
                        style={{ background: 'linear-gradient(135deg,#9E0059,#38009A)' }}>
                        Search
                    </button>
                </div>

                {/* Mobile search pill (opens overlay) */}
                <button onClick={onOpenMobile} className="lg:hidden flex flex-1 items-center gap-2 bg-gray-50 border border-gray-200 rounded-full px-4 h-10 text-left">
                    <span className="text-gray-400">{Ico.search}</span>
                    <span className="flex-1 text-sm text-gray-400 truncate">{q || 'Search jobs...'}</span>
                </button>

                {/* Auth */}
                <div className="flex-shrink-0 flex items-center gap-2">
                    {user ? (
                        <Link href={user.role_type === 'company_admin' ? '/employer/dashboard' : '/candidate/dashboard'}
                            className="rounded-full px-4 py-1.5 text-sm font-semibold text-white transition"
                            style={{ background: '#9E0059' }}>
                            Dashboard
                        </Link>
                    ) : (
                        <>
                            <button onClick={() => setModal('candidate')} className="hidden sm:block text-sm font-medium text-gray-600 hover:text-[#9E0059] px-3 py-1.5 rounded-full hover:bg-gray-50 transition">
                                Sign In
                            </button>
                            <button onClick={() => setModal('candidate')} className="rounded-full px-4 py-1.5 text-sm font-semibold text-white transition"
                                style={{ background: '#9E0059' }}>
                                Sign Up
                            </button>
                        </>
                    )}
                </div>
            </div>
        </nav>
    );
}

// ─── FilterBar ───────────────────────────────────────────────────────────────
function FilterBar({ f, setF, onClear, onMobileFilter }) {
    const hasFilters = Object.values(f).some(Boolean);
    const sel = (field, opts, placeholder) => (
        <select value={f[field] ?? ''} onChange={e => setF(p => ({ ...p, [field]: e.target.value }))}
            className="cursor-pointer rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm font-medium text-gray-700 outline-none transition hover:border-gray-400 focus:border-[#9E0059] min-w-[120px]">
            <option value="">{placeholder}</option>
            {opts.map(([v, l]) => <option key={v} value={v}>{l}</option>)}
        </select>
    );

    return (
        <div className="flex-shrink-0 bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8 py-2.5 space-y-2">
            {/* Mobile: search field + Filters button */}
            <div className="flex lg:hidden items-center gap-2">
                <span className="flex-1 text-sm text-gray-500 truncate">{Object.values(f).filter(Boolean).length} filters active</span>
                <button onClick={onMobileFilter}
                    className="flex items-center gap-1.5 rounded-full border px-4 py-2 text-sm font-semibold transition"
                    style={{ borderColor: '#9E0059', color: '#9E0059', background: '#FDF0F7' }}>
                    {Ico.filter} Filters
                </button>
            </div>
            {/* Desktop selects */}
            <div className="hidden lg:flex items-center gap-2 flex-wrap">
                {sel('sort',       [['newest','Newest'],['salary_high','Highest salary'],['salary_low','Lowest salary']], 'Most relevant')}
                {sel('datePosted', [['24h','Last 24h'],['week','Last week'],['month','Last month'],['3months','Last 3 months']], 'Date posted')}
                {sel('experience', [['entry','Entry'],['mid','Mid'],['senior','Senior'],['executive','Executive']], 'Experience level')}
                {sel('type',       Object.entries(EMPLOYMENT_LABELS), 'Job type')}
                {sel('jobShift',   [['day','Day shift'],['night','Night shift'],['morning','Morning'],['flexible','Flexible']], 'Job shift')}
                {hasFilters && (
                    <button onClick={onClear} className="flex items-center gap-1 text-sm font-medium text-[#38009A] hover:text-red-500 transition">
                        {Ico.x} Clear
                    </button>
                )}
            </div>
        </div>
    );
}

// ─── JobCard (left panel row) ────────────────────────────────────────────────
function JobCard({ job, selected, onClick, appliedIds = [] }) {
    const active      = selected?.uuid === job.uuid;
    const hasApplied  = appliedIds.includes(job.id);
    return (
        <div onClick={onClick} className={`relative cursor-pointer border-b border-gray-100 px-4 py-4 transition hover:bg-gray-50
            ${active ? 'lg:border-l-4 lg:border-l-[#9E0059] lg:bg-[#9E0059]/[0.03]' : 'lg:border-l-4 lg:border-l-transparent'}`}>
            <div className="flex items-start gap-3">
                {/* Logo */}
                <div className="flex h-12 w-12 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-gray-50 p-1">
                    {job.organization?.logo
                        ? <img src={`/storage/${job.organization.logo}`} alt="" className="h-full w-full object-cover rounded-lg" />
                        : <span className="text-lg font-bold" style={{ color: '#9E0059' }}>{(job.organization?.name ?? 'J')[0]}</span>}
                </div>
                <div className="flex-1 min-w-0">
                    {/* Title + Featured badge */}
                    <div className="flex items-start gap-2">
                        <p className="flex-1 text-[15px] font-semibold leading-snug text-gray-900 line-clamp-2">{job.title}</p>
                        {job.urgent_hire && (
                            <span className="mt-0.5 flex-shrink-0 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-600 ring-1 ring-green-200">Urgent</span>
                        )}
                    </div>
                    {/* Company | Location */}
                    <p className="mt-1 text-sm">
                        <span className="font-medium text-gray-600">{job.organization?.name ?? 'Company'}</span>
                        {job.location && <><span className="text-gray-300"> | </span><span className="font-medium" style={{ color: '#9E0059' }}>{job.location}</span></>}
                    </p>
                    {/* Tags */}
                    <div className="mt-2 flex flex-wrap items-center gap-1.5">
                        {hasApplied
                            ? <span className="rounded-full px-2.5 py-0.5 text-xs font-semibold" style={{ background: '#E6F4EA', color: '#1A7F37' }}>✓ Applied</span>
                            : <span className="rounded-full px-2.5 py-0.5 text-xs font-semibold" style={{ background: '#EDE9FF', color: '#5B21B6' }}>Quick Apply</span>
                        }
                        <span className="rounded-full border border-gray-200 px-2.5 py-0.5 text-xs font-medium text-gray-600">{EMPLOYMENT_LABELS[job.employment_type] ?? job.employment_type}</span>
                        <span className="rounded-full border border-gray-200 px-2.5 py-0.5 text-xs font-medium text-gray-600">On Site</span>
                    </div>
                    {/* Posted */}
                    <p className="mt-2 text-xs text-gray-400">Posted {timeAgo(job.created_at)}</p>
                </div>
            </div>
        </div>
    );
}

// ─── JobDetail (right panel) ─────────────────────────────────────────────────
function JobDetail({ job, onBack, setModal }) {
    const { props } = usePage();
    const user       = props.auth?.user ?? null;
    const appliedIds = props.appliedIds ?? [];
    const hasApplied = appliedIds.includes(job.id);
    const [summaryOpen, setSummaryOpen] = useState(true);
    const salary = fmtSalary(job);
    const reqs   = reqList(job);

    function handleApply() {
        if (hasApplied) return;
        if (user) {
            router.post(`/find-jobs/${job.uuid}/apply`);
        } else {
            setModal('candidate');
        }
    }

    return (
        <div className="rounded-2xl bg-white shadow-sm overflow-hidden">
            {/* ── Header ── */}
            <div className="p-6 border-b border-gray-100">
                {/* Back (mobile) */}
                <button onClick={onBack} className="lg:hidden mb-4 flex items-center gap-1.5 text-sm font-medium hover:text-[#9E0059] transition" style={{ color: '#38009A' }}>
                    {Ico.back} Back to jobs
                </button>

                {/* Company row */}
                <div className="mb-5 flex items-center justify-between gap-4">
                    <div className="flex items-center gap-3 min-w-0">
                        <div className="flex h-12 w-12 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-gray-50 p-1.5">
                            {job.organization?.logo
                                ? <img src={`/storage/${job.organization.logo}`} alt="" className="h-full w-full object-cover rounded-lg" />
                                : <span className="text-xl font-bold" style={{ color: '#9E0059' }}>{(job.organization?.name ?? 'J')[0]}</span>}
                        </div>
                        <div className="min-w-0">
                            <p className="truncate text-sm font-semibold text-gray-800">{job.organization?.name ?? 'Company'}</p>
                        </div>
                    </div>
                    <span className="text-xs text-gray-400 flex-shrink-0">Posted {timeAgo(job.created_at)}</span>
                </div>

                {/* Title */}
                <h1 className="mb-4 text-2xl lg:text-3xl font-bold text-gray-900">{job.title}</h1>

                {/* Meta */}
                <div className="mb-6 flex flex-wrap items-center gap-4">
                    {job.location && (
                        <span className="inline-flex items-center gap-1.5 text-sm text-gray-600">
                            <span style={{ color: '#9E0059' }}>{Ico.loc}</span> {job.location}
                        </span>
                    )}
                    {job.employment_type && (
                        <span className="inline-flex items-center gap-1.5 text-sm text-gray-600">
                            {Ico.bag} {EMPLOYMENT_LABELS[job.employment_type] ?? job.employment_type}
                        </span>
                    )}
                    {salary && (
                        <span className="inline-flex items-center gap-1.5 text-sm font-medium text-gray-700">
                            {Ico.coin} {salary}
                        </span>
                    )}
                </div>

                {/* Actions */}
                <div className="flex flex-wrap gap-3">
                    <button onClick={handleApply} disabled={hasApplied}
                        className="rounded-full px-10 py-3 text-sm font-semibold text-white transition shadow-sm disabled:cursor-default"
                        style={{ background: hasApplied ? '#6B7280' : '#9E0059' }}>
                        {hasApplied ? '✓ Applied' : 'Apply Now'}
                    </button>
                    <Link href={`/find-jobs/${job.uuid}`}
                        className="rounded-full border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                        View Details
                    </Link>
                </div>
            </div>

            {/* ── Body ── */}
            <div className="px-6 py-6 space-y-8">
                {/* AI Summary */}
                {job.summary && (
                    <div className="overflow-hidden rounded-2xl" style={{ background: '#FFF8E6B2' }}>
                        <button onClick={() => setSummaryOpen(v => !v)} className="flex w-full items-center justify-between px-5 py-4">
                            <span className="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                {Ico.sparkle} Job Summary
                            </span>
                            <span className={`text-gray-400 transition-transform duration-200 ${summaryOpen ? 'rotate-180' : ''}`}>{Ico.chevDown}</span>
                        </button>
                        {summaryOpen && <div className="px-5 pb-5"><p className="text-sm leading-relaxed text-gray-600">{job.summary}</p></div>}
                    </div>
                )}

                {/* About the role */}
                {job.description && (
                    <div>
                        <h2 className="mb-3 text-base font-bold text-gray-900">About the role</h2>
                        {/<[a-z][\s\S]*>/i.test(job.description) ? (
                            <div className="job-html-content text-sm leading-relaxed text-gray-600"
                                dangerouslySetInnerHTML={{ __html: job.description }} />
                        ) : (
                            <p className="text-sm leading-relaxed text-gray-600 whitespace-pre-wrap">{job.description}</p>
                        )}
                    </div>
                )}

                {/* Requirements */}
                {job.requirements && (
                    <div>
                        <h2 className="mb-4 text-base font-bold text-gray-900">Key Responsibilities</h2>
                        {typeof job.requirements === 'string' ? (
                            /<[a-z][\s\S]*>/i.test(job.requirements) ? (
                                <div className="job-html-content text-sm leading-relaxed text-gray-600"
                                    dangerouslySetInnerHTML={{ __html: job.requirements }} />
                            ) : (
                                <ul className="space-y-2">
                                    {job.requirements.split('\n').filter(l => l.trim()).map((line, i) => (
                                        <li key={i} className="flex items-start gap-3 text-sm text-gray-600">
                                            <span className="mt-2 h-1.5 w-1.5 flex-shrink-0 rounded-full" style={{ background: '#9E0059' }} />
                                            {line.replace(/^[-•*]\s*/, '')}
                                        </li>
                                    ))}
                                </ul>
                            )
                        ) : (
                            <ul className="space-y-3">
                                {reqs.map((r, i) => (
                                    <li key={i} className="flex items-start gap-3 text-sm text-gray-600">
                                        <span className="mt-2 h-1.5 w-1.5 flex-shrink-0 rounded-full" style={{ background: '#9E0059' }} />
                                        {r}
                                    </li>
                                ))}
                            </ul>
                        )}
                    </div>
                )}

                {/* Skills */}
                {job.skills?.length > 0 && (
                    <div>
                        <h2 className="mb-3 text-base font-bold text-gray-900">Skills</h2>
                        <div className="flex flex-wrap gap-2">
                            {job.skills.map(s => (
                                <span key={s} className="rounded-full px-3 py-1 text-xs font-medium" style={{ background: '#9E005910', color: '#9E0059' }}>{s}</span>
                            ))}
                        </div>
                    </div>
                )}

                {/* About company */}
                {job.organization && (
                    <div>
                        <h2 className="mb-4 text-base font-bold text-gray-900">About the Company</h2>
                        <div className="flex items-center gap-3 mb-3">
                            <div className="flex h-12 w-12 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl border border-gray-100 bg-gray-50 p-1.5">
                                {job.organization.logo
                                    ? <img src={`/storage/${job.organization.logo}`} alt="" className="h-full w-full object-cover rounded-lg" />
                                    : <span className="text-xl font-bold" style={{ color: '#9E0059' }}>{(job.organization.name ?? 'J')[0]}</span>}
                            </div>
                            <div>
                                <p className="font-semibold text-gray-900">{job.organization.name}</p>
                                <p className="text-xs text-gray-400 mt-0.5">{job.organization.industry ?? 'Company'}</p>
                            </div>
                        </div>
                        {job.organization.description && (
                            <p className="text-sm leading-relaxed text-gray-600">{job.organization.description}</p>
                        )}
                    </div>
                )}
            </div>
        </div>
    );
}

// ─── MobileFilterSheet ───────────────────────────────────────────────────────
function MobileFilterSheet({ f, setF, onClose, onApply, onClear }) {
    const sel = (label, field, opts) => (
        <div>
            <label className="mb-1 block text-xs font-medium text-gray-500">{label}</label>
            <select value={f[field] ?? ''} onChange={e => setF(p => ({ ...p, [field]: e.target.value }))}
                className="w-full rounded-xl border border-gray-200 bg-white py-3 px-4 text-sm text-gray-700 outline-none focus:border-[#9E0059]">
                <option value="">Select…</option>
                {opts.map(([v, l]) => <option key={v} value={v}>{l}</option>)}
            </select>
        </div>
    );
    return createPortal(
        <div className="lg:hidden fixed inset-0 z-50 flex flex-col justify-end bg-black/30 backdrop-blur-sm" onClick={e => e.target === e.currentTarget && onClose()}>
            <div className="rounded-t-3xl bg-white px-5 pb-8 pt-3">
                <div className="mx-auto mb-4 h-1.5 w-10 rounded-full bg-gray-300" />
                <div className="flex items-center justify-between mb-6">
                    <h3 className="text-base font-bold text-gray-900">Filters</h3>
                    <button onClick={onClose} className="rounded-full p-1 text-gray-400 hover:bg-gray-100">{Ico.x}</button>
                </div>
                <div className="space-y-3">
                    {sel('Sort by', 'sort', [['newest','Newest first'],['salary_high','Highest salary'],['salary_low','Lowest salary']])}
                    {sel('Date posted', 'datePosted', [['24h','Last 24h'],['week','Last week'],['month','Last month'],['3months','Last 3 months']])}
                    {sel('Experience level', 'experience', [['entry','Entry'],['mid','Mid'],['senior','Senior'],['executive','Executive']])}
                    {sel('Job type', 'type', Object.entries(EMPLOYMENT_LABELS))}
                    {sel('Job shift', 'jobShift', [['day','Day'],['night','Night'],['morning','Morning'],['flexible','Flexible']])}
                </div>
                <div className="mt-5 flex items-center gap-3">
                    <button onClick={onClear} className="flex items-center gap-1.5 text-sm font-medium text-gray-500">{Ico.x} Clear</button>
                    <button onClick={onApply} className="ml-auto rounded-full px-8 py-3 text-sm font-semibold text-white" style={{ background: '#9E0059' }}>Apply Filters</button>
                </div>
            </div>
        </div>,
        document.body
    );
}

// ─── MobileSearchOverlay ─────────────────────────────────────────────────────
function MobileSearchOverlay({ initialQ, onClose, onSearch }) {
    const [val, setVal] = useState(initialQ ?? '');
    const [recents, setRecents] = useState(getRecents);
    const ref = useRef(null);
    useEffect(() => { setTimeout(() => ref.current?.focus(), 50); }, []);

    function submit(term) {
        const v = (term ?? val).trim();
        onClose();
        if (v) saveRecent(v);
        setRecents(getRecents());
        onSearch(v, '');
    }

    function removeRecent(term) {
        try {
            const updated = getRecents().filter(s => s !== term);
            localStorage.setItem('jfy_recents', JSON.stringify(updated));
            setRecents(updated);
        } catch {}
    }

    return createPortal(
        <div className="fixed inset-0 z-[60] flex flex-col bg-white">
            {/* top bar */}
            <div className="flex items-center gap-3 border-b border-gray-100 px-4 py-3">
                <button onClick={onClose} className="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 transition">{Ico.back}</button>
                <div className="flex flex-1 items-center gap-2 rounded-full border border-gray-300 bg-gray-50 px-4 py-2">
                    <span className="text-gray-400">{Ico.search}</span>
                    <input ref={ref} value={val} onChange={e => setVal(e.target.value)}
                        onKeyDown={e => e.key === 'Enter' && submit()}
                        placeholder="Search jobs..."
                        className="flex-1 bg-transparent text-sm text-gray-800 placeholder-gray-400 outline-none" />
                    {val && <button onClick={() => setVal('')} className="text-gray-400">{Ico.x}</button>}
                </div>
            </div>
            {/* body */}
            <div className="flex-1 overflow-y-auto px-4 py-5 space-y-6">
                {recents.length > 0 && (
                    <div>
                        <div className="mb-2 flex items-center justify-between">
                            <p className="text-xs font-semibold uppercase tracking-wide text-gray-400">Recent searches</p>
                            <button onClick={() => { try { localStorage.setItem('jfy_recents','[]'); } catch {} setRecents([]); }} className="text-xs text-gray-400 hover:text-gray-600">Clear all</button>
                        </div>
                        <ul className="divide-y divide-gray-50">
                            {recents.map(term => (
                                <li key={term} className="flex items-center gap-3 py-2.5">
                                    {Ico.clock}
                                    <button className="flex-1 text-left text-sm text-gray-700" onClick={() => submit(term)}>{term}</button>
                                    <button onClick={() => removeRecent(term)} className="text-gray-300 hover:text-gray-500">{Ico.x}</button>
                                </li>
                            ))}
                        </ul>
                    </div>
                )}
                <div>
                    <p className="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-400">Popular searches</p>
                    <ul className="divide-y divide-gray-50">
                        {POPULAR.map(term => (
                            <li key={term} className="flex items-center gap-3 py-2.5">
                                <span className="text-gray-400">{Ico.search}</span>
                                <button className="flex-1 text-left text-sm text-gray-700" onClick={() => submit(term)}>{term}</button>
                            </li>
                        ))}
                    </ul>
                </div>
            </div>
        </div>,
        document.body
    );
}

// ─── Main component ──────────────────────────────────────────────────────────
export default function Jobs({ jobs, filters, selectedJob: initialSelected, appliedIds = [] }) {
    const [f, setF]               = useState(filters ?? {});
    const [selected, setSelected] = useState(initialSelected ?? null);
    const [mobileView, setMobileView]   = useState('list'); // 'list' | 'detail'
    const [showFilters, setShowFilters] = useState(false);
    const [searchOpen, setSearchOpen]   = useState(false);
    const [tab, setTab]                 = useState('feed');
    const [modal, setModal]             = useState(null);

    // auto-select first job on desktop when results change
    useEffect(() => {
        if (!selected && jobs?.data?.length > 0) setSelected(jobs.data[0]);
    }, [jobs?.data]);

    // debounced search on filter changes
    useEffect(() => {
        const t = setTimeout(() => doSearch(f.q ?? '', f.location ?? '', f), 400);
        return () => clearTimeout(t);
    }, [f.sort, f.datePosted, f.experience, f.type, f.jobShift]);

    function doSearch(kw, loc, extra = {}) {
        const params = { ...extra, q: kw || undefined, location: loc || undefined };
        Object.keys(params).forEach(k => !params[k] && delete params[k]);
        router.get('/find-jobs', params, { preserveState: true, replace: true });
    }

    function selectJob(job) {
        setSelected(job);
        setMobileView('detail');
        router.get('/find-jobs', { ...buildParams(), selected: job.uuid }, { preserveState: true, replace: true, only: ['selectedJob'] });
    }

    function buildParams() {
        const p = { ...f };
        Object.keys(p).forEach(k => !p[k] && delete p[k]);
        return p;
    }

    function clearFilters() {
        setF({});
        router.get('/find-jobs', {}, { preserveState: true, replace: true });
    }

    return (
        <>
            <style>{`
                .scrollbar-hide { scrollbar-width: none; }
                .scrollbar-hide::-webkit-scrollbar { display: none; }
                select { -webkit-appearance: none; -moz-appearance: none; appearance: none; }
            `}</style>

            {/* Auth modal */}
            <JobifyAuthModal show={!!modal} type={modal} onClose={() => setModal(null)} />

            {/* Mobile search overlay */}
            {searchOpen && (
                <MobileSearchOverlay
                    initialQ={f.q}
                    onClose={() => setSearchOpen(false)}
                    onSearch={(kw, loc) => { setF(p => ({ ...p, q: kw, location: loc })); doSearch(kw, loc, f); }}
                />
            )}

            {/* Mobile filter sheet */}
            {showFilters && (
                <MobileFilterSheet
                    f={f} setF={setF}
                    onClose={() => setShowFilters(false)}
                    onApply={() => { setShowFilters(false); doSearch(f.q, f.location, f); }}
                    onClear={() => { clearFilters(); setShowFilters(false); }}
                />
            )}

            {/* Full-height shell */}
            <div className="flex h-screen flex-col bg-[#F3F2EF] antialiased overflow-hidden">

                {/* Navbar */}
                <SearchNavbar
                    q={f.q} location={f.location}
                    onSearch={(kw, loc) => { setF(p => ({ ...p, q: kw, location: loc })); doSearch(kw, loc, f); }}
                    onOpenMobile={() => setSearchOpen(true)}
                    setModal={setModal}
                />

                {/* Filter bar */}
                <FilterBar f={f} setF={setF} onClear={clearFilters} onMobileFilter={() => setShowFilters(true)} />

                {/* Split panel */}
                <div className="flex flex-1 overflow-hidden">
                    <div className="mx-auto flex w-full max-w-7xl gap-2 overflow-hidden p-3">

                        {/* ── Left: job list ── */}
                        <aside className={`scrollbar-hide overflow-y-auto rounded-xl bg-white shadow-sm flex-shrink-0 w-full lg:w-[460px] pb-20 lg:pb-0
                            ${mobileView === 'detail' ? 'hidden lg:block' : 'block'}`}>

                            {/* Tabs */}
                            <div className="flex items-center border-b border-gray-200 sticky top-0 bg-white z-10">
                                {['feed','for-you'].map(t => (
                                    <button key={t} onClick={() => setTab(t)}
                                        className={`relative flex flex-1 items-center justify-center gap-1.5 pb-3 pt-4 text-sm font-semibold transition
                                            ${tab === t ? 'text-[#38009A]' : 'text-gray-400 hover:text-gray-600'}`}>
                                        {t === 'feed' ? 'Job Feed' : <><span>For You</span>{Ico.sparkle}</>}
                                        {tab === t && <span className="absolute bottom-0 left-0 right-0 h-0.5 rounded-full bg-[#38009A]" />}
                                    </button>
                                ))}
                            </div>

                            {tab === 'for-you' ? (
                                <div className="flex flex-col items-center justify-center py-20 text-center px-6">
                                    <div className="mb-3 opacity-30">{Ico.sparkle}</div>
                                    <p className="text-sm font-medium text-gray-400">Personalised jobs coming soon</p>
                                </div>
                            ) : jobs?.data?.length > 0 ? (
                                <>
                                    <div className="px-4 py-2.5 border-b border-gray-100 text-xs text-gray-400">
                                        <span className="font-semibold text-gray-700">{jobs.total}</span> jobs found
                                    </div>
                                    {jobs.data.map(job => (
                                        <JobCard key={job.id} job={job} selected={selected} onClick={() => selectJob(job)} appliedIds={appliedIds} />
                                    ))}
                                    {jobs.next_page_url && (
                                        <div className="py-5 text-center">
                                            <Link href={jobs.next_page_url} className="rounded-full border border-gray-200 px-6 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                                                Load More
                                            </Link>
                                        </div>
                                    )}
                                </>
                            ) : (
                                <div className="flex flex-col items-center justify-center py-20 text-center">
                                    <p className="text-sm font-semibold text-gray-600">No jobs found</p>
                                    <p className="mt-1 text-xs text-gray-400">Try adjusting your search or filters.</p>
                                    <button onClick={clearFilters} className="mt-3 text-xs font-medium" style={{ color: '#9E0059' }}>Clear all filters</button>
                                </div>
                            )}
                        </aside>

                        {/* ── Right: job detail ── */}
                        <main className={`scrollbar-hide flex-1 overflow-y-auto
                            ${mobileView === 'list' ? 'hidden lg:block' : 'block'}`}>
                            {selected ? (
                                <JobDetail job={selected} onBack={() => setMobileView('list')} setModal={setModal} />
                            ) : (
                                <div className="hidden lg:flex h-full items-center justify-center rounded-2xl bg-white shadow-sm">
                                    <p className="text-sm text-gray-400">Select a job to view details</p>
                                </div>
                            )}
                        </main>
                    </div>
                </div>

                {/* Mobile bottom nav */}
                <nav className="lg:hidden fixed bottom-0 left-0 right-0 z-40 border-t border-gray-200 bg-white" style={{ paddingBottom: 'env(safe-area-inset-bottom,0px)' }}>
                    <div className="flex items-center justify-around py-2">
                        <Link href="/" className="flex flex-col items-center gap-0.5 px-4 py-1 text-gray-400">
                            {Ico.home}<span className="text-xs">Home</span>
                        </Link>
                        <Link href="/find-jobs" className="flex flex-col items-center gap-0.5 px-4 py-1" style={{ color: '#9E0059' }}>
                            {Ico.search}<span className="text-xs font-semibold">Search</span>
                        </Link>
                        <Link href="/candidate/dashboard" className="flex flex-col items-center gap-0.5 px-4 py-1 text-gray-400">
                            {Ico.user}<span className="text-xs">Profile</span>
                        </Link>
                    </div>
                </nav>
            </div>
        </>
    );
}
