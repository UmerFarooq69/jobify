import { Link, usePage } from '@inertiajs/react';
import { useState } from 'react';
import JobifyAuthModal from '../Components/JobifyAuthModal';

function Navbar() {
    const { props } = usePage();
    const user = props.auth?.user ?? null;
    const [mobileOpen, setMobileOpen] = useState(false);
    const [modal, setModal] = useState(null); // null | 'candidate' | 'employer'

    return (
        <>
            <nav className="sticky top-0 z-30 bg-white border-b border-[#F0EEF4]">
                <div className="flex h-20 w-full items-center px-6 lg:px-8">

                    {/* Logo */}
                    <div className="flex flex-1 items-center">
                        <Link href="/" className="flex items-center">
                            <img src="/assets/images/logo/logo.jpg" alt="Jobify" className="h-10 w-auto object-contain" />
                        </Link>
                    </div>

                    {/* Desktop nav links */}
                    <div className="hidden lg:flex items-center gap-2">
                        <Link href="/find-jobs" className="rounded-full px-4 py-2 text-base font-medium text-[#0B001F] hover:bg-[#F2F3F6] transition">
                            Featured Jobs
                        </Link>
                        <a href="#" className="rounded-full px-4 py-2 text-base font-medium text-[#0B001F] hover:bg-[#F2F3F6] transition">
                            About
                        </a>
                        <button onClick={() => setModal('employer')} className="rounded-full px-4 py-2 text-base font-medium text-[#0B001F] hover:bg-[#F2F3F6] transition">
                            Employers / Post Job
                        </button>
                    </div>

                    {/* Desktop auth */}
                    <div className="flex-1 hidden md:flex items-center justify-end gap-2">
                        {user ? (
                            <Link
                                href={user.role_type === 'company_admin' ? '/employer/dashboard' : '/candidate/dashboard'}
                                className="rounded-full bg-[#9E0059] px-6 py-2.5 text-base font-medium text-white hover:bg-[#7a0044] transition"
                            >
                                Dashboard
                            </Link>
                        ) : (
                            <>
                                <button onClick={() => setModal('candidate')} className="rounded-full px-5 py-2.5 text-base font-medium text-[#0B001F] hover:bg-[#F2F3F6] transition">
                                    Sign In
                                </button>
                                <button onClick={() => setModal('candidate')} className="rounded-full bg-[#9E0059] px-6 py-2.5 text-base font-medium text-white hover:bg-[#7a0044] transition">
                                    Sign Up / Login
                                </button>
                            </>
                        )}
                    </div>

                    {/* Mobile hamburger */}
                    <button onClick={() => setMobileOpen(v => !v)} className="flex lg:hidden items-center justify-center rounded-md p-2 text-[#5C5469] hover:text-[#9E0059]">
                        <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {mobileOpen
                                ? <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                : <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                            }
                        </svg>
                    </button>
                </div>

                {/* Mobile menu */}
                {mobileOpen && (
                    <div className="border-t border-[#E7E6E9] bg-white lg:hidden">
                        <div className="space-y-1 px-6 py-4">
                            <Link href="/find-jobs" className="block py-2.5 text-base font-medium text-[#0B001F] hover:text-[#9E0059]" onClick={() => setMobileOpen(false)}>Featured Jobs</Link>
                            <a href="#" className="block py-2.5 text-base font-medium text-[#0B001F] hover:text-[#9E0059]" onClick={() => setMobileOpen(false)}>About</a>
                            <button onClick={() => { setMobileOpen(false); setModal('employer'); }} className="block w-full text-left py-2.5 text-base font-medium text-[#0B001F] hover:text-[#9E0059]">Employers / Post Job</button>
                            <div className="border-t border-[#E7E6E9] pt-3">
                                {user ? (
                                    <Link href={user.role_type === 'company_admin' ? '/employer/dashboard' : '/candidate/dashboard'} className="block py-2 text-base font-semibold text-[#9E0059]">Dashboard</Link>
                                ) : (
                                    <>
                                        <button onClick={() => { setMobileOpen(false); setModal('candidate'); }} className="block w-full text-left py-2 text-base font-medium text-[#5C5469] hover:text-[#9E0059]">Sign In</button>
                                        <button onClick={() => { setMobileOpen(false); setModal('candidate'); }} className="block w-full text-left py-2 text-base font-semibold text-[#9E0059]">Sign Up</button>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>
                )}
            </nav>

            <JobifyAuthModal show={!!modal} type={modal} onClose={() => setModal(null)} />
        </>
    );
}

function Footer() {
    return (
        <footer className="px-6 py-0 pb-12">
            <div className="mx-auto max-w-7xl">
                <div className="overflow-hidden rounded-[24px] px-6 py-10 sm:rounded-[36px] sm:px-12 sm:py-14 lg:px-16 lg:py-16"
                    style={{ background: 'linear-gradient(180deg, #9E0059 0%, rgba(71,39,124,1) 100%)' }}>

                    {/* Logo + App Badges */}
                    <div className="mb-10 flex flex-col gap-5 sm:mb-14 sm:flex-row sm:items-center sm:justify-between">
                        <div className="flex items-center gap-3">
                            <img src="/assets/images/logo/logo.jpg" alt="Jobify" className="h-10 w-10 rounded-full object-cover brightness-0 invert" onError={e => { e.target.style.display = 'none'; }} />
                            <span className="text-2xl font-bold text-white">Jobify</span>
                        </div>
                        <div className="flex items-center gap-4">
                            <a href="#" className="flex items-center gap-2 rounded-xl border border-white/30 px-4 py-2 text-sm text-white hover:bg-white/10 transition">
                                <svg className="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.7 9.05 7.4c1.36.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.39-1.32 2.76-2.53 3.99zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/></svg>
                                App Store
                            </a>
                            <a href="#" className="flex items-center gap-2 rounded-xl border border-white/30 px-4 py-2 text-sm text-white hover:bg-white/10 transition">
                                <svg className="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3.18 23.76c.36.2.77.24 1.17.12l12.54-7.24-2.68-2.68-11.03 9.8zM.5 1.57C.18 1.98 0 2.56 0 3.28v17.44c0 .72.18 1.3.5 1.71l.09.09 9.77-9.77v-.23L.59 1.48.5 1.57zM20.37 10.26l-2.63-1.52-3.01 3.01 3.01 3.01 2.65-1.53c.76-.43.76-1.54-.02-1.97zM4.35.12L16.89 7.36l-2.68 2.68L1.52.24C1.93.12 2.34.16 4.35.12z"/></svg>
                                Google Play
                            </a>
                        </div>
                    </div>

                    {/* Links grid */}
                    <div className="mb-10 grid grid-cols-2 gap-8 sm:mb-14 sm:gap-10 md:grid-cols-4">
                        <div>
                            <p className="mb-4 text-base font-semibold text-white">Jobify</p>
                            <ul className="space-y-3 text-base">
                                <li><a href="#" className="text-white/70 hover:text-white transition">About</a></li>
                                <li><a href="#" className="text-white/70 hover:text-white transition">Blog</a></li>
                                <li><a href="#" className="text-white/70 hover:text-white transition">Research</a></li>
                                <li><a href="/contact" className="text-white/70 hover:text-white transition">Contact Us</a></li>
                                <li><Link href="/find-jobs" className="text-white/70 hover:text-white transition">Find Jobs</Link></li>
                            </ul>
                        </div>
                        <div>
                            <p className="mb-4 text-base font-semibold text-white">Employers</p>
                            <ul className="space-y-3 text-base">
                                <li><Link href="/employer/setup" className="text-white/70 hover:text-white transition">Get an Employer Account</Link></li>
                                <li><Link href="/employer/dashboard" className="text-white/70 hover:text-white transition">Employer Dashboard</Link></li>
                                <li><Link href="/employer/jobs/create" className="text-white/70 hover:text-white transition">Post a Job</Link></li>
                            </ul>
                        </div>
                        <div>
                            <p className="mb-4 text-base font-semibold text-white">Information</p>
                            <ul className="space-y-3 text-base">
                                <li><a href="#" className="text-white/70 hover:text-white transition">Help</a></li>
                                <li><a href="#" className="text-white/70 hover:text-white transition">Terms of Use</a></li>
                                <li><a href="#" className="text-white/70 hover:text-white transition">Privacy Policy</a></li>
                                <li><a href="#" className="text-white/70 hover:text-white transition">Security</a></li>
                            </ul>
                        </div>
                        <div>
                            <p className="mb-4 text-base font-semibold text-white">Candidates</p>
                            <ul className="space-y-3 text-base">
                                <li><Link href="/candidate/dashboard" className="text-white/70 hover:text-white transition">My Dashboard</Link></li>
                                <li><Link href="/candidate/profile" className="text-white/70 hover:text-white transition">My Profile</Link></li>
                                <li><Link href="/candidate/skill-gap" className="text-white/70 hover:text-white transition">Skill Gap AI</Link></li>
                                <li><Link href="/candidate/interview-prep" className="text-white/70 hover:text-white transition">Interview Prep</Link></li>
                            </ul>
                        </div>
                    </div>

                    {/* Bottom row */}
                    <div className="flex flex-col items-center justify-between gap-4 border-t border-white/20 pt-8 sm:flex-row">
                        <p className="text-base text-white/60">&copy; {new Date().getFullYear()} Jobify. All rights reserved.</p>
                        <div className="flex items-center gap-4">
                            {/* X/Twitter */}
                            <a href="#" className="flex h-9 w-9 items-center justify-center rounded-full border border-white/30 text-white/70 hover:border-white hover:text-white transition">
                                <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.741l7.73-8.835L1.254 2.25H8.08l4.254 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                            {/* LinkedIn */}
                            <a href="#" className="flex h-9 w-9 items-center justify-center rounded-full border border-white/30 text-white/70 hover:border-white hover:text-white transition">
                                <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                            {/* Instagram */}
                            <a href="#" className="flex h-9 w-9 items-center justify-center rounded-full border border-white/30 text-white/70 hover:border-white hover:text-white transition">
                                <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                            {/* Facebook */}
                            <a href="#" className="flex h-9 w-9 items-center justify-center rounded-full border border-white/30 text-white/70 hover:border-white hover:text-white transition">
                                <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    );
}

export default function JobifyPublicLayout({ children, hideFooter = false }) {
    return (
        <div className="min-h-screen bg-[#F9F9FB]">
            <div className="relative min-h-screen font-sans antialiased bg-white max-w-[1536px] mx-auto">
                {/* Shade/gradient overlay at top */}
                <div
                    className="pointer-events-none absolute left-0 top-0 z-0 w-full select-none"
                    style={{
                        height: '420px',
                        background: 'linear-gradient(180deg, rgba(158,0,89,0.06) 0%, transparent 100%)',
                    }}
                />
                <Navbar />
                <main className="relative z-10">{children}</main>
                {!hideFooter && <Footer />}
            </div>
        </div>
    );
}
