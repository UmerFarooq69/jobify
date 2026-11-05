import JobifyLayout from '../../../Layouts/JobifyLayout';
import { router } from '@inertiajs/react';
import { useState } from 'react';

export default function Departments({ org, departments }) {
    const [name, setName] = useState('');

    const add = () => {
        if(!name.trim()) return;
        router.post('/employer/departments', { name }, { preserveScroll: true, onSuccess: () => setName('') });
    };

    const remove = (id) => { if(confirm('Delete?')) router.delete(`/employer/departments/${id}`, { preserveScroll: true }); };

    return (
        <JobifyLayout title="Departments">
            <div className="max-w-2xl space-y-6">
                <div className="bg-white rounded-xl border border-gray-200 p-5">
                    <h2 className="font-semibold text-gray-900 mb-4">Add Department</h2>
                    <div className="flex gap-3">
                        <input value={name} onChange={e => setName(e.target.value)}
                            onKeyDown={e => e.key==='Enter' && add()}
                            placeholder="Department name..." className="flex-1 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                        <button onClick={add} className="bg-indigo-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">Add</button>
                    </div>
                </div>

                <div className="bg-white rounded-xl border border-gray-200">
                    <div className="p-5 border-b border-gray-100">
                        <h2 className="font-semibold text-gray-900">Departments ({departments?.length ?? 0})</h2>
                    </div>
                    {departments?.length > 0 ? (
                        <div className="divide-y divide-gray-100">
                            {departments.map(d => (
                                <div key={d.id} className="flex items-center justify-between px-5 py-3">
                                    <span className="text-sm text-gray-900 font-medium">{d.name}</span>
                                    <button onClick={() => remove(d.id)} className="text-red-500 hover:text-red-700 text-xs px-3 py-1.5 rounded hover:bg-red-50 transition-colors">Remove</button>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="p-8 text-center text-gray-400 text-sm">No departments yet</div>
                    )}
                </div>
            </div>
        </JobifyLayout>
    );
}
