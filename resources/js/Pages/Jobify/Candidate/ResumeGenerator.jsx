import JobifyLayout from '../../../Layouts/JobifyLayout';
import { useState } from 'react';
import axios from 'axios';

export default function ResumeGenerator({ profile, appliedJobs }) {
    const [selectedJob, setSelectedJob] = useState('');
    const [result, setResult] = useState(null);
    const [loading, setLoading] = useState(false);

    const generate = async () => {
        setLoading(true);
        try {
            const res = await axios.post('/candidate/resume-generator/generate',
                { job_id: selectedJob || undefined },
                { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content } }
            );
            setResult(res.data);
        } catch { alert('Generation failed. Please try again.'); }
        setLoading(false);
    };

    return (
        <JobifyLayout title="AI Resume Generator">
            <div className="max-w-3xl space-y-6">
                <div className="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 className="font-semibold text-gray-900 mb-1">AI Resume Generator</h2>
                    <p className="text-gray-500 text-sm mb-4">Generate an ATS-optimized resume tailored to a specific job</p>
                    <div className="flex gap-3">
                        <select value={selectedJob} onChange={e => setSelectedJob(e.target.value)}
                            className="flex-1 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">General (no specific job)</option>
                            {(appliedJobs ?? []).filter(Boolean).map(j => <option key={j.id} value={j.id}>{j.title} — {j.organization?.name}</option>)}
                        </select>
                        <button onClick={generate} disabled={loading}
                            className="bg-indigo-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 transition-colors">
                            {loading ? '✨ Generating...' : '✨ Generate'}
                        </button>
                    </div>
                </div>

                {result && (
                    <div className="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
                        <div className="flex items-center justify-between">
                            <h3 className="font-semibold text-gray-900">Generated Resume</h3>
                            <button onClick={() => window.print()} className="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">🖨️ Print / Save PDF</button>
                        </div>

                        {result.headline && <div className="text-lg font-bold text-indigo-700">{result.headline}</div>}
                        {result.summary && <p className="text-gray-700 text-sm border-l-4 border-indigo-200 pl-4">{result.summary}</p>}

                        {result.skills?.length > 0 && (
                            <div>
                                <h4 className="font-semibold text-gray-900 text-sm mb-2">Skills</h4>
                                <div className="flex flex-wrap gap-2">{result.skills.map((s,i) => <span key={i} className="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">{s}</span>)}</div>
                            </div>
                        )}

                        {result.experience?.length > 0 && (
                            <div>
                                <h4 className="font-semibold text-gray-900 text-sm mb-3">Experience</h4>
                                <div className="space-y-4">
                                    {result.experience.map((exp, i) => (
                                        <div key={i} className="border-l-2 border-indigo-200 pl-4">
                                            <div className="font-semibold text-gray-900 text-sm">{exp.title}</div>
                                            <div className="text-gray-500 text-xs">{exp.company} · {exp.start_date} – {exp.end_date || 'Present'}</div>
                                            {exp.bullets?.length > 0 && (
                                                <ul className="mt-1 space-y-0.5 list-disc list-inside">
                                                    {exp.bullets.map((b, j) => <li key={j} className="text-gray-700 text-xs">{b}</li>)}
                                                </ul>
                                            )}
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}

                        {result.education?.length > 0 && (
                            <div>
                                <h4 className="font-semibold text-gray-900 text-sm mb-2">Education</h4>
                                <div className="space-y-2">
                                    {result.education.map((edu, i) => (
                                        <div key={i} className="flex justify-between">
                                            <div className="font-medium text-gray-900 text-sm">{edu.degree}</div>
                                            <div className="text-gray-500 text-xs">{edu.institution} {edu.year}</div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                )}
            </div>
        </JobifyLayout>
    );
}
