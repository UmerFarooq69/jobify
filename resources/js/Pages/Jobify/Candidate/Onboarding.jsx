import { useState } from 'react';
import { router, usePage } from '@inertiajs/react';
import axios from 'axios';

export default function Onboarding({ user }) {
    const { props } = usePage();
    const [step, setStep] = useState(1);
    const [parsing, setParsing] = useState(false);
    const [form, setForm] = useState({
        headline: '', summary: '',
        skills: [], skillInput: '',
        experience: [], education: [],
    });
    const [errors, setErrors] = useState({});

    const handleResumeUpload = async (e) => {
        const file = e.target.files[0];
        if (!file) return;
        setParsing(true);
        const fd = new FormData();
        fd.append('resume', file);
        fd.append('_token', document.querySelector('meta[name=csrf-token]').content);
        try {
            const res = await axios.post('/candidate/onboarding/parse-resume', fd);
            const p = res.data.parsed;
            if (p) {
                setForm(prev => ({
                    ...prev,
                    headline: p.headline ?? prev.headline,
                    summary: p.summary ?? prev.summary,
                    skills: p.skills ?? prev.skills,
                    experience: p.experience ?? prev.experience,
                    education: p.education ?? prev.education,
                }));
            }
        } catch {}
        setParsing(false);
        setStep(2);
    };

    const addSkill = () => {
        if (form.skillInput.trim()) {
            setForm(prev => ({ ...prev, skills: [...prev.skills, prev.skillInput.trim()], skillInput: '' }));
        }
    };

    const removeSkill = (i) => setForm(prev => ({ ...prev, skills: prev.skills.filter((_, idx) => idx !== i) }));

    const submit = () => {
        router.post('/candidate/onboarding', {
            headline: form.headline,
            summary: form.summary,
            skills: form.skills,
            experience: form.experience,
            education: form.education,
        }, { onError: setErrors });
    };

    return (
        <div className="min-h-screen bg-gradient-to-br from-indigo-50 to-white flex items-center justify-center p-4">
            <div className="w-full max-w-2xl">
                {/* Logo */}
                <div className="text-center mb-8">
                    <div className="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center mx-auto mb-3">
                        <span className="text-white font-bold text-xl">Q</span>
                    </div>
                    <h1 className="text-2xl font-bold text-gray-900">Set up your profile</h1>
                    <p className="text-gray-500 mt-1">Let employers find you faster with AI-powered matching</p>
                </div>

                {/* Step indicator */}
                <div className="flex items-center gap-2 mb-6">
                    {[1,2,3].map(s => (
                        <div key={s} className="flex-1 flex items-center gap-2">
                            <div className={`w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-colors ${step >= s ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500'}`}>{s}</div>
                            {s < 3 && <div className={`flex-1 h-0.5 transition-colors ${step > s ? 'bg-indigo-600' : 'bg-gray-200'}`} />}
                        </div>
                    ))}
                </div>

                <div className="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    {step === 1 && (
                        <div>
                            <h2 className="font-semibold text-gray-900 mb-2">Upload your CV (optional)</h2>
                            <p className="text-gray-500 text-sm mb-4">We'll automatically extract your details using AI</p>
                            <label className="block border-2 border-dashed border-gray-200 rounded-xl p-8 text-center cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/30 transition-all">
                                <input type="file" accept=".pdf,.doc,.docx" className="hidden" onChange={handleResumeUpload} />
                                {parsing ? (
                                    <div className="text-indigo-600 font-medium">Parsing your CV...</div>
                                ) : (
                                    <>
                                        <div className="text-3xl mb-2">📄</div>
                                        <div className="font-medium text-gray-900">Drop your CV here</div>
                                        <div className="text-gray-400 text-sm">PDF, DOC, DOCX up to 5MB</div>
                                    </>
                                )}
                            </label>
                            <div className="text-center mt-4">
                                <button onClick={() => setStep(2)} className="text-indigo-600 text-sm hover:text-indigo-700 font-medium">Skip, I'll fill in manually →</button>
                            </div>
                        </div>
                    )}

                    {step === 2 && (
                        <div className="space-y-4">
                            <h2 className="font-semibold text-gray-900 mb-4">Your Profile</h2>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Professional Headline</label>
                                <input value={form.headline} onChange={e => setForm({...form, headline: e.target.value})}
                                    placeholder="e.g. Senior Software Engineer" className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Professional Summary</label>
                                <textarea value={form.summary} onChange={e => setForm({...form, summary: e.target.value})}
                                    rows={4} placeholder="Brief overview of your experience and goals..."
                                    className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none" />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">Skills</label>
                                <div className="flex gap-2">
                                    <input value={form.skillInput} onChange={e => setForm({...form, skillInput: e.target.value})}
                                        onKeyDown={e => e.key === 'Enter' && (e.preventDefault(), addSkill())}
                                        placeholder="Add skill, press Enter" className="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                                    <button onClick={addSkill} className="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">Add</button>
                                </div>
                                <div className="flex flex-wrap gap-2 mt-2">
                                    {form.skills.map((s, i) => (
                                        <span key={i} className="bg-indigo-50 text-indigo-700 text-xs px-2 py-1 rounded-full flex items-center gap-1">
                                            {s} <button onClick={() => removeSkill(i)} className="text-indigo-400 hover:text-indigo-700">×</button>
                                        </span>
                                    ))}
                                </div>
                            </div>
                            <div className="flex gap-3 pt-2">
                                <button onClick={() => setStep(1)} className="border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Back</button>
                                <button onClick={() => setStep(3)} className="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">Continue</button>
                            </div>
                        </div>
                    )}

                    {step === 3 && (
                        <div>
                            <h2 className="font-semibold text-gray-900 mb-2">All set!</h2>
                            <p className="text-gray-500 text-sm mb-6">Your profile is ready. You can add more details from your profile page.</p>
                            <div className="bg-gray-50 rounded-xl p-4 mb-6 text-sm space-y-2">
                                <div className="flex items-center gap-2"><span className="text-green-600">✓</span> <span>{form.headline || 'Headline'}</span></div>
                                <div className="flex items-center gap-2"><span className="text-green-600">✓</span> <span>{form.skills.length} skills added</span></div>
                            </div>
                            <div className="flex gap-3">
                                <button onClick={() => setStep(2)} className="border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Back</button>
                                <button onClick={submit} className="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">Complete Setup →</button>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
