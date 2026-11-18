import { useRef, useState, useEffect, useCallback, useId } from 'react';
import { createPortal } from 'react-dom';

function getCsrf() {
    return document.head.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

async function postJson(url, body) {
    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': getCsrf(),
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(body),
    });
}

/* ── OTP digit input row ── */
function OtpBoxes({ digits, setDigits, error, idPrefix }) {
    function onInput(i, e) {
        const val = e.target.value.replace(/\D/g, '');
        const next = [...digits];
        next[i] = val ? val[val.length - 1] : '';
        setDigits(next);
        if (val && i < 5) document.getElementById(`${idPrefix}-${i + 1}`)?.focus();
    }
    function onKeydown(i, e) {
        if (e.key === 'Backspace' && !digits[i] && i > 0)
            document.getElementById(`${idPrefix}-${i - 1}`)?.focus();
    }
    function onPaste(e) {
        const text = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
        if (!text) return;
        e.preventDefault();
        const next = Array(6).fill('');
        for (let i = 0; i < text.length; i++) next[i] = text[i];
        setDigits(next);
        document.getElementById(`${idPrefix}-${Math.min(text.length, 5)}`)?.focus();
    }
    return (
        <div className="flex justify-center gap-2">
            {digits.map((d, i) => (
                <input
                    key={i}
                    id={`${idPrefix}-${i}`}
                    value={d}
                    type="text"
                    inputMode="numeric"
                    maxLength={1}
                    onChange={e => onInput(i, e)}
                    onKeyDown={e => onKeydown(i, e)}
                    onPaste={onPaste}
                    className={`h-12 w-12 rounded-xl border-2 text-center text-lg font-bold text-[#9E0059] outline-none transition focus:border-[#9E0059] focus:ring-2 focus:ring-[#9E0059]/20 ${error ? 'border-red-400' : 'border-[#9E0059]/40'}`}
                />
            ))}
        </div>
    );
}

/* ── Resend timer ── */
function useResend(secs = 60) {
    const [countdown, setCountdown] = useState(0);
    const timer = useRef(null);
    const start = useCallback(() => {
        setCountdown(secs);
        clearInterval(timer.current);
        timer.current = setInterval(() => setCountdown(v => { if (v <= 1) { clearInterval(timer.current); return 0; } return v - 1; }), 1000);
    }, [secs]);
    useEffect(() => () => clearInterval(timer.current), []);
    return [countdown, start];
}

/* ══════════════════════════════════════════════
   CANDIDATE MODAL
═══════════════════════════════════════════════ */
function CandidateModal({ onClose }) {
    const [step, setStep] = useState('identifier'); // identifier | otp | cv | parsed
    const [identifier, setIdentifier] = useState('');
    const [identifierErr, setIdentifierErr] = useState('');
    const [sending, setSending] = useState(false);

    const [digits, setDigits] = useState(Array(6).fill(''));
    const [otpErr, setOtpErr] = useState('');
    const [verifying, setVerifying] = useState(false);
    const [countdown, startCountdown] = useResend(60);
    const [devHint, setDevHint] = useState(null);

    const [cvFile, setCvFile] = useState(null);
    const [cvErr, setCvErr] = useState('');
    const [uploading, setUploading] = useState(false);
    const [parsedData, setParsedData] = useState(null);
    const [resumeId, setResumeId] = useState(null);
    const [redirectUrl, setRedirectUrl] = useState('');

    async function sendCode() {
        setIdentifierErr('');
        if (!identifier.trim()) { setIdentifierErr('Please enter your email or phone number.'); return; }
        setSending(true);
        try {
            const res = await postJson('/otp/send', { identifier, role: 'candidate' });
            if (res.status === 422) {
                const d = await res.json();
                setIdentifierErr(d.errors?.identifier?.[0] ?? 'Invalid input.'); return;
            }
            if (!res.ok) { setIdentifierErr('Something went wrong. Please try again.'); return; }
            const d = await res.json();
            setDigits(Array(6).fill(''));
            setOtpErr('');
            setDevHint(d.dev_otp ?? null);
            setStep('otp');
            startCountdown();
            setTimeout(() => document.getElementById('cand-otp-0')?.focus(), 100);
        } catch { setIdentifierErr('Something went wrong. Please try again.'); }
        finally { setSending(false); }
    }

    async function verifyOtp() {
        const otp = digits.join('');
        if (otp.length < 6) { setOtpErr('Please enter all 6 digits.'); return; }
        setOtpErr('');
        setVerifying(true);
        try {
            const res = await postJson('/otp/verify', { otp, identifier, role: 'candidate' });
            if (res.status === 422) {
                const d = await res.json();
                setOtpErr(d.errors?.otp?.[0] ?? 'Invalid code.'); return;
            }
            if (!res.ok) { setOtpErr('Verification failed. Please try again.'); return; }
            const d = await res.json();
            if (d.csrf) {
                const meta = document.head.querySelector('meta[name="csrf-token"]');
                if (meta) meta.setAttribute('content', d.csrf);
            }
            const dest = d.redirect ?? '/candidate/dashboard';
            if (dest.includes('onboarding')) {
                setRedirectUrl(dest);
                setStep('cv');
            } else {
                window.location.href = dest;
            }
        } catch { setOtpErr('Verification failed. Please try again.'); }
        finally { setVerifying(false); }
    }

    async function uploadCV() {
        if (!cvFile) { setCvErr('Please select a file first.'); return; }
        setUploading(true); setCvErr('');
        try {
            const fd = new FormData();
            fd.append('resume', cvFile);
            const res = await fetch('/candidate/onboarding/parse-resume', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': getCsrf(), 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
                body: fd,
            });
            if (!res.ok) { const d = await res.json().catch(() => ({})); setCvErr(d.message ?? 'Upload failed.'); return; }
            const d = await res.json();
            setParsedData(d.parsed);
            setResumeId(d.file_path);
            setStep('parsed');
        } catch { setCvErr('Upload failed. Please try again.'); }
        finally { setUploading(false); }
    }

    async function skipCV() {
        await postJson('/candidate/onboarding', { resume_id: null, phone: '' }).catch(() => {});
        window.location.href = redirectUrl || '/candidate/dashboard';
    }

    async function continueToApp() {
        await postJson('/candidate/onboarding', { resume_path: resumeId, phone: parsedData?.phone ?? '' }).catch(() => {});
        window.location.href = redirectUrl || '/candidate/dashboard';
    }

    return (
        <div className="relative z-10 w-full max-w-md rounded-2xl bg-white px-8 py-10 shadow-2xl">
            <button onClick={onClose} className="absolute right-4 top-4 rounded-full p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}>
                    <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div className="mb-6 flex justify-center">
                <img src="/assets/images/logo/logo.jpg" alt="Jobify" className="h-10 w-auto object-contain" onError={e => { e.target.style.display = 'none'; }} />
            </div>

            {/* Step 1: Identifier */}
            {step === 'identifier' && (
                <>
                    <div className="mb-6 text-center">
                        <h2 className="text-xl font-bold text-gray-900">Let's Get You Started</h2>
                        <p className="mt-1.5 text-sm text-gray-500">Please provide your Mobile number or Email to Sign up / Sign in.</p>
                    </div>
                    <form onSubmit={e => { e.preventDefault(); sendCode(); }} className="space-y-4">
                        <div>
                            <label className="mb-1.5 block text-sm font-medium text-gray-700">Email or Phone Number</label>
                            <input
                                value={identifier}
                                onChange={e => setIdentifier(e.target.value)}
                                type="text"
                                placeholder="name@example.com or +1 555 0000"
                                autoFocus
                                className="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-[#9E0059] focus:outline-none focus:ring-2 focus:ring-[#9E0059]/20 transition"
                            />
                            {identifierErr && <p className="mt-1 text-xs text-red-500">{identifierErr}</p>}
                        </div>
                        <button type="submit" disabled={sending}
                            className="flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-60"
                            style={{ backgroundColor: '#9E0059' }}>
                            <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}>
                                <path strokeLinecap="round" strokeLinejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {sending ? 'Sending…' : 'Send Code'}
                        </button>
                    </form>
                </>
            )}

            {/* Step 2: OTP */}
            {step === 'otp' && (
                <>
                    <div className="mb-6 text-center">
                        <h2 className="text-xl font-bold text-gray-900">Verify OTP Code</h2>
                        <p className="mt-1.5 text-sm text-gray-500">
                            Code sent to <span className="font-medium text-gray-700">{identifier}</span>
                            <button onClick={() => setStep('identifier')} className="ml-1 font-semibold text-[#9E0059] hover:underline">(Update)</button>
                        </p>
                    </div>
                    <form onSubmit={e => { e.preventDefault(); verifyOtp(); }} className="space-y-5">
                        <OtpBoxes digits={digits} setDigits={setDigits} error={!!otpErr} idPrefix="cand-otp" />
                        {otpErr && <p className="text-center text-xs text-red-500">{otpErr}</p>}
                        {devHint && (
                            <div className="rounded-lg border border-amber-200 bg-amber-50 px-4 py-2.5 text-center">
                                <p className="text-xs text-amber-700 font-medium">Dev mode — your OTP is: <span className="font-bold tracking-widest text-amber-900">{devHint}</span></p>
                            </div>
                        )}
                        <p className="text-center text-xs text-gray-500">
                            {countdown > 0
                                ? <>Resend in <span className="font-medium">{countdown} secs</span></>
                                : <button type="button" onClick={() => { setDigits(Array(6).fill('')); sendCode(); }} className="font-semibold text-[#9E0059] hover:underline">Resend Code</button>
                            }
                        </p>
                        <button type="submit" disabled={verifying}
                            className="w-full rounded-xl py-3 text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-60"
                            style={{ backgroundColor: '#9E0059' }}>
                            {verifying ? 'Verifying…' : 'Verify & Login'}
                        </button>
                    </form>
                </>
            )}

            {/* Step 3: CV Upload */}
            {step === 'cv' && (
                <>
                    <div className="mb-5 text-center">
                        <h2 className="text-xl font-bold text-gray-900">Set up your profile</h2>
                        <p className="mt-1.5 text-sm text-gray-500">Upload your CV or build manually</p>
                    </div>

                    <a href="/candidate/onboarding/linkedin"
                        className="mb-4 flex w-full items-center justify-center gap-2.5 rounded-xl border border-[#0A66C2] bg-[#0A66C2] py-3 text-sm font-semibold text-white transition hover:bg-[#004182]">
                        <svg className="h-4 w-4 fill-white shrink-0" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        Import from LinkedIn
                    </a>

                    <div className="mb-4 flex items-center gap-3">
                        <div className="flex-1 border-t border-gray-200"></div>
                        <span className="text-xs text-gray-400">or upload CV</span>
                        <div className="flex-1 border-t border-gray-200"></div>
                    </div>

                    <label className="mb-5 flex cursor-pointer flex-col items-center gap-3 rounded-2xl border-2 border-dashed border-[#9E0059]/40 bg-[#9E0059]/5 px-6 py-8 text-center transition hover:bg-[#9E0059]/10">
                        <input type="file" accept=".pdf,.doc,.docx" className="hidden" onChange={e => { setCvFile(e.target.files[0] ?? null); setCvErr(''); }} />
                        <span className="flex h-12 w-12 items-center justify-center rounded-xl bg-[#9E0059]/10">
                            <svg className="h-6 w-6 text-[#9E0059]" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={1.8}>
                                <path strokeLinecap="round" strokeLinejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                            </svg>
                        </span>
                        <div>
                            <p className="text-sm font-semibold text-gray-800">{cvFile ? cvFile.name : 'Upload your Resume (PDF or Word)'}</p>
                            <p className="mt-0.5 text-xs text-gray-500">All data will be extracted automatically</p>
                        </div>
                    </label>
                    {cvErr && <p className="mb-3 text-center text-xs text-red-500">{cvErr}</p>}

                    <button onClick={uploadCV} disabled={uploading}
                        className="flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-60"
                        style={{ backgroundColor: '#9E0059' }}>
                        {uploading && <svg className="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"/><path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>}
                        {uploading ? 'Extracting data…' : 'Upload Resume'}
                    </button>
                    <button onClick={skipCV} type="button"
                        className="mt-3 w-full rounded-xl border border-[#9E0059] py-3 text-sm font-semibold text-[#9E0059] transition hover:bg-[#9E0059]/5">
                        Skip — Build Resume Manually
                    </button>
                </>
            )}

            {/* Step 4: Parsed */}
            {step === 'parsed' && parsedData && (
                <>
                    <div className="mb-5 text-center">
                        <span className="mb-3 flex justify-center">
                            <span className="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                                <svg className="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                        </span>
                        <h2 className="text-xl font-bold text-gray-900">Profile Extracted!</h2>
                        <p className="mt-1 text-sm text-gray-500">Here's what we found in your CV</p>
                    </div>
                    <div className="space-y-3 rounded-xl border border-gray-100 bg-gray-50 p-4 text-sm">
                        {(parsedData.first_name || parsedData.headline) && (
                            <div>
                                <p className="font-semibold text-gray-900">{[parsedData.first_name, parsedData.last_name].filter(Boolean).join(' ')}</p>
                                {parsedData.headline && <p className="text-xs text-gray-500">{parsedData.headline}</p>}
                            </div>
                        )}
                        {parsedData.skills?.length > 0 && (
                            <div>
                                <p className="mb-1.5 text-xs font-medium uppercase tracking-wide text-gray-400">Skills</p>
                                <div className="flex flex-wrap gap-1.5">
                                    {parsedData.skills.slice(0, 8).map(s => (
                                        <span key={s} className="rounded-full bg-[#9E0059]/10 px-2.5 py-0.5 text-xs font-medium text-[#9E0059]">{s}</span>
                                    ))}
                                    {parsedData.skills.length > 8 && <span className="rounded-full bg-gray-200 px-2.5 py-0.5 text-xs text-gray-500">+{parsedData.skills.length - 8} more</span>}
                                </div>
                            </div>
                        )}
                        {parsedData.experience?.length > 0 && (
                            <div>
                                <p className="mb-1 text-xs font-medium uppercase tracking-wide text-gray-400">Experience</p>
                                {parsedData.experience.slice(0, 2).map((e, i) => (
                                    <div key={i} className="text-xs text-gray-700"><span className="font-medium">{e.title}</span>{e.company && ` · ${e.company}`}</div>
                                ))}
                                {parsedData.experience.length > 2 && <p className="text-xs text-gray-400">+{parsedData.experience.length - 2} more</p>}
                            </div>
                        )}
                    </div>
                    <button onClick={continueToApp}
                        className="mt-4 w-full rounded-xl py-3 text-sm font-semibold text-white transition hover:opacity-90"
                        style={{ backgroundColor: '#9E0059' }}>
                        Continue to Dashboard
                    </button>
                    <button onClick={skipCV} type="button"
                        className="mt-2 w-full rounded-xl border border-gray-200 py-3 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                        Edit Full Profile
                    </button>
                </>
            )}
        </div>
    );
}

/* ══════════════════════════════════════════════
   EMPLOYER MODAL
═══════════════════════════════════════════════ */
function EmployerModal({ onClose }) {
    const [tab, setTab] = useState('signin'); // signin | signup | signup-otp

    // Sign-in form
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [remember, setRemember] = useState(false);
    const [loginErr, setLoginErr] = useState({});
    const [loggingIn, setLoggingIn] = useState(false);

    // Sign-up OTP
    const [empEmail, setEmpEmail] = useState('');
    const [empEmailErr, setEmpEmailErr] = useState('');
    const [empSending, setEmpSending] = useState(false);
    const [empDigits, setEmpDigits] = useState(Array(6).fill(''));
    const [empOtpErr, setEmpOtpErr] = useState('');
    const [empVerifying, setEmpVerifying] = useState(false);
    const [empCountdown, startEmpCountdown] = useResend(60);
    const [empDevHint, setEmpDevHint] = useState(null);

    async function submitLogin(e) {
        e.preventDefault();
        setLoginErr({});
        setLoggingIn(true);
        try {
            const res = await postJson('/employer/login', { email, password, remember });
            if (res.status === 422) {
                const d = await res.json();
                setLoginErr(d.errors ?? {}); return;
            }
            if (!res.ok) { setLoginErr({ email: ['Login failed. Please try again.'] }); return; }
            const d = await res.json();
            if (d.csrf) {
                const meta = document.head.querySelector('meta[name="csrf-token"]');
                if (meta) meta.setAttribute('content', d.csrf);
            }
            window.location.href = d.redirect ?? '/employer/dashboard';
        } catch { setLoginErr({ email: ['Login failed. Please try again.'] }); }
        finally { setLoggingIn(false); }
    }

    async function sendEmpOtp(e) {
        if (e?.preventDefault) e.preventDefault();
        setEmpEmailErr('');
        if (!empEmail.trim()) { setEmpEmailErr('Please enter your email address.'); return; }
        setEmpSending(true);
        try {
            const res = await postJson('/otp/send', { identifier: empEmail, role: 'company_admin' });
            if (res.status === 422) {
                const d = await res.json();
                setEmpEmailErr(d.errors?.identifier?.[0] ?? 'Invalid email.'); return;
            }
            if (!res.ok) { setEmpEmailErr('Something went wrong. Please try again.'); return; }
            const d = await res.json();
            setEmpDigits(Array(6).fill(''));
            setEmpOtpErr('');
            setEmpDevHint(d.dev_otp ?? null);
            setTab('signup-otp');
            startEmpCountdown();
            setTimeout(() => document.getElementById('emp-otp-0')?.focus(), 100);
        } catch { setEmpEmailErr('Something went wrong. Please try again.'); }
        finally { setEmpSending(false); }
    }

    async function verifyEmpOtp(e) {
        e.preventDefault();
        const otp = empDigits.join('');
        if (otp.length < 6) { setEmpOtpErr('Please enter all 6 digits.'); return; }
        setEmpOtpErr('');
        setEmpVerifying(true);
        try {
            const res = await postJson('/otp/verify', { otp, identifier: empEmail, role: 'company_admin' });
            if (res.status === 422) {
                const d = await res.json();
                setEmpOtpErr(d.errors?.otp?.[0] ?? 'Invalid code.'); return;
            }
            if (!res.ok) { setEmpOtpErr('Verification failed. Please try again.'); return; }
            const d = await res.json();
            window.location.href = d.redirect ?? '/employer/setup';
        } catch { setEmpOtpErr('Verification failed. Please try again.'); }
        finally { setEmpVerifying(false); }
    }

    const heading = tab === 'signin' ? 'Welcome Back' : tab === 'signup' ? 'Create Employer Account' : 'Verify Your Email';
    const subtext = tab === 'signin' ? 'Sign in to your employer account.' : tab === 'signup' ? "Enter your email — we'll send you a verification code." : `Code sent to ${empEmail}`;

    return (
        <div className="relative z-10 w-full max-w-md rounded-2xl bg-white px-8 py-8 shadow-2xl">
            <button onClick={onClose} className="absolute right-4 top-4 rounded-full p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}>
                    <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div className="mb-6 flex justify-center">
                <img src="/assets/images/logo/logo.jpg" alt="Jobify" className="h-10 w-auto object-contain" onError={e => { e.target.style.display = 'none'; }} />
            </div>

            <div className="mb-6 text-center">
                <h2 className="text-xl font-bold text-gray-900">{heading}</h2>
                <p className="mt-1.5 text-sm text-gray-500">{subtext}</p>
            </div>

            {/* Sign In */}
            {tab === 'signin' && (
                <form onSubmit={submitLogin} className="space-y-4">
                    <div>
                        <label className="mb-1.5 block text-sm font-medium text-gray-700">Email Address</label>
                        <input value={email} onChange={e => setEmail(e.target.value)} type="email" required autoFocus placeholder="you@company.com"
                            className={`w-full rounded-xl border px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-[#38009A] focus:outline-none focus:ring-2 focus:ring-[#38009A]/20 transition ${loginErr.email ? 'border-red-400' : 'border-gray-200'}`} />
                        {loginErr.email && <p className="mt-1 text-xs text-red-500">{loginErr.email[0]}</p>}
                    </div>
                    <div>
                        <div className="mb-1.5 flex items-center justify-between">
                            <label className="text-sm font-medium text-gray-700">Password</label>
                            <a href="/forgot-password" className="text-xs font-medium text-[#38009A] hover:underline">Forgot password?</a>
                        </div>
                        <input value={password} onChange={e => setPassword(e.target.value)} type="password" required placeholder="••••••••"
                            className={`w-full rounded-xl border px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-[#38009A] focus:outline-none focus:ring-2 focus:ring-[#38009A]/20 transition ${loginErr.password ? 'border-red-400' : 'border-gray-200'}`} />
                        {loginErr.password && <p className="mt-1 text-xs text-red-500">{loginErr.password[0]}</p>}
                    </div>
                    <label className="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" checked={remember} onChange={e => setRemember(e.target.checked)} className="rounded border-gray-300 text-[#38009A] focus:ring-[#38009A]" />
                        <span className="text-sm text-gray-600">Remember me</span>
                    </label>
                    <button type="submit" disabled={loggingIn}
                        className="w-full rounded-xl py-3 text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-60"
                        style={{ backgroundColor: '#38009A' }}>
                        {loggingIn ? 'Signing in…' : 'Sign In'}
                    </button>
                    <p className="text-center text-sm text-gray-500">
                        Don't have an account?{' '}
                        <button type="button" onClick={() => setTab('signup')} className="font-semibold text-[#38009A] hover:underline">Sign Up</button>
                    </p>
                </form>
            )}

            {/* Sign Up: email entry */}
            {tab === 'signup' && (
                <form onSubmit={sendEmpOtp} className="space-y-4">
                    <div>
                        <label className="mb-1.5 block text-sm font-medium text-gray-700">Work Email Address</label>
                        <input value={empEmail} onChange={e => setEmpEmail(e.target.value)} type="email" required autoFocus placeholder="you@company.com"
                            className={`w-full rounded-xl border px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-[#38009A] focus:outline-none focus:ring-2 focus:ring-[#38009A]/20 transition ${empEmailErr ? 'border-red-400' : 'border-gray-200'}`} />
                        {empEmailErr && <p className="mt-1 text-xs text-red-500">{empEmailErr}</p>}
                    </div>
                    <button type="submit" disabled={empSending}
                        className="w-full rounded-xl py-3 text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-60"
                        style={{ backgroundColor: '#38009A' }}>
                        {empSending ? 'Sending…' : 'Send Verification Code'}
                    </button>
                    <p className="text-center text-sm text-gray-500">
                        Already have an account?{' '}
                        <button type="button" onClick={() => setTab('signin')} className="font-semibold text-[#38009A] hover:underline">Sign In</button>
                    </p>
                </form>
            )}

            {/* Sign Up: OTP */}
            {tab === 'signup-otp' && (
                <form onSubmit={verifyEmpOtp} className="space-y-5">
                    <p className="mb-4 text-center text-sm text-gray-500">
                        <button type="button" onClick={() => setTab('signup')} className="font-semibold text-[#38009A] hover:underline">(Change email)</button>
                    </p>
                    <OtpBoxes digits={empDigits} setDigits={setEmpDigits} error={!!empOtpErr} idPrefix="emp-otp" />
                    {empOtpErr && <p className="text-center text-xs text-red-500">{empOtpErr}</p>}
                    {empDevHint && (
                        <div className="rounded-lg border border-amber-200 bg-amber-50 px-4 py-2.5 text-center">
                            <p className="text-xs text-amber-700 font-medium">Dev mode — your OTP is: <span className="font-bold tracking-widest text-amber-900">{empDevHint}</span></p>
                        </div>
                    )}
                    <p className="text-center text-xs text-gray-500">
                        {empCountdown > 0
                            ? <>Resend in <span className="font-medium">{empCountdown}s</span></>
                            : <button type="button" onClick={sendEmpOtp} className="font-semibold text-[#38009A] hover:underline">Resend Code</button>
                        }
                    </p>
                    <button type="submit" disabled={empVerifying}
                        className="w-full rounded-xl py-3 text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-60"
                        style={{ backgroundColor: '#38009A' }}>
                        {empVerifying ? 'Verifying…' : 'Verify & Continue'}
                    </button>
                </form>
            )}
        </div>
    );
}

/* ══════════════════════════════════════════════
   EXPORTED WRAPPER — renders both modals via portal
═══════════════════════════════════════════════ */
export default function JobifyAuthModal({ show, type, onClose }) {
    useEffect(() => {
        document.body.style.overflow = show ? 'hidden' : '';
        return () => { document.body.style.overflow = ''; };
    }, [show]);

    if (!show) return null;

    const backdrop = (
        <div
            className="fixed inset-0 z-50 flex items-center justify-center p-4"
            onClick={e => { if (e.target === e.currentTarget) onClose(); }}
        >
            <div className="absolute inset-0 bg-black/40 backdrop-blur-sm" onClick={onClose}></div>
            {type === 'employer' ? <EmployerModal onClose={onClose} /> : <CandidateModal onClose={onClose} />}
        </div>
    );

    return createPortal(backdrop, document.body);
}
