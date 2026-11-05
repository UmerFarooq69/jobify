import JobifyLayout from '../../../Layouts/JobifyLayout';
import { router } from '@inertiajs/react';
import { useState } from 'react';

export default function JobAlerts({ alerts }) {
    const [form, setForm] = useState({ keywords: '', employment_type: '', location: '', frequency: 'daily' });
    const [adding, setAdding] = useState(false);

    const add = () => {
        setAdding(true);
        router.post('/candidate/alerts', form, {
            preserveScroll: true,
            onFinish: () => { setAdding(false); setForm({ keywords: '', employment_type: '', location: '', frequency: 'daily' }); }
        });
    };

    const remove = (id) => { if(confirm('Delete alert?')) router.delete(`/candidate/alerts/${id}`, { preserveScroll: true }); };

    return (
        <JobifyLayout title="Job Alerts">
            <div className="max-w-2xl space-y-6">
                {/* Create alert */}
                <div className="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 className="font-semibold text-gray-900 mb-4">Create New Alert</h2>
                    <div className="space-y-3">
                        <input value={form.keywords} onChange={e => setForm({...form, keywords: e.target.value})}
                            placeholder="Keywords (e.g. React Developer)" className="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                        <div className="grid grid-cols-3 gap-3">
                            <input value={form.location} onChange={e => setForm({...form, location: e.target.value})}
                                placeholder="Location" className="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                            <select value={form.employment_type} onChange={e => setForm({...form, employment_type: e.target.value})}
                                className="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <option value="">All Types</option>
                                {['full_time','part_time','contract','freelance','internship'].map(t => <option key={t} value={t}>{t.replace('_',' ')}</option>)}
                            </select>
                            <select value={form.frequency} onChange={e => setForm({...form, frequency: e.target.value})}
                                className="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                            </select>
                        </div>
                        <button onClick={add} disabled={adding || !form.keywords.trim()} className="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 transition-colors">
                            {adding ? 'Creating...' : 'Create Alert'}
                        </button>
                    </div>
                </div>

                {/* Alert list */}
                <div className="bg-white rounded-xl border border-gray-200">
                    <div className="p-5 border-b border-gray-100">
                        <h2 className="font-semibold text-gray-900">Active Alerts ({alerts?.length ?? 0})</h2>
                    </div>
                    {alerts?.length > 0 ? (
                        <div className="divide-y divide-gray-100">
                            {alerts.map(alert => (
                                <div key={alert.id} className="px-5 py-4 flex items-center justify-between">
                                    <div>
                                        <div className="font-medium text-gray-900 text-sm">{alert.keywords}</div>
                                        <div className="text-gray-500 text-xs mt-0.5">
                                            {[alert.location, alert.employment_type?.replace('_',' ')].filter(Boolean).join(' · ')} · {alert.frequency}
                                        </div>
                                        {alert.new_matches_count > 0 && <div className="text-indigo-600 text-xs font-semibold mt-0.5">{alert.new_matches_count} new matches</div>}
                                    </div>
                                    <button onClick={() => remove(alert.id)} className="text-red-500 hover:text-red-700 text-xs px-3 py-1.5 rounded hover:bg-red-50 transition-colors">Delete</button>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="p-8 text-center text-gray-400 text-sm">No alerts yet. Create one above to get notified of new matching jobs.</div>
                    )}
                </div>
            </div>
        </JobifyLayout>
    );
}
