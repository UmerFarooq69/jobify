import { Link } from '@inertiajs/react';
import { useState } from 'react';
import JobifyPublicLayout from '../../Layouts/JobifyPublicLayout';

const BG_CYCLE = ['#FFEEE6', '#EBE6F5', '#FFE2D4'];

const POPULAR_SEARCHES = ['Graphic Designers', 'IT Professionals', 'Civil Engineers', 'Sales Managers'];

const TESTIMONIALS = [
    {
        text: '"Jobify made my job search so much easier. I found opportunities that matched my skills and experience, and within a short time, I landed the right job for my career goals."',
        name: 'Johnathan Doe',
        img: '/assets/images/home/testimonial-johnathan.png',
        bg: '#F2F3F6',
    },
    {
        text: '"I had been searching for the right opportunity for months. With Jobify, I discovered a role that perfectly aligned with my career goals and strengths."',
        name: 'Rebecca Smith',
        img: '/assets/images/home/testimonial-rebecca.png',
        bg: '#F2F3F6',
    },
    {
        text: '"Jobify connected me with opportunities that matched my experience instead of making me search through endless irrelevant jobs. It made my job search much easier."',
        name: 'Catherine Zeta',
        img: '/assets/images/home/testimonial-catherine.png',
        bg: '#F2F3F6',
    },
];

const TRUST_POINTS = [
    { title: 'Verified job posts from verified employers', icon: '/assets/images/icons/check.png' },
    { title: 'Transparent matching, no hidden criteria',   icon: '/assets/images/icons/envelope.png' },
    { title: 'Easy Apply — skip account creation altogether', icon: '/assets/images/icons/briefcase.png' },
    { title: 'Data privacy & security you can trust',     icon: '/assets/images/icons/shield.png' },
];

const STATIC_EMPLOYERS = [
    { name: 'Aetna',             bg: '#EBE6F5',                img: '/assets/images/home/employer-aetna.png' },
    { name: 'Alico',             bg: '#F2F3F6',                img: '/assets/images/home/employer-alico.png' },
    { name: 'AlKoot',            bg: 'rgba(15,117,188,0.1)',   img: '/assets/images/home/employer-alkoot.png' },
    { name: 'Alkhaleej Takaful', bg: '#9E0059',                img: '/assets/images/home/employer-alkhaleej.png' },
    { name: 'Aetna',             bg: 'rgba(0,75,147,0.08)',    img: '/assets/images/home/employer-aetna2.png' },
];

const NEWS = [
    { type: 'News',    date: '2d ago', title: 'New opportunities presented by top companies in Qatar',  location: 'Doha, Qatar', img: '/assets/images/home/news-1.png' },
    { type: 'Article', date: '2d ago', title: 'Why working in Qatar is good for your career?',          location: 'Doha, Qatar', img: '/assets/images/home/news-2.png' },
    { type: 'Event',   date: '2d ago', title: 'Companies hosting "Career Day" at Central Hall, Qatar',  location: 'Doha, Qatar', img: '/assets/images/home/news-3.png' },
    { type: 'Article', date: '2d ago', title: 'Top companies to work with in Middle-East in 2025',      location: 'Doha, Qatar', img: '/assets/images/home/news-4.png' },
];

const JOURNEY_CARDS = [
    {
        bg: '#EEE9FA',
        title: 'Know your fit before you apply',
        body: 'Get an AI-powered match score and a clear breakdown of how your CV fits the role, so you can focus on opportunities that suit your strengths.',
        cta: 'See Jobs that Match',
        href: '/find-jobs',
        imgSide: 'right',
        img: '/assets/images/home/slider1.png',
        imgAlt: 'Know your fit',
    },
    {
        bg: '#FCEEF5',
        title: 'See what makes you a strong candidate',
        body: 'Discover which skills and experiences match the job requirements, and know exactly what to highlight in your application.',
        cta: 'Explore My Strengths',
        href: '/find-jobs',
        imgSide: 'left',
        img: '/assets/images/home/slider2.png',
        imgAlt: 'Strong candidate',
    },
    {
        bg: '#F5EEF8',
        title: 'Gap Analysis and Ways to Improve',
        body: 'Spot missing skills, experience, or evidence in your CV. Get practical suggestions to showcase your achievements and make your application more compelling.',
        cta: 'Improve My Application',
        href: '/candidate/skill-gap',
        imgSide: 'right',
        img: '/assets/images/home/slider3.png',
        imgAlt: 'Gap analysis',
    },
    {
        bg: '#FEF9EE',
        title: 'Job Summary & Interview Preparation',
        body: 'Turn your match insights into focused preparation. Know your strongest talking points, prepare relevant examples, and get ready for any interview.',
        cta: 'Prepare for My Interview',
        href: '/candidate/interview-prep',
        imgSide: 'left',
        img: '/assets/images/home/slider4.png',
        imgAlt: 'Interview preparation',
    },
];

export default function Home({ featuredJobs, topEmployers, industries, stats }) {
    const [search, setSearch] = useState('');

    const safeJobs = featuredJobs ?? [];
    const safeEmployers = topEmployers ?? [];
    const safeIndustries = industries ?? [];

    const employerList = safeEmployers.length
        ? safeEmployers.map(e => ({ name: e.name, bg: '#EBE6F5', img: e.logo ? `/storage/${e.logo}` : null }))
        : STATIC_EMPLOYERS;

    const tripleEmployers = [...employerList, ...employerList, ...employerList];

    function handleSearch(e) {
        e.preventDefault();
        window.location.href = `/find-jobs${search ? `?q=${encodeURIComponent(search)}` : ''}`;
    }

    return (
        <JobifyPublicLayout>

            {/* ─── HERO ─── */}
            <section className="px-4 sm:px-6 pt-10 sm:pt-16 pb-0">
                <div className="mx-auto max-w-7xl">

                    {/* Title */}
                    <h1 className="mb-6 text-center font-bold text-[36px] leading-[1.1] tracking-[-0.03em] text-[#1F0055] sm:text-[52px] md:text-[68px]">
                        Smarter Way to<br />
                        Find Jobs with <span className="text-[#9E0059]">AI</span>
                    </h1>

                    {/* Search bar */}
                    <form
                        onSubmit={handleSearch}
                        className="mx-auto mb-6 flex max-w-3xl rounded-full border border-[#E7E6E9] bg-white shadow-[0px_12px_24px_0px_rgba(35,32,40,0.06)]"
                    >
                        <div className="flex min-w-0 flex-1 items-center gap-2 px-4 py-3 sm:px-7 sm:py-5">
                            <svg className="h-4 w-4 flex-shrink-0 text-[#5C5469] sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input
                                value={search}
                                onChange={e => setSearch(e.target.value)}
                                type="text"
                                placeholder="Job title or keywords"
                                className="min-w-0 flex-1 border-none bg-transparent p-0 text-sm text-[#0B001F] placeholder-[#5C5469] outline-none ring-0 focus:border-none focus:ring-0 sm:text-lg"
                            />
                        </div>
                        <div className="flex flex-shrink-0 items-center px-1.5 sm:px-2 sm:pr-3">
                            <button
                                type="submit"
                                className="flex h-9 flex-shrink-0 items-center gap-1.5 rounded-full px-3 text-xs font-medium text-white transition sm:h-12 sm:gap-2 sm:px-6 sm:text-base"
                                style={{ background: '#9E0059' }}
                            >
                                <img src="/assets/images/icons/li_sparkle.png" className="h-4 w-4 sm:h-5 sm:w-5" alt="" />
                                Search
                            </button>
                        </div>
                    </form>

                    {/* Popular Searches */}
                    <div className="mb-8 sm:mb-12 flex flex-wrap items-center justify-center gap-2 sm:gap-3">
                        <span className="text-sm font-medium text-[#38009A] sm:text-base">Popular Searches:</span>
                        {POPULAR_SEARCHES.map(tag => (
                            <Link
                                key={tag}
                                href={`/find-jobs?q=${encodeURIComponent(tag)}`}
                                className="flex items-center gap-1.5 rounded-full border border-[#D6DAE4] bg-white px-3 py-1.5 text-sm text-[#5C5469] hover:border-[#9E0059] hover:text-[#9E0059] transition sm:px-4 sm:py-2 sm:text-base"
                            >
                                <svg className="h-3.5 w-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                {tag}
                            </Link>
                        ))}
                    </div>

                    {/* CV banner */}
                    <div
                        className="relative overflow-hidden rounded-[24px] sm:rounded-[36px]"
                        style={{ background: 'linear-gradient(180deg, #38009A 0%, #9E0059 100%)', minHeight: '200px' }}
                    >
                        <span className="absolute right-[470px] top-28 z-10 hidden rounded-full bg-white/20 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm lg:block">Smart Skill Matching</span>
                        <span className="absolute right-[110px] top-20 z-10 hidden rounded-full bg-white/20 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm lg:block">Career Growth</span>
                        <span className="absolute bottom-6 right-[470px] z-10 hidden rounded-full bg-white/20 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm lg:block">Intelligent Search</span>
                        <span className="absolute bottom-10 right-24 z-10 hidden rounded-full bg-white/20 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm lg:block">Easy Apply</span>

                        <img src="/assets/images/home/cv-person-1.png" alt="" className="pointer-events-none absolute top-0 z-[1] hidden h-[260%] w-auto lg:block" style={{ right: '16%' }} />
                        <img src="/assets/images/home/cv-person-2.png" alt="" className="pointer-events-none absolute top-0 z-[2] hidden h-[290%] w-auto lg:block" style={{ right: '1%' }} />

                        <div className="relative z-20 px-6 py-8 sm:px-16 sm:py-12">
                            <h2 className="mb-2 text-2xl font-bold leading-tight text-white sm:text-4xl">Haven't added a resume yet?</h2>
                            <p className="mb-6 text-lg text-white/60">Let our smart AI search work for you.</p>
                            <div className="flex flex-wrap gap-3">
                                <Link
                                    href="/candidate/onboarding"
                                    className="rounded-full px-6 py-2.5 text-base font-medium transition"
                                    style={{ background: '#F5E6EE', color: '#9E0059' }}
                                >
                                    Upload a CV
                                </Link>
                                <Link
                                    href="/candidate/resume-generator"
                                    className="rounded-full border border-white px-6 py-2.5 text-base font-medium text-white hover:bg-white/10 transition"
                                >
                                    Build a Resume
                                </Link>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            {/* ─── TOP INDUSTRIES ─── */}
            <section className="bg-white px-6 py-20">
                <div className="mx-auto max-w-7xl">
                    <h2 className="mb-8 text-center font-bold text-[28px] leading-[34px] tracking-[-0.019em] text-[#1F0055] sm:mb-12 sm:text-[42px] sm:leading-[48px]">
                        Top Industries <span className="text-[#9E0059]">Hiring Now</span>
                    </h2>

                    {safeIndustries.length === 0 ? (
                        <p className="text-center text-sm text-gray-400">No active industries yet. Post jobs to see them here.</p>
                    ) : (
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            {safeIndustries.map((ind, i) => (
                                <Link
                                    key={ind.id}
                                    href={`/find-jobs?job_type_id=${ind.id}`}
                                    className="flex items-center gap-4 rounded-[24px] border border-[#D6DAE4] bg-white px-6 py-6 hover:shadow-md transition"
                                >
                                    <div
                                        className="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full overflow-hidden"
                                        style={{ background: BG_CYCLE[i % 3] }}
                                    >
                                        {ind.icon_url
                                            ? <img src={ind.icon_url} className="h-full w-full object-cover" alt="" />
                                            : <span className="text-lg font-bold text-[#9E0059]">{ind.name?.[0]}</span>
                                        }
                                    </div>
                                    <div>
                                        <p className="text-[22px] font-semibold leading-[24px] text-[#121212]">{ind.name}</p>
                                        <p className="mt-1 text-sm text-[#5C5469]">{ind.openings} {ind.openings === 1 ? 'Opening' : 'Openings'}</p>
                                    </div>
                                </Link>
                            ))}
                            <Link
                                href="/find-jobs"
                                className="col-span-1 flex items-center justify-center gap-3 rounded-[24px] border border-[#D6DAE4] bg-white px-6 py-6 hover:shadow-md transition sm:col-span-3"
                            >
                                <svg className="h-5 w-5 text-[#0B001F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span className="text-[22px] font-semibold text-[#0B001F]">Explore More Industries</span>
                            </Link>
                        </div>
                    )}
                </div>
            </section>

            {/* ─── FEATURED JOBS ─── */}
            <section className="bg-[#F2F3F6] px-6 py-20">
                <div className="mx-auto max-w-7xl">
                    <div className="mb-8 flex flex-col gap-4 sm:mb-10 sm:flex-row sm:items-center sm:justify-between">
                        <h2 className="font-bold text-[28px] leading-[34px] tracking-[-0.019em] text-[#22005C] sm:text-[42px] sm:leading-[44px]">
                            Featured <span className="text-[#9E0059]">Jobs</span>
                        </h2>
                        <Link
                            href="/find-jobs"
                            className="rounded-full border border-[#9E0059] px-6 py-2.5 text-base font-medium text-[#9E0059] hover:bg-[#9E0059] hover:text-white transition"
                        >
                            Search More Jobs
                        </Link>
                    </div>

                    {safeJobs.length > 0 ? (
                        <div className="grid gap-4 sm:grid-cols-2">
                            {safeJobs.map(job => (
                                <Link
                                    key={job.id}
                                    href={`/find-jobs/${job.uuid}`}
                                    className="group flex items-center gap-4 rounded-[20px] border border-[#C2C9D7] bg-white px-5 py-4 hover:shadow-md transition"
                                >
                                    {/* Company logo */}
                                    <div className="flex h-16 w-16 flex-shrink-0 items-center justify-center overflow-hidden rounded-[14px] border border-[#E7E6E9] bg-white p-1.5">
                                        {job.organization?.logo
                                            ? <img src={`/storage/${job.organization.logo}`} alt="" className="h-full w-full object-contain" />
                                            : <span className="text-2xl font-bold text-[#38009A]/40">{job.organization?.name?.[0] ?? 'J'}</span>
                                        }
                                    </div>
                                    {/* Info */}
                                    <div className="flex flex-1 flex-col gap-1.5 min-w-0">
                                        <p className="text-[17px] font-semibold leading-snug text-[#0B001F] group-hover:text-[#9E0059] transition truncate">{job.title}</p>
                                        <div className="flex items-center gap-2 text-sm text-[#5C5469]">
                                            <span className="truncate">{job.organization?.name ?? 'Company'}</span>
                                            <span className="h-3 w-px flex-shrink-0 bg-[#C2C9D7]"></span>
                                            <span className="text-[#38009A] truncate">{job.location ?? 'Remote'}</span>
                                        </div>
                                        <div className="flex flex-wrap gap-1.5">
                                            <span className="rounded-full border border-[#D6DAE4] px-3 py-1 text-xs text-[#5C5469]">{job.employment_type?.replace('_', ' ') ?? 'Full Time'}</span>
                                            {job.work_type && <span className="rounded-full border border-[#D6DAE4] px-3 py-1 text-xs text-[#5C5469]">{job.work_type}</span>}
                                        </div>
                                    </div>
                                    {/* Right */}
                                    <div className="hidden flex-shrink-0 flex-col items-end gap-2 sm:flex">
                                        <span className="rounded-full bg-[#FFEEE6] px-3 py-1 text-xs font-semibold text-[#E84D00]">Featured</span>
                                        <span className="rounded-full px-5 py-2 text-sm font-medium text-white" style={{ background: '#9E0059' }}>Apply</span>
                                    </div>
                                </Link>
                            ))}
                        </div>
                    ) : (
                        <div className="rounded-[24px] border-2 border-dashed border-[#C2C9D7] py-16 text-center">
                            <p className="text-[#8F8A98]">No active jobs yet — check back soon.</p>
                        </div>
                    )}
                </div>
            </section>

            {/* ─── TOP EMPLOYERS ─── */}
            <section className="overflow-hidden bg-white px-6 py-20">
                <div className="mx-auto max-w-7xl">
                    <h2 className="mb-6 text-center font-bold text-[28px] leading-[34px] tracking-[-0.019em] text-[#22005C] sm:mb-10 sm:text-[42px] sm:leading-[44px]">Top Employers</h2>

                    <div className="mx-auto mb-10 flex max-w-md items-center gap-2 rounded-full border border-[#E7E6E9] bg-white px-6 py-3 shadow-[0px_12px_24px_0px_rgba(35,32,40,0.06)]">
                        <input
                            type="text"
                            placeholder="Filter companies"
                            className="flex-1 border-none bg-transparent p-0 text-base text-[#5C5469] placeholder-[#5C5469] outline-none ring-0 focus:border-none focus:ring-0"
                        />
                        <button className="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full" style={{ background: '#9E0059' }}>
                            <svg className="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>

                    {/* Row 1 — scrolls left */}
                    <div className="mb-5 overflow-hidden">
                        <div className="flex gap-5" style={{ animation: 'scrollLeft 20s linear infinite', width: 'max-content' }}>
                            {tripleEmployers.map((emp, i) => (
                                <div key={`l-${i}`} className="flex w-[340px] flex-shrink-0 flex-col rounded-[32px] border-[1.5px] border-[#D6DAE4] bg-white p-5 cursor-pointer hover:shadow-md transition">
                                    <div className="flex h-[170px] items-center justify-center rounded-[20px] p-6" style={{ background: emp.bg }}>
                                        {emp.img
                                            ? <img src={emp.img} alt={emp.name} className={`max-h-14 max-w-[120px] object-contain${emp.bg === '#9E0059' ? ' brightness-0 invert' : ''}`} />
                                            : <span className="text-3xl font-bold text-[#38009A] opacity-60">{emp.name[0]}</span>
                                        }
                                    </div>
                                    <p className="mt-3 px-1 text-[16px] font-bold text-[#0B001F]">{emp.name}</p>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Row 2 — scrolls right */}
                    <div className="overflow-hidden">
                        <div className="flex gap-5" style={{ animation: 'scrollRight 20s linear infinite', width: 'max-content' }}>
                            {tripleEmployers.map((emp, i) => (
                                <div key={`r-${i}`} className="flex w-[340px] flex-shrink-0 flex-col rounded-[32px] border-[1.5px] border-[#D6DAE4] bg-white p-5 cursor-pointer hover:shadow-md transition">
                                    <div className="flex h-[170px] items-center justify-center rounded-[20px] p-6" style={{ background: emp.bg }}>
                                        {emp.img
                                            ? <img src={emp.img} alt={emp.name} className={`max-h-14 max-w-[120px] object-contain${emp.bg === '#9E0059' ? ' brightness-0 invert' : ''}`} />
                                            : <span className="text-3xl font-bold text-[#38009A] opacity-60">{emp.name[0]}</span>
                                        }
                                    </div>
                                    <p className="mt-3 px-1 text-[16px] font-bold text-[#0B001F]">{emp.name}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </section>

            {/* ─── STATS BAR ─── */}
            <section className="bg-[#F2F3F6] px-6 py-10">
                <div className="mx-auto max-w-7xl grid grid-cols-3 gap-8 text-center">
                    <div>
                        <div className="text-4xl font-bold text-[#9E0059]">{stats?.jobs?.toLocaleString() ?? '0'}</div>
                        <div className="mt-1 text-[#5C5469] text-sm">Active Jobs</div>
                    </div>
                    <div>
                        <div className="text-4xl font-bold text-[#9E0059]">{stats?.companies?.toLocaleString() ?? '0'}</div>
                        <div className="mt-1 text-[#5C5469] text-sm">Companies</div>
                    </div>
                    <div>
                        <div className="text-4xl font-bold text-[#9E0059]">{stats?.candidates?.toLocaleString() ?? '0'}</div>
                        <div className="mt-1 text-[#5C5469] text-sm">Candidates</div>
                    </div>
                </div>
            </section>

            {/* ─── JOURNEY / FEATURE CARDS ─── */}
            <section className="bg-white py-20 px-8">
                <div className="mx-auto max-w-7xl space-y-6">
                    {JOURNEY_CARDS.map((card, idx) => (
                        <div
                            key={idx}
                            className="sticky top-20 grid items-center gap-10 rounded-3xl px-10 py-14 lg:grid-cols-2"
                            style={{ background: card.bg, zIndex: 10 + idx * 10 }}
                        >
                            {card.imgSide === 'right' ? (
                                <>
                                    <div>
                                        <img src="/assets/images/icons/li_sparkle.png" alt="" className="mb-4 h-8 w-8" />
                                        <h2 className="mb-4 text-[26px] font-bold leading-[1.2] text-[#22005C] sm:text-[36px]">{card.title}</h2>
                                        <p className="mb-8 text-base leading-relaxed text-gray-600 max-w-md">{card.body}</p>
                                        <Link href={card.href} className="inline-block rounded-full px-7 py-3 text-sm font-semibold text-white transition hover:opacity-90" style={{ background: '#9E0059' }}>
                                            {card.cta}
                                        </Link>
                                    </div>
                                    <div className="flex justify-center lg:justify-end">
                                        <img src={card.img} alt={card.imgAlt} className="w-full max-w-lg object-contain" />
                                    </div>
                                </>
                            ) : (
                                <>
                                    <div className="flex justify-center lg:justify-start order-2 lg:order-1">
                                        <img src={card.img} alt={card.imgAlt} className="w-full max-w-lg object-contain" />
                                    </div>
                                    <div className="order-1 lg:order-2">
                                        <img src="/assets/images/icons/li_sparkle.png" alt="" className="mb-4 h-8 w-8" />
                                        <h2 className="mb-4 text-[26px] font-bold leading-[1.2] text-[#22005C] sm:text-[36px]">{card.title}</h2>
                                        <p className="mb-8 text-base leading-relaxed text-gray-600 max-w-md">{card.body}</p>
                                        <Link href={card.href} className="inline-block rounded-full px-7 py-3 text-sm font-semibold text-white transition hover:opacity-90" style={{ background: '#9E0059' }}>
                                            {card.cta}
                                        </Link>
                                    </div>
                                </>
                            )}
                        </div>
                    ))}
                </div>
            </section>

            {/* ─── SUCCESS STORIES ─── */}
            <section className="bg-white px-6 py-20">
                <div className="mx-auto max-w-7xl">
                    <h2 className="mb-8 text-center font-bold text-[28px] leading-[36px] tracking-[-0.019em] text-[#1F0055] sm:mb-12 sm:text-[42px] sm:leading-[52px]">
                        Stories of <span className="text-[#9E0059]">Success</span><br />
                        Because Your Experience Matters
                    </h2>
                    <div className="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        {TESTIMONIALS.map(t => (
                            <div
                                key={t.name}
                                className="flex flex-col rounded-[20px] p-6 sm:rounded-[24px] sm:p-9"
                                style={{ background: t.bg }}
                            >
                                <p className="mb-6 flex-1 text-base leading-[1.4] tracking-[-0.007em] text-[#080016] sm:mb-8 sm:text-[20px] sm:leading-[1.3]">{t.text}</p>
                                <div className="flex items-center gap-4">
                                    <img src={t.img} alt={t.name} className="h-14 w-14 flex-shrink-0 rounded-full object-cover" />
                                    <p className="text-xl font-medium text-[#080016]">{t.name}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* ─── WHY TRUST ─── */}
            <section className="px-6 py-12">
                <div className="mx-auto max-w-7xl">
                    <div
                        className="grid items-center gap-8 overflow-hidden rounded-[24px] p-6 sm:gap-12 sm:rounded-[36px] sm:p-12 lg:p-20 lg:grid-cols-2"
                        style={{ background: 'linear-gradient(180deg, #9E0059 3%, rgba(71,39,124,1) 100%)' }}
                    >
                        <div>
                            <h2 className="mb-6 font-bold text-[28px] leading-[36px] tracking-[-0.017em] text-white sm:mb-10 sm:text-[48px] sm:leading-[56px]">
                                <span className="block">Why People</span>
                                <span className="flex items-center gap-2 sm:gap-3">
                                    Trust
                                    <img src="/assets/logo/white-logo.png" alt="Jobify" className="inline-block h-7 sm:h-12 object-contain" />
                                </span>
                            </h2>
                            <div className="flex flex-wrap gap-3">
                                <Link
                                    href="/find-jobs"
                                    className="rounded-full px-7 py-3 text-base font-medium transition"
                                    style={{ background: '#F5E6EE', color: '#9E0059' }}
                                >
                                    Search Jobs
                                </Link>
                                <Link
                                    href="/login"
                                    className="rounded-full border border-white px-7 py-3 text-base font-medium text-white hover:bg-white/10 transition"
                                >
                                    Learn More
                                </Link>
                            </div>
                        </div>
                        <div className="grid grid-cols-2 gap-5">
                            {TRUST_POINTS.map(point => (
                                <div key={point.title} className="flex flex-col gap-4 rounded-[16px] bg-white p-5 sm:gap-6 sm:rounded-[24px] sm:p-8">
                                    <img src={point.icon} alt={point.title} className="h-8 w-8 object-contain" />
                                    <p className="text-base font-semibold leading-snug tracking-[-0.014em] text-[#9E0059] sm:text-[20px] sm:leading-[24px]">{point.title}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </section>

            {/* ─── NEWS & EVENTS ─── */}
            <section className="bg-white px-6 py-20">
                <div className="mx-auto max-w-7xl">
                    <h2 className="mb-6 font-bold text-[28px] leading-[34px] tracking-[-0.019em] text-[#22005C] sm:mb-10 sm:text-[42px] sm:leading-[44px]">Latest News and Events</h2>
                    <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        {NEWS.map(item => (
                            <div key={item.title} className="group flex flex-col overflow-hidden rounded-[20px] border border-[#C2C9D7] bg-white hover:shadow-md transition cursor-pointer">
                                <div className="relative h-44 overflow-hidden bg-[#F5F5F5]">
                                    <img src={item.img} alt={item.title} className="h-full w-full object-cover" />
                                </div>
                                <div className="flex flex-col gap-3 px-3 pb-5 pt-2.5">
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm text-[#5C5C5C]">{item.type}</span>
                                        <span className="text-xs text-[#939393]">{item.date}</span>
                                    </div>
                                    <p className="text-[18px] font-semibold leading-snug tracking-[-0.03em] text-[#121212] group-hover:text-[#9E0059] transition line-clamp-2">{item.title}</p>
                                    <p className="text-sm text-[#595959]">{item.location}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Employer scroll CSS */}
            <style>{`
                @keyframes scrollLeft {
                    0%   { transform: translateX(0); }
                    100% { transform: translateX(-33.333%); }
                }
                @keyframes scrollRight {
                    0%   { transform: translateX(-33.333%); }
                    100% { transform: translateX(0); }
                }
            `}</style>

        </JobifyPublicLayout>
    );
}
