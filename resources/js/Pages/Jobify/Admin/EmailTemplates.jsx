import JobifyLayout from '../../../Layouts/JobifyLayout';
import { router } from '@inertiajs/react';
import { useState } from 'react';

export default function EmailTemplates({ templates }) {
    const [selected, setSelected] = useState(null);
    const [form, setForm] = useState({});
    const [saving, setSaving] = useState(false);

    const select = (t) => { setSelected(t); setForm({ subject: t.subject, body: t.body, is_active: t.is_active }); };
    const save = () => {
        setSaving(true);
        router.put(`/platform-admin/email-templates/${selected.id}`, form, { onFinish: () => setSaving(false) });
    };

    return (
        <JobifyLayout title="Email Templates">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div className="p-4 border-b border-gray-100 font-semibold text-gray-900 text-sm">Templates</div>
                    <div className="divide-y divide-gray-100">
                        {(templates ?? []).map(t => (
                            <button key={t.id} onClick={() => select(t)}
                                className={`w-full text-left px-4 py-3 hover:bg-gray-50 transition-colors ${selected?.id===t.id ? 'bg-indigo-50' : ''}`}>
                                <div className="font-medium text-sm text-gray-900">{t.display_name}</div>
                                <div className="text-xs text-gray-500">{t.name}</div>
                            </button>
                        ))}
                    </div>
                </div>

                {selected ? (
                    <div className="md:col-span-2 bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                        <h2 className="font-semibold text-gray-900">{selected.display_name}</h2>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <input value={form.subject ?? ''} onChange={e => setForm({...form, subject: e.target.value})}
                                className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Body (HTML)</label>
                            <textarea value={form.body ?? ''} onChange={e => setForm({...form, body: e.target.value})} rows={10}
                                className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none" />
                        </div>
                        {selected.variables && (
                            <div className="text-xs text-gray-500">
                                Available variables: {Object.keys(selected.variables).map(k => <code key={k} className="bg-gray-100 px-1 rounded mr-1">&#123;&#123;{k}&#125;&#125;</code>)}
                            </div>
                        )}
                        <button onClick={save} disabled={saving} className="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 transition-colors">
                            {saving ? 'Saving...' : 'Save Template'}
                        </button>
                    </div>
                ) : (
                    <div className="md:col-span-2 bg-white rounded-xl border border-gray-200 p-12 flex items-center justify-center text-gray-400 text-sm">Select a template to edit</div>
                )}
            </div>
        </JobifyLayout>
    );
}
