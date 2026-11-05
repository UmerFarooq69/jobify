import JobifyLayout from '../../../Layouts/JobifyLayout';
import { router } from '@inertiajs/react';
import { useState } from 'react';

export default function AdminSettings({ settings }) {
    const [form, setForm] = useState(() => {
        const s = {};
        Object.values(settings ?? {}).forEach(setting => { s[setting.key] = setting.value; });
        return s;
    });
    const [saving, setSaving] = useState(false);

    const save = () => {
        setSaving(true);
        router.put('/platform-admin/settings', { settings: form }, { onFinish: () => setSaving(false) });
    };

    const groups = {};
    Object.values(settings ?? {}).forEach(s => {
        if (!groups[s.group]) groups[s.group] = [];
        groups[s.group].push(s);
    });

    return (
        <JobifyLayout title="Platform Settings">
            <div className="max-w-2xl space-y-6">
                {Object.entries(groups).map(([group, items]) => (
                    <div key={group} className="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 className="font-semibold text-gray-900 mb-4 capitalize">{group} Settings</h2>
                        <div className="space-y-4">
                            {items.map(setting => (
                                <div key={setting.key}>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">{setting.description ?? setting.key}</label>
                                    {setting.type === 'boolean' ? (
                                        <label className="flex items-center gap-2 cursor-pointer">
                                            <div className="relative">
                                                <input type="checkbox" className="sr-only" checked={form[setting.key] === 'true'}
                                                    onChange={e => setForm({...form, [setting.key]: e.target.checked ? 'true' : 'false'})} />
                                                <div className={`w-10 h-6 rounded-full transition-colors ${form[setting.key]==='true' ? 'bg-indigo-600' : 'bg-gray-300'}`}>
                                                    <div className={`absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transition-transform ${form[setting.key]==='true' ? 'translate-x-4' : ''}`} />
                                                </div>
                                            </div>
                                            <span className="text-sm text-gray-600">{form[setting.key]==='true' ? 'Enabled' : 'Disabled'}</span>
                                        </label>
                                    ) : (
                                        <input value={form[setting.key] ?? ''} onChange={e => setForm({...form, [setting.key]: e.target.value})}
                                            type={setting.type==='integer' ? 'number' : setting.key.includes('key') ? 'password' : 'text'}
                                            className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                                    )}
                                </div>
                            ))}
                        </div>
                    </div>
                ))}
                <button onClick={save} disabled={saving} className="bg-indigo-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 transition-colors">
                    {saving ? 'Saving...' : 'Save Settings'}
                </button>
            </div>
        </JobifyLayout>
    );
}
