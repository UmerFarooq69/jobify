import JobifyLayout from '../../../Layouts/JobifyLayout';
import { useState } from 'react';
import axios from 'axios';

export default function InterviewPrep({ profile, appliedJobs }) {
    const [selectedJob, setSelectedJob] = useState('');
    const [result, setResult] = useState(null);
    const [loading, setLoading] = useState(false);
    const [openCategory, setOpenCategory] = useState(0);

    const generate = async () => {
        if (!selectedJob) return;
        setLoading(true);
        try {
            const res = await axios.post('/candidate/interview-prep/generate', { job_id: selectedJob }, {
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
            });
            setResult(res.data);
            setOpenCategory(0);
        } catch { alert('Generation failed. Please try again.'); }
        setLoading(false);
    };

    return (
        <JobifyLayout title="Interview Preparation">
            <div className="max-w-3xl space-y-6">
                <div className="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 className="font-semibold text-gray-900 mb-1">AI Interview Prep</h2>
                    <p className="text-gray-500 text-sm mb-4">Get tailored interview questions and coaching tips for your target role</p>
                    <div className="flex gap-3">
                        <select value={selectedJob} onChange={e => setSelectedJob(e.target.value)}
                            className="flex-1 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">Select a job you've applied to...</option>
                            {(appliedJobs ?? []).filter(Boolean).map(j => <option key={j.id} value={j.id}>{j.title} — {j.organization?.name}</option>)}
                        </select>
                        <button onClick={generate} disabled={loading || !selectedJob}
                            className="bg-indigo-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 transition-colors">
                            {loading ? ' Generating...' : '🎤 Generate'}
                        </button>
                    </div>
                </div>

                {result?.categories?.length > 0 && (
                    <div className="space-y-3">
                        {result.categories.map((cat, i) => (
                            <div key={i} className="bg-white rounded-xl border border-gray-200 overflow-hidden">
                                <button className="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors"
                                    onClick={() => setOpenCategory(openCategory === i ? -1 : i)}>
                                    <span className="font-semibold text-gray-900">{cat.name}</span>
                                    <span className="text-gray-400 text-sm">{cat.questions?.length} questions {openCategory === i ? '▲' : '▼'}</span>
                                </button>
                                {openCategory === i && (
                                    <div className="border-t border-gray-100 divide-y divide-gray-100">
                                        {cat.questions?.map((q, j) => (
                                            <div key={j} className="p-5 space-y-2">
                                                <div className="font-medium text-gray-900 text-sm">Q: {q.question}</div>
                                                {q.tip && <div className="text-indigo-700 text-xs bg-indigo-50 px-3 py-2 rounded-lg">💡 Tip: {q.tip}</div>}
                                                {q.sample_answer && (
                                                    <details className="cursor-pointer">
                                                        <summary className="text-xs text-gray-500 hover:text-gray-700">Show sample answer</summary>
                                                        <p className="text-sm text-gray-700 mt-2">{q.sample_answer}</p>
                                                    </details>
                                                )}
                                            </div>
                                        ))}
                                    </div>
                                )}
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </JobifyLayout>
    );
}
