import JobifyLayout from '../../../../Layouts/JobifyLayout';
import { useForm } from '@inertiajs/react';
import { useState } from 'react';
import axios from 'axios';


export default function CreateJob({ org, departments, jobTypes, functions: jobFunctions }) {
    const { data, setData, post, processing, errors } = useForm({
        title: '', summary: '', description: '', requirements: '',
        skills: [], salary_min: '', salary_max: '', currency: 'QAR',
        location: '', employment_type: 'full_time', visa_sponsorship: false,
        urgent_hire: false, department_id: '', job_type_id: '', job_function_id: '',
    });
    const [skillInput, setSkillInput] = useState('');
    const [aiPrompt, setAiPrompt] = useState('');
    const [aiLoading, setAiLoading] = useState(false);

    const addSkill = () => { if(skillInput.trim()) { setData('skills',[...data.skills,skillInput.trim()]); setSkillInput(''); } };
    const removeSkill = (i) => setData('skills', data.skills.filter((_,idx)=>idx!==i));

    const aiDraft = async () => {
        if (!aiPrompt.trim()) return;
        setAiLoading(true);
        try {
            const res = await axios.post('/employer/jobs/ai-draft', { prompt: aiPrompt }, {
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
            });
            const d = res.data;
            if (d.title) setData(prev => ({ ...prev, ...d, skills: d.skills ?? prev.skills }));
            setAiPrompt('');
        } catch { alert('AI draft failed.'); }
        setAiLoading(false);
    };

    const submit = (e) => { e.preventDefault(); post('/employer/jobs'); };

    return (
        <JobifyLayout title="Post a Job">
            <div className="w-full space-y-6">
                {/* AI Draft */}
                <div className="bg-indigo-50 border border-indigo-200 rounded-xl p-5">
                    <h3 className="font-semibold text-indigo-900 mb-1 flex items-center gap-2"> AI Job Draft</h3>
                    <p className="text-indigo-700 text-sm mb-3">Describe what you're hiring for and AI will fill in the details</p>
                    <div className="flex gap-3">
                        <input value={aiPrompt} onChange={e => setAiPrompt(e.target.value)}
                            placeholder="e.g. Hiring a senior React developer with 5+ years experience..."
                            className="flex-1 border border-indigo-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white" />
                        <button onClick={aiDraft} disabled={aiLoading || !aiPrompt.trim()}
                            className="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 transition-colors whitespace-nowrap">
                            {aiLoading ? '✨ Drafting...' : '✨ Draft'}
                        </button>
                    </div>
                </div>

                <form onSubmit={submit} className="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Job Title *</label>
                        <input value={data.title} onChange={e => setData('title',e.target.value)} required
                            placeholder="e.g. Senior Software Engineer" className="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                    </div>

                    <div className="grid grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Employment Type</label>
                            <select value={data.employment_type} onChange={e => setData('employment_type',e.target.value)}
                                className="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                {['full_time','part_time','contract','freelance','internship'].map(t => <option key={t} value={t}>{t.replace('_',' ')}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input value={data.location} onChange={e => setData('location',e.target.value)}
                                placeholder="e.g. Doha, Lahore" className="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                        </div>
                    </div>

                    <div className="grid grid-cols-3 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Salary Min</label>
                            <input type="number" value={data.salary_min} onChange={e => setData('salary_min',e.target.value)}
                                placeholder="5000" className="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Salary Max</label>
                            <input type="number" value={data.salary_max} onChange={e => setData('salary_max',e.target.value)}
                                placeholder="10000" className="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                            <select value={data.currency} onChange={e => setData('currency',e.target.value)}
                                className="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <option value="QAR">QAR</option><option value="USD">USD</option><option value="EUR">EUR</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Summary</label>
                        <textarea value={data.summary} onChange={e => setData('summary',e.target.value)} rows={2}
                            placeholder="Brief job summary..." className="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none" />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Job Description</label>
                        <textarea value={data.description} onChange={e => setData('description',e.target.value)} rows={6}
                            placeholder="Detailed job description..." className="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none" />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Requirements</label>
                        <textarea value={data.requirements} onChange={e => setData('requirements',e.target.value)} rows={4}
                            placeholder="Candidate requirements..." className="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none" />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">Required Skills</label>
                        <div className="flex gap-2 mb-2">
                            <input value={skillInput} onChange={e => setSkillInput(e.target.value)}
                                onKeyDown={e => e.key==='Enter' && (e.preventDefault(),addSkill())}
                                placeholder="Add skill..." className="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                            <button type="button" onClick={addSkill} className="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Add</button>
                        </div>
                        <div className="flex flex-wrap gap-2">
                            {data.skills.map((s,i) => <span key={i} className="bg-indigo-50 text-indigo-700 text-sm px-3 py-1 rounded-full flex items-center gap-1">{s}<button type="button" onClick={() => removeSkill(i)} className="text-indigo-400 hover:text-red-500">×</button></span>)}
                        </div>
                    </div>

                    <div className="flex items-center gap-6">
                        <label className="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" checked={data.visa_sponsorship} onChange={e => setData('visa_sponsorship',e.target.checked)} className="w-4 h-4 text-indigo-600 rounded" />
                            <span className="text-sm text-gray-700">Visa Sponsorship</span>
                        </label>
                        <label className="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" checked={data.urgent_hire} onChange={e => setData('urgent_hire',e.target.checked)} className="w-4 h-4 text-indigo-600 rounded" />
                            <span className="text-sm text-gray-700">Urgent Hire</span>
                        </label>
                    </div>

                    <div className="flex gap-3 pt-2">
                        <button type="submit" disabled={processing}
                            className="bg-indigo-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 transition-colors">
                            {processing ? 'Saving...' : 'Save as Draft'}
                        </button>
                    </div>
                </form>
            </div>
        </JobifyLayout>
    );
}
