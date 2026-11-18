import { Link, usePage, router } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';

// ── Inline SVG Icons ──────────────────────────────────────────────────────────
const I = {
    grid:    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>,
    user:    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>,
    clip:    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/></svg>,
    bookmark:<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>,
    bell:    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>,
    search:  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/></svg>,
    file:    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>,
    list:    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>,
    sparkle: <svg viewBox="0 0 24 24" fill="currentColor" className="w-5 h-5 flex-shrink-0"><path d="M12 2l2.09 6.26L20 10l-6.26 2.09L12 18l-2.09-5.91L4 10l5.91-2.09L12 2z"/></svg>,
    briefcase:<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>,
    building:<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>,
    team:    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>,
    shield:  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>,
    chart:   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>,
    cog:     <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>,
    mail:    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>,
    heart:   <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>,
    external:<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>,
    logout:  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>,
    menu:    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} className="w-5 h-5"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>,
    tag:     <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} className="w-5 h-5 flex-shrink-0"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>,
};

// ── Nav definitions ───────────────────────────────────────────────────────────
const CANDIDATE_MAIN = [
    { label: 'Dashboard',       icon: 'grid',     href: '/candidate/dashboard' },
    { label: 'My Profile',      icon: 'user',     href: '/candidate/profile' },
    { label: 'My Applications', icon: 'clip',     href: '/candidate/applications' },
    { label: 'Saved Jobs',      icon: 'bookmark', href: '/candidate/saved-jobs' },
    { label: 'Job Alerts',      icon: 'bell',     href: '/candidate/alerts' },
];
const CANDIDATE_AI = [
    { label: 'Resume Generator', icon: 'file',    href: '/candidate/resume-generator', img: '/assets/images/icons/li_files.png' },
    { label: 'Browse Jobs',      icon: 'search',  href: '/find-jobs',                  img: '/assets/images/icons/search.png'   },
    { label: 'Skill Gap',        icon: 'list',    href: '/candidate/skill-gap',        img: '/assets/images/icons/list.png'     },
    { label: 'Interview Prep',   icon: 'user',    href: '/candidate/interview-prep',   img: '/assets/images/icons/user.png'     },
];
const EMPLOYER_NAV = [
    { label: 'Dashboard',    icon: 'grid',      href: '/employer/dashboard' },
    { label: 'Job Listings', icon: 'briefcase', href: '/employer/jobs' },
    { label: 'Applications', icon: 'clip',      href: '/employer/applications' },
    { label: 'Profile',      icon: 'building',  href: '/employer/profile' },
];
const ADMIN_NAV = [
    { label: 'Dashboard',       icon: 'grid',    href: '/platform-admin',                section: null },
    { label: 'Users',           icon: 'team',    href: '/platform-admin/users',          section: 'People & Content' },
    { label: 'Moderation',      icon: 'shield',  href: '/platform-admin/moderation',    section: null },
    { label: 'Job Categories',  icon: 'tag',     href: '/platform-admin/job-taxonomy',  section: null },
    { label: 'Email Templates', icon: 'mail',    href: '/platform-admin/email-templates', section: null },
    { label: 'Audit Logs',      icon: 'clip',    href: '/platform-admin/audit',         section: 'System' },
    { label: 'System Health',   icon: 'heart',   href: '/platform-admin/health',        section: null },
    { label: 'Settings',        icon: 'cog',     href: '/platform-admin/settings',      section: null },
];

function NavLink({ href, icon, label, active, activeClass, hoverClass }) {
    return (
        <Link href={href} className={`flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-all ${active ? activeClass : `text-gray-600 ${hoverClass}`}`}>
            <span className={active ? '' : 'opacity-60'}>{I[icon]}</span>
            <span className={active ? 'font-semibold' : 'font-medium'}>{label}</span>
        </Link>
    );
}

function computeProfileScore(profile) {
    let s = 0;
    if (profile.headline)                          s += 20;
    if (profile.summary)                           s += 15;
    if ((profile.skills        ?? []).length > 0)  s += 20;
    if ((profile.experience    ?? []).length > 0)  s += 25;
    if ((profile.education     ?? []).length > 0)  s += 20;
    return Math.min(s, 100);
}

function CandidateSidebar({ user, url }) {
    const profile = user.candidate_profile ?? {};
    const score   = computeProfileScore(profile);
    const scoreColor = score >= 80 ? 'bg-green-500' : score >= 50 ? 'bg-yellow-500' : 'bg-red-400';
    const isActive = (href) => url.startsWith(href) && href !== '/find-jobs' || url === href;

    return (
        <div className="space-y-3">
            {/* Profile mini-card */}
            <div className="rounded-2xl bg-white shadow-md ring-1 ring-gray-100 px-3 py-3">
                <div className="flex items-center gap-3">
                    <div className="h-12 w-12 rounded-xl flex-shrink-0 overflow-hidden flex items-center justify-center text-white font-bold text-lg" style={{ background: 'linear-gradient(135deg, #38009A, #9E0059)' }}>
                        {user.first_name?.[0] ?? user.name?.[0] ?? 'U'}
                    </div>
                    <div className="min-w-0">
                        <div className="text-sm font-semibold text-gray-900 truncate">{user.first_name ? `${user.first_name} ${user.last_name ?? ''}`.trim() : user.name}</div>
                        <div className="text-xs text-gray-500 truncate">{profile.headline ?? user.email}</div>
                    </div>
                </div>
            </div>

            {/* Nav card */}
            <div className="rounded-2xl bg-white shadow-md px-2 py-2 space-y-0.5">
                {CANDIDATE_MAIN.map(item => (
                    <NavLink key={item.href} {...item} active={isActive(item.href)}
                        activeClass="bg-[#fdf0f7] text-[#9E0059]"
                        hoverClass="hover:bg-gray-50 hover:text-gray-900" />
                ))}
            </div>

            {/* AI Tools card */}
            <div className="rounded-2xl overflow-hidden shadow-md" style={{ background: '#FFF8E6' }}>
                <div className="px-4 pt-3 pb-2 flex items-center gap-2">
                    <span className="text-amber-600">{I.sparkle}</span>
                    <span className="text-xs font-bold text-amber-800 uppercase tracking-widest">AI Tools</span>
                </div>
                {CANDIDATE_AI.map((item, idx) => (
                    <div key={item.href}>
                        {idx > 0 && <div className="border-t border-orange-100/50" />}
                        <Link href={item.href} className={`flex items-center gap-3 px-4 py-3 text-sm font-semibold transition-all ${url.startsWith(item.href) ? 'bg-orange-100/60 text-orange-900' : 'text-gray-700 hover:bg-orange-100/40'}`}>
                            <img src={item.img} alt="" className="h-5 w-5 object-contain flex-shrink-0" onError={e => { e.target.style.display = 'none'; }} />
                            {item.label}
                        </Link>
                    </div>
                ))}
            </div>

            {/* Completion card */}
            <div className="rounded-2xl bg-white shadow-md px-4 py-3 space-y-2">
                <div className="flex items-center justify-between text-xs">
                    <span className="font-semibold text-gray-700">Profile {score}% complete</span>
                    <Link href="/candidate/profile" className="text-[#9E0059] font-medium hover:underline">Edit</Link>
                </div>
                <div className="h-1.5 rounded-full bg-gray-100">
                    <div className={`h-1.5 rounded-full transition-all ${scoreColor}`} style={{ width: `${score}%` }} />
                </div>
            </div>
        </div>
    );
}

function EmployerSidebar({ user, url, org }) {
    const isActive = (href) => url.startsWith(href);
    return (
        <div className="space-y-3">
            {/* Company card */}
            <div className="rounded-2xl bg-white shadow-md ring-1 ring-gray-100 px-4 py-4">
                <div className="flex items-center gap-3">
                    <div className="h-12 w-12 rounded-full flex-shrink-0 overflow-hidden flex items-center justify-center font-bold text-xl" style={{ background: '#F5E6EE', color: '#9E0059' }}>
                        {org?.logo ? <img src={`/storage/${org.logo}`} alt="" className="w-full h-full object-cover" /> : (org?.name?.[0] ?? 'C')}
                    </div>
                    <div className="min-w-0">
                        <div className="text-sm font-bold text-gray-900 truncate">{org?.name ?? 'Your Company'}</div>
                        <div className="text-xs text-gray-500 truncate">{org?.industry ?? 'Employer'}</div>
                        {org?.verified && <div className="flex items-center gap-1 mt-0.5"><span className="text-blue-600 text-[10px] font-semibold">✓ Verified</span></div>}
                    </div>
                </div>
            </div>

            {/* Nav card */}
            <div className="rounded-2xl bg-white shadow-md px-2 py-2 space-y-0.5">
                {EMPLOYER_NAV.map(item => (
                    <NavLink key={item.href} {...item} active={isActive(item.href)}
                        activeClass="bg-[#EBE6F5] text-[#38009A]"
                        hoverClass="hover:bg-gray-50 hover:text-gray-900" />
                ))}
            </div>

            {/* Post Job CTA */}
            <div className="rounded-2xl bg-white shadow-md ring-1 ring-gray-100 px-4 py-4">
                <p className="text-xs text-gray-500 mb-3">Ready to find great talent?</p>
                <Link href="/employer/jobs/create" className="block w-full rounded-full text-center py-2.5 text-sm font-semibold text-white transition hover:opacity-90" style={{ background: 'linear-gradient(135deg, #38009A, #9E0059)' }}>
                    + Post a Job
                </Link>
            </div>
        </div>
    );
}

function AdminSidebar({ url }) {
    const isActive = (href) => href === '/platform-admin' ? (url === '/platform-admin' || url === '/platform-admin/') : url.startsWith(href);
    let lastSection = null;
    return (
        <div className="space-y-1">
            {/* Identity card */}
            <div className="rounded-2xl px-3 py-3 mb-3 flex items-center gap-3" style={{ background: '#fff', border: '1px solid #F2F3F6' }}>
                <div className="h-9 w-9 rounded-xl flex items-center justify-center flex-shrink-0" style={{ background: '#38009A' }}>
                    {I.shield && <span className="text-white scale-90">{I.shield}</span>}
                </div>
                <div>
                    <div className="text-sm font-bold text-gray-900">Platform Admin</div>
                    <div className="text-[11px] font-medium" style={{ color: '#38009A' }}>Full access</div>
                </div>
            </div>

            {ADMIN_NAV.map((item) => {
                const showSection = item.section && item.section !== lastSection;
                if (showSection) lastSection = item.section;
                return (
                    <div key={item.href}>
                        {showSection && (
                            <div className="px-3 pt-4 pb-1 text-[10px] font-semibold uppercase tracking-wider text-gray-400">{item.section}</div>
                        )}
                        <NavLink {...item} active={isActive(item.href)}
                            activeClass="bg-[#EBE6F5] text-[#38009A]"
                            hoverClass="hover:bg-gray-50 hover:text-gray-900" />
                    </div>
                );
            })}
        </div>
    );
}

// ── Main Layout ───────────────────────────────────────────────────────────────
export default function JobifyLayout({ children, title = 'Dashboard', noPadding = false }) {
    const { url, props } = usePage();
    const flash = props.flash ?? {};
    const user  = props.auth?.user ?? {};
    const org   = props.org ?? user.primary_organization ?? null;
    const [toast, setToast]       = useState(null);
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [bellOpen, setBellOpen]   = useState(false);
    const bellRef = useRef(null);

    useEffect(() => {
        if (flash.success) { setToast({ type: 'success', msg: flash.success }); const t = setTimeout(() => setToast(null), 4000); return () => clearTimeout(t); }
    }, [flash.success]);
    useEffect(() => {
        if (flash.error) { setToast({ type: 'error', msg: flash.error }); const t = setTimeout(() => setToast(null), 4000); return () => clearTimeout(t); }
    }, [flash.error]);
    useEffect(() => {
        const handler = (e) => { if (bellRef.current && !bellRef.current.contains(e.target)) setBellOpen(false); };
        document.addEventListener('mousedown', handler);
        return () => document.removeEventListener('mousedown', handler);
    }, []);

    const isAdmin   = user.role === 'admin' || user.role_type === 'platform_admin';
    const isCompany = ['company_admin','company_hiring_manager','company_recruiter','company_viewer'].includes(user.role_type);
    const mainBg    = isAdmin ? '#F0EFF5' : '#F2F3F6';
    const initials  = (user.first_name ? `${user.first_name} ${user.last_name ?? ''}` : (user.name ?? 'U')).split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase();

    function handleLogout(e) { e.preventDefault(); router.post('/logout'); }

    const SidebarContent = () => (
        <div className="w-[280px] flex-shrink-0 bg-gray-50 h-full overflow-y-auto px-4 py-4">
            {isAdmin   && <AdminSidebar    url={url} />}
            {isCompany && !isAdmin && <EmployerSidebar  url={url} user={user} org={org} />}
            {!isAdmin  && !isCompany && <CandidateSidebar url={url} user={user} />}

            {/* Footer links */}
            <div className="mt-6 space-y-0.5 border-t border-gray-200 pt-4">
                <Link href="/" className="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-all">
                    {I.external} <span>View site</span>
                </Link>
                <button onClick={handleLogout} className="w-full flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-gray-500 hover:bg-red-50 hover:text-red-600 transition-all">
                    {I.logout} <span>Sign out</span>
                </button>
            </div>
        </div>
    );

    return (
        <div className="min-h-screen flex flex-col" style={{ background: mainBg }}>

            {/* ── Top Header ── */}
            <header className="fixed top-0 left-0 right-0 z-30 h-[60px] bg-white border-b border-gray-200 flex items-center px-6 gap-4">
                {/* Logo */}
                <div className="flex items-center gap-2 w-[220px] flex-shrink-0">
                    <img src="/assets/images/logo/logo.jpg" alt="Jobify" className="h-8 object-contain" onError={e => { e.target.style.display = 'none'; }} />
                </div>

                <div className="flex-1" />

                {/* Right controls */}
                <div className="ml-auto flex items-center gap-2">
                    {/* Bell */}
                    <div className="relative" ref={bellRef}>
                        <button onClick={() => setBellOpen(v => !v)} className="relative h-9 w-9 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 transition-all">
                            {I.bell}
                        </button>
                    </div>

                    {/* Avatar */}
                    <div className="h-9 w-9 rounded-xl flex items-center justify-center text-xs font-bold text-white ring-2 ring-white overflow-hidden flex-shrink-0" style={{ background: 'linear-gradient(135deg, #38009A, #9E0059)' }}>
                        {isCompany && org?.logo
                            ? <img src={`/storage/${org.logo}`} alt="" className="w-full h-full object-cover" onError={e => { e.target.style.display = 'none'; }} />
                            : !isCompany && !isAdmin && user.avatar
                            ? <img src={`/storage/${user.avatar}`} alt="" className="w-full h-full object-cover" onError={e => { e.target.style.display = 'none'; }} />
                            : initials}
                    </div>

                    {/* Hamburger mobile */}
                    <button onClick={() => setSidebarOpen(v => !v)} className="lg:hidden h-9 w-9 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 transition-all">
                        {I.menu}
                    </button>
                </div>
            </header>

            {/* ── Body ── */}
            <div className="flex flex-1 pt-[60px] pb-16 lg:pb-0 justify-center">
                <div className="flex w-full max-w-[1440px] items-start min-h-full">

                    {/* Desktop sidebar */}
                    <div className="hidden lg:block sticky top-[60px] h-[calc(100vh-60px)]">
                        {SidebarContent()}
                    </div>

                    {/* Mobile sidebar overlay */}
                    {sidebarOpen && (
                        <div className="fixed inset-0 z-40 lg:hidden">
                            <div className="absolute inset-0 bg-black/40" onClick={() => setSidebarOpen(false)} />
                            <div className="absolute top-[60px] bottom-0 left-0 z-50 overflow-y-auto">
                                {SidebarContent()}
                            </div>
                        </div>
                    )}

                    {/* Content */}
                    <main className={`flex-1 min-w-0 min-h-[calc(100vh-60px)]${noPadding ? '' : ' p-6'}`}>
                        {children}
                    </main>
                </div>
            </div>

            {/* ── Mobile bottom nav (candidates) ── */}
            {!isAdmin && !isCompany && (
                <div className="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 flex lg:hidden">
                    {[
                        { href: '/candidate/dashboard',   icon: 'grid',     label: 'Home'    },
                        { href: '/find-jobs',              icon: 'search',   label: 'Jobs'    },
                        { href: '/candidate/applications', icon: 'clip',     label: 'Applied' },
                        { href: '/candidate/profile',      icon: 'user',     label: 'Profile' },
                    ].map(item => {
                        const active = url.startsWith(item.href);
                        return (
                            <Link key={item.href} href={item.href} className={`flex-1 flex flex-col items-center gap-1 py-3 text-[11px] font-medium transition-all ${active ? 'text-[#9E0059]' : 'text-gray-400'}`}>
                                {active && <div className="absolute top-0 h-0.5 w-8 rounded-full" style={{ background: '#9E0059' }} />}
                                <span>{I[item.icon]}</span>
                                {item.label}
                            </Link>
                        );
                    })}
                </div>
            )}

            {/* ── Toast ── */}
            {toast && (
                <div className="fixed bottom-6 left-1/2 z-50 -translate-x-1/2 flex items-center gap-2.5 rounded-xl bg-gray-900 px-5 py-3 text-sm text-white shadow-lg">
                    <span className={toast.type === 'success' ? 'text-green-400' : 'text-red-400'}>{toast.type === 'success' ? '✓' : '✗'}</span>
                    {toast.msg}
                </div>
            )}
        </div>
    );
}
