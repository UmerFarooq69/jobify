import JobifyLayout from '../../../Layouts/JobifyLayout';
import { router } from '@inertiajs/react';
import { useState } from 'react';

export default function JobTaxonomy({ taxonomies }) {
    const [form, setForm] = useState({ type: 'job_type', name: '', icon: '', sort_order: 0 });
    const [submitting, setSubmitting] = useState(false);

    const add = () => {
        setSubmitting(true);
        router.post('/platform-admin/job-taxonomy', form, { preserveScroll: true, onFinish: () => { setSubmitting(false); setForm({ type: 'job_type', name: '', icon: '', sort_order: 0 }); } });
    };

    const remove = (id) => { if(confirm('Delete?')) router.delete(`/platform-admin/job-taxonomy/${id}`, { preserveScroll: true }); };

    const groups = { job_type: [], job_functionality: [] };
    (taxonomies ?? []).forEach(t => { if(groups[t.type]) groups[t.type].push(t); });

    return (
        <JobifyLayout title="Job Taxonomy">
            <div className="max-w-4xl space-y-6">
                {/* Add form */}
                <div className="bg-white rounded-xl border border-gray-200 p-5">
                    <h2 className="font-semibold text-gray-900 mb-4">Add Taxonomy Item</h2>
                    <div className="grid grid-cols-4 gap-3">
                        <select value={form.type} onChange={e => setForm({...form, type: e.target.value})}
                            className="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="job_type">Job Type</option>
                            <option value="job_functionality">Job Function</option>
                        </select>
                        <input value={form.name} onChange={e => setForm({...form, name: e.target.value})}
                            placeholder="Name" className="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                        <input value={form.icon} onChange={e => setForm({...form, icon: e.target.value})}
                            placeholder="Icon (emoji)" className="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                        <button onClick={add} disabled={submitting || !form.name.trim()}
                            className="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 transition-colors">
                            Add
                        </button>
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {Object.entries(groups).map(([type, items]) => (
                        <div key={type} className="bg-white rounded-xl border border-gray-200 p-5">
                            <h3 className="font-semibold text-gray-900 mb-3 capitalize">{type.replace('_',' ')}</h3>
                            {items.length > 0 ? (
                                <div className="space-y-2">
                                    {items.map(item => (
                                        <div key={item.id} className="flex items-center justify-between p-2 border border-gray-100 rounded-lg">
                                            <div className="flex items-center gap-2">
                                                {item.icon && <span>{item.icon}</span>}
                                                <span className="text-sm text-gray-900">{item.name}</span>
                                                {!item.active && <span className="text-xs bg-red-50 text-red-600 px-1.5 py-0.5 rounded">Inactive</span>}
                                            </div>
                                            <button onClick={() => remove(item.id)} className="text-red-500 hover:text-red-700 text-xs px-2 py-1 rounded hover:bg-red-50 transition-colors">Remove</button>
                                        </div>
                                    ))}
                                </div>
                            ) : <p className="text-gray-400 text-sm">None added</p>}
                        </div>
                    ))}
                </div>
            </div>
        </JobifyLayout>
    );
}
