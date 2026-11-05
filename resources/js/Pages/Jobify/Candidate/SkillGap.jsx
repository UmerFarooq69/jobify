import JobifyLayout from '../../../Layouts/JobifyLayout';
import { useState } from 'react';
import axios from 'axios';

export default function SkillGap({ profile }) {
    const [targetRole, setTargetRole] = useState('');
    const [result, setResult] = useState(null);
    const [loading, setLoading] = useState(false);

    const analyse = async () => {
        if (!targetRole.trim()) return;
        setLoading(true);
        try {
            const res = await axios.post('/candidate/skill-gap/analyse', { target_role: targetRole }, {
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
            });
            setResult(res.data);
        } catch(e) { alert('Analysis failed. Please try again.'); }
        setLoading(false);
    };

    return (
        <JobifyLayout title="Skill Gap Analysis">
            <div className="max-w-3xl space-y-6">
                <div className="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 className="font-semibold text-gray-900 mb-1">AI Skill Gap Analysis</h2>
                    <p className="text-gray-500 text-sm mb-4">Enter your target role and we'll show you what skills to develop</p>
                    <div className="flex gap-3">
                        <input value={targetRole} onChange={e => setTargetRole(e.target.value)}
                            placeholder="e.g. Senior Data Scientist" className="flex-1 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                        <button onClick={analyse} disabled={loading || !targetRole.trim()}
                            className="bg-indigo-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 transition-colors">
                            {loading ? ' Analysing...' : 'Analyse'}
                        </button>
                    </div>
                    <div className="text-xs text-gray-400 mt-2">Your current skills: {(profile?.skills ?? []).join(', ') || 'None added yet'}</div>
                </div>

                {result && (
                    <>
                        <div className="bg-white rounded-xl border border-gray-200 p-6">
                            <div className="flex items-center justify-between mb-4">
                                <h3 className="font-semibold text-gray-900">Overall Readiness</h3>
                                <span className={`text-2xl font-bold ${result.overall_readiness >= 70 ? 'text-green-600' : result.overall_readiness >= 40 ? 'text-amber-600' : 'text-red-600'}`}>{result.overall_readiness}%</span>
                            </div>
                            <div className="h-3 bg-gray-100 rounded-full overflow-hidden">
                                <div className="h-full rounded-full transition-all bg-gradient-to-r from-indigo-500 to-purple-600" style={{ width: `${result.overall_readiness}%` }} />
                            </div>
                        </div>

                        {result.existing_strengths?.length > 0 && (
                            <div className="bg-white rounded-xl border border-gray-200 p-6">
                                <h3 className="font-semibold text-gray-900 mb-3">Your Strengths ✓</h3>
                                <div className="flex flex-wrap gap-2">
                                    {result.existing_strengths.map((s,i) => <span key={i} className="bg-green-50 text-green-700 text-sm px-3 py-1 rounded-full">{s}</span>)}
                                </div>
                            </div>
                        )}

                        {result.missing_skills?.length > 0 && (
                            <div className="bg-white rounded-xl border border-gray-200 p-6">
                                <h3 className="font-semibold text-gray-900 mb-4">Skills to Develop</h3>
                                <div className="space-y-4">
                                    {result.missing_skills.map((s,i) => (
                                        <div key={i} className="border border-gray-100 rounded-lg p-4">
                                            <div className="flex items-center gap-2 mb-2">
                                                <span className="font-medium text-gray-900 text-sm">{s.skill}</span>
                                                <span className={`text-xs px-2 py-0.5 rounded-full ${s.priority === 'high' ? 'bg-red-50 text-red-600' : s.priority === 'medium' ? 'bg-amber-50 text-amber-600' : 'bg-gray-100 text-gray-600'}`}>{s.priority}</span>
                                            </div>
                                            {s.resources?.length > 0 && (
                                                <ul className="space-y-1">
                                                    {s.resources.map((r,j) => <li key={j} className="text-xs text-gray-600">• {r.name} <span className="text-gray-400">({r.type})</span></li>)}
                                                </ul>
                                            )}
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}
                    </>
                )}
            </div>
        </JobifyLayout>
    );
}
