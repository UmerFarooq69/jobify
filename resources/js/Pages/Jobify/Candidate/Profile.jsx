import JobifyLayout from '../../../Layouts/JobifyLayout';
import { router, useForm } from '@inertiajs/react';
import { useState, useCallback } from 'react';

const BRAND  = '#9E0059';
const PURPLE = '#38009A';

// ── tiny icons ────────────────────────────────────────────────────────────
const IcoChevron  = ({ open }) => <svg className={`h-4 w-4 text-gray-400 transition-transform duration-200 ${open ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M19 9l-7 7-7-7"/></svg>;
const IcoDone     = () => <svg className="h-5 w-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2.5}><path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7"/></svg>;
const IcoMissing  = () => <svg className="h-5 w-5 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2.5}><path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>;
const IcoUser     = () => <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>;
const IcoBag      = () => <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>;
const IcoCap      = () => <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path strokeLinecap="round" strokeLinejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>;
const IcoStar     = () => <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>;
const IcoDoc      = () => <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>;
const IcoLang     = () => <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>;
const IcoCert     = () => <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>;

// ── helpers ───────────────────────────────────────────────────────────────
function iconBox(icon) {
    return (
        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg" style={{ background: '#FEF0F5', color: BRAND }}>
            {icon}
        </div>
    );
}

function SectionHeader({ icon, title, summary, done, open, onToggle }) {
    return (
        <button type="button" onClick={onToggle}
            className="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-gray-50 transition">
            <div className="flex items-center gap-3 min-w-0">
                {iconBox(icon)}
                <div className="min-w-0">
                    <p className="text-sm font-semibold text-gray-800">{title}</p>
                    <p className="text-xs text-gray-400 truncate max-w-xs mt-0.5">{summary}</p>
                </div>
            </div>
            <div className="flex items-center gap-2 shrink-0 ml-3">
                {done ? <IcoDone /> : <IcoMissing />}
                <IcoChevron open={open} />
            </div>
        </button>
    );
}

function SectionBody({ open, children }) {
    if (!open) return null;
    return <div className="border-t border-gray-100 px-6 py-5 space-y-4">{children}</div>;
}

function SaveBtn({ processing, onClick }) {
    return (
        <div className="flex justify-end pt-1">
            <button type="button" onClick={onClick} disabled={processing}
                className="rounded-lg px-5 py-2 text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-50"
                style={{ background: BRAND }}>
                {processing ? 'Saving…' : 'Save'}
            </button>
        </div>
    );
}

function inputCls(err) {
    return `w-full rounded-lg border px-3 py-2 text-sm focus:outline-none transition ${err ? 'border-red-400' : 'border-gray-200 focus:border-[#9E0059]'}`;
}

// ── completeness ──────────────────────────────────────────────────────────
function getScore(data, resumes) {
    let s = 0;
    if (data.headline)                      s += 20;
    if (data.summary)                       s += 15;
    if ((data.skills ?? []).length > 0)     s += 20;
    if ((data.experience ?? []).length > 0) s += 25;
    if ((data.education ?? []).length > 0)  s += 20;
    return Math.min(s, 100);
}

// ── main ─────────────────────────────────────────────────────────────────
export default function CandidateProfile({ profile, resumes }) {
    const { data, setData, put, processing } = useForm({
        headline:       profile?.headline       ?? '',
        summary:        profile?.summary        ?? '',
        skills:         profile?.skills         ?? [],
        experience:     profile?.experience     ?? [],
        education:      profile?.education      ?? [],
        certifications: profile?.certifications ?? [],
        languages:      [],
    });

    const [open, setOpen]           = useState({ personal: false, experience: false, education: false, skills: false, certifications: false, languages: false, resumes: false });
    const [openExp, setOpenExp]     = useState({});
    const [openEdu, setOpenEdu]     = useState({});
    const [skillInput, setSkillInput] = useState('');
    const [saved, setSaved]         = useState(false);
    const [uploading, setUploading] = useState(false);

    const toggle = (key) => setOpen(p => ({ ...p, [key]: !p[key] }));
    const toggleExp = (i) => setOpenExp(p => ({ ...p, [i]: !p[i] }));
    const toggleEdu = (i) => setOpenEdu(p => ({ ...p, [i]: !p[i] }));

    const score = getScore(data, resumes);

    // summaries
    const experienceSummary    = data.experience.length  ? data.experience.map(e => e.title || 'Untitled').join(', ') : 'None added';
    const educationSummary     = data.education.length   ? data.education.map(e => e.degree || 'Untitled').join(', ')  : 'None added';
    const skillsSummary        = data.skills.length      ? data.skills.slice(0, 5).join(', ') + (data.skills.length > 5 ? '…' : '') : 'None added';
    const certificationsSummary= data.certifications.length ? data.certifications.map(c => c.name || 'Untitled').join(', ') : 'None added';
    const languagesSummary     = data.languages.length   ? data.languages.map(l => l.language || 'Untitled').join(', ') : 'None added';
    const resumesSummary       = (resumes ?? []).length  ? `${resumes.length} file${resumes.length !== 1 ? 's' : ''}` : 'None uploaded';
    const personalSummary      = [data.headline].filter(Boolean).join(' · ') || 'Not set';

    function saveProfile() {
        put('/candidate/profile', { onSuccess: () => { setSaved(true); setTimeout(() => setSaved(false), 3000); } });
    }

    // Skills
    function addSkill() {
        const v = skillInput.trim();
        if (v && !data.skills.includes(v)) setData('skills', [...data.skills, v]);
        setSkillInput('');
    }
    function removeSkill(i) { setData('skills', data.skills.filter((_, idx) => idx !== i)); }

    // Experience
    function addExperience() {
        const updated = [...data.experience, { title: '', company: '', country: '', start: '', end: '', current: false, description: '' }];
        setData('experience', updated);
        setOpenExp(p => ({ ...p, [updated.length - 1]: true }));
        setOpen(p => ({ ...p, experience: true }));
    }
    function removeExperience(i) {
        setData('experience', data.experience.filter((_, idx) => idx !== i));
        setOpenExp(p => { const n = {}; Object.keys(p).forEach(k => { const ki = parseInt(k); if (ki < i) n[ki] = p[ki]; else if (ki > i) n[ki-1] = p[ki]; }); return n; });
    }
    function setExp(i, field, value) {
        const updated = data.experience.map((e, idx) => idx === i ? { ...e, [field]: value } : e);
        setData('experience', updated);
    }

    // Education
    function addEducation() {
        const updated = [...data.education, { degree: '', institution: '', country: '', year: '' }];
        setData('education', updated);
        setOpenEdu(p => ({ ...p, [updated.length - 1]: true }));
        setOpen(p => ({ ...p, education: true }));
    }
    function removeEducation(i) { setData('education', data.education.filter((_, idx) => idx !== i)); }
    function setEdu(i, field, value) {
        setData('education', data.education.map((e, idx) => idx === i ? { ...e, [field]: value } : e));
    }

    // Certifications
    function addCertification() { setData('certifications', [...data.certifications, { name: '', issuer: '', year: '', expiry: '' }]); setOpen(p => ({ ...p, certifications: true })); }
    function removeCertification(i) { setData('certifications', data.certifications.filter((_, idx) => idx !== i)); }
    function setCert(i, field, value) { setData('certifications', data.certifications.map((c, idx) => idx === i ? { ...c, [field]: value } : c)); }

    // Resume upload
    function uploadResume(file) {
        if (!file) return;
        setUploading(true);
        const fd = new FormData();
        fd.append('resume', file);
        fd.append('_token', document.querySelector('meta[name=csrf-token]').content);
        fetch('/candidate/resume/upload', { method: 'POST', body: fd })
            .then(res => { if (res.ok) router.reload({ only: ['resumes'] }); })
            .finally(() => setUploading(false));
    }

    const initials = (profile?.headline || 'U').slice(0, 1).toUpperCase();
    const userName = profile?.user?.name ?? 'User';

    return (
        <JobifyLayout title="My Profile">
            <div className="space-y-3">

                {/* ── Header card ── */}
                <div className="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 p-6">
                    <div className="flex items-center gap-4">
                        {/* Avatar */}
                        <div className="h-16 w-16 shrink-0 rounded-full flex items-center justify-center text-2xl font-bold text-white"
                            style={{ background: BRAND }}>
                            {initials}
                        </div>
                        <div className="flex-1 min-w-0">
                            <h1 className="text-lg font-bold text-gray-900">{userName}</h1>
                            {data.headline && <p className="text-sm text-gray-500 mt-0.5 truncate">{data.headline}</p>}
                        </div>
                        {/* Score ring */}
                        <div className="shrink-0 flex flex-col items-center gap-1">
                            <div className="relative h-14 w-14">
                                <svg viewBox="0 0 36 36" className="h-14 w-14 -rotate-90">
                                    <circle cx="18" cy="18" r="15.5" fill="none" stroke="#F2F3F6" strokeWidth="3"/>
                                    <circle cx="18" cy="18" r="15.5" fill="none" stroke={score >= 80 ? '#22C55E' : score >= 50 ? '#F59E0B' : '#EF4444'} strokeWidth="3"
                                        strokeDasharray={`${(score / 100) * 97.4} 97.4`} strokeLinecap="round"/>
                                </svg>
                                <div className="absolute inset-0 flex items-center justify-center">
                                    <span className="text-xs font-bold text-gray-900">{score}%</span>
                                </div>
                            </div>
                            <span className="text-[11px] text-gray-400">Complete</span>
                        </div>
                    </div>

                    {data.summary && (
                        <div className="mt-5">
                            <h2 className="mb-1.5 text-sm font-semibold text-gray-900">Profile</h2>
                            <p className="text-sm leading-relaxed text-gray-600">{data.summary}</p>
                        </div>
                    )}
                </div>

                {saved && (
                    <div className="flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-medium" style={{ background: '#E6F4EA', color: '#1A7F37' }}>
                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2.5}><path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Profile saved successfully!
                    </div>
                )}

                {/* ── Personal / Professional Info ── */}
                <div className="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 overflow-hidden">
                    <SectionHeader
                        icon={<IcoUser />}
                        title="Personal Information"
                        summary={personalSummary}
                        done={!!(data.headline && data.summary)}
                        open={open.personal}
                        onToggle={() => toggle('personal')} />
                    <SectionBody open={open.personal}>
                        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div className="sm:col-span-2">
                                <label className="block text-xs font-medium text-gray-600 mb-1">Professional Headline</label>
                                <input value={data.headline} onChange={e => setData('headline', e.target.value)}
                                    placeholder="e.g. Senior Laravel Developer | 5 Years Experience"
                                    className={inputCls(false)} />
                            </div>
                            <div className="sm:col-span-2">
                                <label className="block text-xs font-medium text-gray-600 mb-1">Professional Summary</label>
                                <textarea value={data.summary} onChange={e => setData('summary', e.target.value)}
                                    rows={4} placeholder="Brief overview of your professional background..."
                                    className={`${inputCls(false)} resize-none`} />
                            </div>
                        </div>
                        <SaveBtn processing={processing} onClick={saveProfile} />
                    </SectionBody>
                </div>

                {/* ── Work Experience ── */}
                <div className="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 overflow-hidden">
                    <SectionHeader
                        icon={<IcoBag />}
                        title="Work Experience"
                        summary={experienceSummary}
                        done={data.experience.length > 0}
                        open={open.experience}
                        onToggle={() => toggle('experience')} />
                    <SectionBody open={open.experience}>
                        {data.experience.length === 0 && (
                            <p className="py-4 text-center text-sm text-gray-400">No experience added yet.</p>
                        )}
                        {data.experience.map((exp, i) => (
                            <div key={i} className="rounded-xl border border-gray-100 bg-white overflow-hidden">
                                {/* row header */}
                                <div className="flex items-center justify-between px-4 py-3 cursor-pointer hover:bg-gray-50 transition"
                                    onClick={() => toggleExp(i)}>
                                    <div>
                                        <p className="text-sm font-semibold text-gray-700">{exp.title || `Work Experience #${i + 1}`}</p>
                                        {exp.company && <p className="text-xs text-gray-400 mt-0.5">{exp.company}</p>}
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <button type="button" onClick={e => { e.stopPropagation(); removeExperience(i); }}
                                            className="text-xs font-medium rounded-full px-3 py-1 transition hover:opacity-80"
                                            style={{ background: '#DC354514', color: '#DC3545' }}>
                                            Remove
                                        </button>
                                        <IcoChevron open={!!openExp[i]} />
                                    </div>
                                </div>
                                {/* row body */}
                                {openExp[i] && (
                                    <div className="border-t border-gray-100 px-4 pb-4 pt-3 space-y-3">
                                        <div className="grid grid-cols-2 gap-3">
                                            <div>
                                                <label className="block text-xs font-medium text-gray-500 mb-1">Job Title</label>
                                                <input value={exp.title} onChange={e => setExp(i, 'title', e.target.value)} className={inputCls(false)} placeholder="e.g. Senior Developer" />
                                            </div>
                                            <div>
                                                <label className="block text-xs font-medium text-gray-500 mb-1">Company</label>
                                                <input value={exp.company} onChange={e => setExp(i, 'company', e.target.value)} className={inputCls(false)} placeholder="Company name" />
                                            </div>
                                            <div>
                                                <label className="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
                                                <input type="month" value={exp.start} onChange={e => setExp(i, 'start', e.target.value)} className={inputCls(false)} />
                                            </div>
                                            <div>
                                                <label className="block text-xs font-medium text-gray-500 mb-1">End Date</label>
                                                {exp.current
                                                    ? <input type="text" value="Present" disabled className="w-full rounded-lg border border-gray-200 bg-gray-100 px-3 py-2 text-sm text-gray-400 cursor-not-allowed outline-none" />
                                                    : <input type="month" value={exp.end} onChange={e => setExp(i, 'end', e.target.value)} className={inputCls(false)} />
                                                }
                                            </div>
                                            <div className="col-span-2 flex items-center gap-2 -mt-1">
                                                <input id={`cur-${i}`} type="checkbox" checked={!!exp.current}
                                                    onChange={e => setExp(i, 'current', e.target.checked)}
                                                    className="h-4 w-4 rounded border-gray-300 cursor-pointer"
                                                    style={{ accentColor: BRAND }} />
                                                <label htmlFor={`cur-${i}`} className="text-sm text-gray-600 cursor-pointer">I currently work here</label>
                                            </div>
                                            <div className="col-span-2">
                                                <label className="block text-xs font-medium text-gray-500 mb-1">Description</label>
                                                <textarea value={exp.description} onChange={e => setExp(i, 'description', e.target.value)}
                                                    rows={3} className={`${inputCls(false)} resize-none`} placeholder="Describe your responsibilities and achievements..." />
                                            </div>
                                        </div>
                                    </div>
                                )}
                            </div>
                        ))}
                        <div className="flex items-center justify-between pt-1">
                            <button type="button" onClick={addExperience}
                                className="text-sm font-medium transition hover:opacity-70" style={{ color: BRAND }}>
                                + Add Entry
                            </button>
                            <SaveBtn processing={processing} onClick={saveProfile} />
                        </div>
                    </SectionBody>
                </div>

                {/* ── Education ── */}
                <div className="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 overflow-hidden">
                    <SectionHeader
                        icon={<IcoCap />}
                        title="Education"
                        summary={educationSummary}
                        done={data.education.length > 0}
                        open={open.education}
                        onToggle={() => toggle('education')} />
                    <SectionBody open={open.education}>
                        {data.education.length === 0 && (
                            <p className="py-4 text-center text-sm text-gray-400">No education added yet.</p>
                        )}
                        {data.education.map((edu, i) => (
                            <div key={i} className="rounded-xl border border-gray-100 bg-white overflow-hidden">
                                <div className="flex items-center justify-between px-4 py-3 cursor-pointer hover:bg-gray-50 transition"
                                    onClick={() => toggleEdu(i)}>
                                    <div>
                                        <p className="text-sm font-semibold text-gray-700">{edu.degree || `Education #${i + 1}`}</p>
                                        {edu.institution && <p className="text-xs text-gray-400 mt-0.5">{edu.institution}</p>}
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <button type="button" onClick={e => { e.stopPropagation(); removeEducation(i); }}
                                            className="text-xs font-medium rounded-full px-3 py-1"
                                            style={{ background: '#DC354514', color: '#DC3545' }}>
                                            Remove
                                        </button>
                                        <IcoChevron open={!!openEdu[i]} />
                                    </div>
                                </div>
                                {openEdu[i] && (
                                    <div className="border-t border-gray-100 px-4 pb-4 pt-3 space-y-3">
                                        <div className="grid grid-cols-2 gap-3">
                                            <div className="col-span-2">
                                                <label className="block text-xs font-medium text-gray-500 mb-1">Degree / Qualification</label>
                                                <input value={edu.degree} onChange={e => setEdu(i, 'degree', e.target.value)} className={inputCls(false)} placeholder="e.g. BSc Computer Science" />
                                            </div>
                                            <div className="col-span-2">
                                                <label className="block text-xs font-medium text-gray-500 mb-1">Institution</label>
                                                <input value={edu.institution} onChange={e => setEdu(i, 'institution', e.target.value)} className={inputCls(false)} placeholder="University or school name" />
                                            </div>
                                            <div>
                                                <label className="block text-xs font-medium text-gray-500 mb-1">Country</label>
                                                <input value={edu.country ?? ''} onChange={e => setEdu(i, 'country', e.target.value)} className={inputCls(false)} placeholder="e.g. Pakistan" />
                                            </div>
                                            <div>
                                                <label className="block text-xs font-medium text-gray-500 mb-1">Graduation Year</label>
                                                <input value={edu.year} onChange={e => setEdu(i, 'year', e.target.value)} className={inputCls(false)} placeholder="e.g. 2022" />
                                            </div>
                                        </div>
                                    </div>
                                )}
                            </div>
                        ))}
                        <div className="flex items-center justify-between pt-1">
                            <button type="button" onClick={addEducation}
                                className="text-sm font-medium transition hover:opacity-70" style={{ color: BRAND }}>
                                + Add Entry
                            </button>
                            <SaveBtn processing={processing} onClick={saveProfile} />
                        </div>
                    </SectionBody>
                </div>

                {/* ── Skills ── */}
                <div className="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 overflow-hidden">
                    <SectionHeader
                        icon={<IcoStar />}
                        title="Skills"
                        summary={skillsSummary}
                        done={data.skills.length > 0}
                        open={open.skills}
                        onToggle={() => toggle('skills')} />
                    <SectionBody open={open.skills}>
                        <div className="flex gap-2">
                            <input value={skillInput} onChange={e => setSkillInput(e.target.value)}
                                onKeyDown={e => { if (e.key === 'Enter') { e.preventDefault(); addSkill(); } }}
                                placeholder="Add a skill and press Enter…"
                                className={inputCls(false)} />
                            <button type="button" onClick={addSkill}
                                className="shrink-0 rounded-lg px-4 py-2 text-sm font-semibold text-white transition hover:opacity-90"
                                style={{ background: BRAND }}>
                                Add
                            </button>
                        </div>
                        {data.skills.length > 0 && (
                            <div className="flex flex-wrap gap-2 mt-1">
                                {data.skills.map((s, i) => (
                                    <span key={i} className="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-sm font-medium"
                                        style={{ background: '#FEF0F5', color: BRAND }}>
                                        {s}
                                        <button onClick={() => removeSkill(i)} className="transition hover:opacity-60">
                                            <svg className="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={3}><path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </span>
                                ))}
                            </div>
                        )}
                        <SaveBtn processing={processing} onClick={saveProfile} />
                    </SectionBody>
                </div>

                {/* ── Certifications ── */}
                <div className="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 overflow-hidden">
                    <SectionHeader
                        icon={<IcoCert />}
                        title="Certifications"
                        summary={certificationsSummary}
                        done={data.certifications.length > 0}
                        open={open.certifications}
                        onToggle={() => toggle('certifications')} />
                    <SectionBody open={open.certifications}>
                        {data.certifications.length === 0 && (
                            <p className="py-4 text-center text-sm text-gray-400">No certifications added yet.</p>
                        )}
                        {data.certifications.map((cert, i) => (
                            <div key={i} className="rounded-xl border border-gray-100 p-4 space-y-3" style={{ background: '#FAFAFA' }}>
                                <div className="flex items-center justify-between mb-1">
                                    <p className="text-sm font-semibold text-gray-700">{cert.name || `Certification #${i + 1}`}</p>
                                    <button type="button" onClick={() => removeCertification(i)}
                                        className="text-xs font-medium rounded-full px-3 py-1"
                                        style={{ background: '#DC354514', color: '#DC3545' }}>
                                        Remove
                                    </button>
                                </div>
                                <div className="grid grid-cols-2 gap-3">
                                    <div className="col-span-2">
                                        <label className="block text-xs font-medium text-gray-500 mb-1">Certificate Name</label>
                                        <input value={cert.name} onChange={e => setCert(i, 'name', e.target.value)} className={inputCls(false)} placeholder="e.g. AWS Solutions Architect" />
                                    </div>
                                    <div className="col-span-2">
                                        <label className="block text-xs font-medium text-gray-500 mb-1">Issuing Organisation</label>
                                        <input value={cert.issuer} onChange={e => setCert(i, 'issuer', e.target.value)} className={inputCls(false)} placeholder="e.g. Amazon Web Services" />
                                    </div>
                                    <div>
                                        <label className="block text-xs font-medium text-gray-500 mb-1">Issue Year</label>
                                        <input value={cert.year} onChange={e => setCert(i, 'year', e.target.value)} className={inputCls(false)} placeholder="2023" />
                                    </div>
                                    <div>
                                        <label className="block text-xs font-medium text-gray-500 mb-1">Expiry Year</label>
                                        <input value={cert.expiry} onChange={e => setCert(i, 'expiry', e.target.value)} className={inputCls(false)} placeholder="2026 (or leave blank)" />
                                    </div>
                                </div>
                            </div>
                        ))}
                        <div className="flex items-center justify-between pt-1">
                            <button type="button" onClick={addCertification}
                                className="text-sm font-medium transition hover:opacity-70" style={{ color: BRAND }}>
                                + Add Entry
                            </button>
                            <SaveBtn processing={processing} onClick={saveProfile} />
                        </div>
                    </SectionBody>
                </div>

                {/* ── Resumes ── */}
                <div className="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 overflow-hidden">
                    <SectionHeader
                        icon={<IcoDoc />}
                        title="Resumes"
                        summary={resumesSummary}
                        done={(resumes ?? []).length > 0}
                        open={open.resumes}
                        onToggle={() => toggle('resumes')} />
                    <SectionBody open={open.resumes}>
                        <label className="flex items-center justify-center gap-2 rounded-xl border-2 border-dashed cursor-pointer transition hover:opacity-80 py-5"
                            style={{ borderColor: '#C4B5FD', background: '#F5F0FF', color: PURPLE }}>
                            <input type="file" accept=".pdf,.doc,.docx" className="hidden" onChange={e => uploadResume(e.target.files?.[0])} />
                            {uploading
                                ? <span className="text-sm font-medium">Uploading…</span>
                                : <>
                                    <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <span className="text-sm font-medium">Upload Resume (PDF, DOC, DOCX)</span>
                                </>
                            }
                        </label>

                        {(resumes ?? []).length > 0 ? (
                            <div className="mt-3 space-y-2">
                                {resumes.map(r => (
                                    <div key={r.id} className="flex items-center gap-3 rounded-xl px-4 py-3"
                                        style={{ background: '#F8F9FB', border: '1px solid #F2F3F6' }}>
                                        <div className="h-9 w-9 rounded-lg flex items-center justify-center shrink-0"
                                            style={{ background: '#FEF0F5', color: BRAND }}>
                                            <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={1.8}><path strokeLinecap="round" strokeLinejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className="text-sm font-medium truncate text-gray-900">{r.original_name}</p>
                                            {r.is_primary && (
                                                <span className="inline-flex items-center gap-1 text-[11px] font-semibold mt-0.5" style={{ color: BRAND }}>
                                                    <svg className="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={3}><path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                    Primary Resume
                                                </span>
                                            )}
                                        </div>
                                        <a href={`/storage/${r.file_path}`} target="_blank" rel="noopener noreferrer"
                                            className="text-[13px] font-medium transition hover:underline shrink-0" style={{ color: PURPLE }}>
                                            View
                                        </a>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <p className="text-center text-sm py-2 text-gray-400">No resumes uploaded yet</p>
                        )}
                    </SectionBody>
                </div>

            </div>
        </JobifyLayout>
    );
}
