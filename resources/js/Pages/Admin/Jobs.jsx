import AdminLayout from '../../Layouts/AdminLayout';
import { router, useForm } from '@inertiajs/react';
import Icon from '@mdi/react';
import { mdiMagnify, mdiDelete, mdiPencil, mdiPlus, mdiClose, mdiOfficeBuilding, mdiMapMarker, mdiAccountMultiple } from '@mdi/js';
import { useState } from 'react';

const JOB_TYPES = ['Full-Time', 'Part-Time', 'Contract', 'Internship'];

function Modal({ open, onClose, title, children }) {
    if (!open) return null;
    return (
        <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" onClick={e => e.target === e.currentTarget && onClose()}>
            <div className="bg-white rounded-xl shadow-2xl w-full max-w-lg">
                <div className="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h2 className="text-base font-semibold text-gray-900">{title}</h2>
                    <button onClick={onClose} className="p-1 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                        <Icon path={mdiClose} size={0.8} />
                    </button>
                </div>
                <div className="px-6 py-5">{children}</div>
            </div>
        </div>
    );
}

function Field({ label, error, children }) {
    return (
        <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">{label}</label>
            {children}
            {error && <p className="mt-1 text-xs text-red-600">{error}</p>}
        </div>
    );
}

const inputCls = 'w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent';

export default function Jobs({ jobs = [], companies = [] }) {
    const [search, setSearch]     = useState('');
    const [showCreate, setShowCreate] = useState(false);
    const [editingJob, setEditingJob] = useState(null);

    const createForm = useForm({ company_id: '', job_title: '', job_type: '', description: '', job_salary: '' });
    const editForm   = useForm({ company_id: '', job_title: '', job_type: '', description: '', job_salary: '' });

    const filtered = jobs.filter(j =>
        j.job_title?.toLowerCase().includes(search.toLowerCase()) ||
        j.company?.name?.toLowerCase().includes(search.toLowerCase())
    );

    function openEdit(job) {
        editForm.setData({
            company_id:  job.company_id ?? '',
            job_title:   job.job_title ?? '',
            job_type:    job.job_type ?? '',
            description: job.description ?? '',
            job_salary:  job.job_salary ?? '',
        });
        setEditingJob(job);
    }

    function submitCreate(e) {
        e.preventDefault();
        createForm.post('/job', { onSuccess: () => { setShowCreate(false); createForm.reset(); } });
    }

    function submitEdit(e) {
        e.preventDefault();
        editForm.put(`/jobs/${editingJob.id}`, { onSuccess: () => setEditingJob(null) });
    }

    function deleteJob(id) {
        window.Swal?.fire({
            title: 'Delete this job?', text: 'This cannot be undone.', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
        }).then(r => { if (r.isConfirmed) router.delete(`/admin/jobs/${id}`); });
    }

    const typeBadge = t => ({
        'Full-Time':  'bg-blue-50 text-blue-700 ring-blue-100',
        'Part-Time':  'bg-amber-50 text-amber-700 ring-amber-100',
        'Contract':   'bg-purple-50 text-purple-700 ring-purple-100',
        'Internship': 'bg-emerald-50 text-emerald-700 ring-emerald-100',
    })[t] ?? 'bg-gray-100 text-gray-600 ring-gray-200';

    return (
        <AdminLayout title="Manage Jobs">
            {/* Create Modal */}
            <Modal open={showCreate} onClose={() => setShowCreate(false)} title="Add New Job">
                <form onSubmit={submitCreate} className="space-y-4">
                    <Field label="Company" error={createForm.errors.company_id}>
                        <select value={createForm.data.company_id} onChange={e => createForm.setData('company_id', e.target.value)} className={inputCls} required>
                            <option value="">Select company…</option>
                            {companies.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                        </select>
                    </Field>
                    <Field label="Job Title" error={createForm.errors.job_title}>
                        <input type="text" value={createForm.data.job_title} onChange={e => createForm.setData('job_title', e.target.value)} className={inputCls} placeholder="e.g. Senior Developer" required />
                    </Field>
                    <div className="grid grid-cols-2 gap-3">
                        <Field label="Job Type" error={createForm.errors.job_type}>
                            <select value={createForm.data.job_type} onChange={e => createForm.setData('job_type', e.target.value)} className={inputCls} required>
                                <option value="">Select type…</option>
                                {JOB_TYPES.map(t => <option key={t} value={t}>{t}</option>)}
                            </select>
                        </Field>
                        <Field label="Salary" error={createForm.errors.job_salary}>
                            <input type="text" value={createForm.data.job_salary} onChange={e => createForm.setData('job_salary', e.target.value)} className={inputCls} placeholder="e.g. $60,000" required />
                        </Field>
                    </div>
                    <Field label="Description" error={createForm.errors.description}>
                        <textarea value={createForm.data.description} onChange={e => createForm.setData('description', e.target.value)} className={inputCls} rows={3} placeholder="Job description…" required maxLength={255} />
                    </Field>
                    <div className="flex justify-end gap-3 pt-2">
                        <button type="button" onClick={() => setShowCreate(false)} className="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">Cancel</button>
                        <button type="submit" disabled={createForm.processing} className="px-4 py-2 text-sm bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-lg transition-colors disabled:opacity-60">
                            {createForm.processing ? 'Saving…' : 'Add Job'}
                        </button>
                    </div>
                </form>
            </Modal>

            {/* Edit Modal */}
            <Modal open={!!editingJob} onClose={() => setEditingJob(null)} title="Edit Job">
                <form onSubmit={submitEdit} className="space-y-4">
                    <Field label="Company" error={editForm.errors.company_id}>
                        <select value={editForm.data.company_id} onChange={e => editForm.setData('company_id', e.target.value)} className={inputCls} required>
                            <option value="">Select company…</option>
                            {companies.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                        </select>
                    </Field>
                    <Field label="Job Title" error={editForm.errors.job_title}>
                        <input type="text" value={editForm.data.job_title} onChange={e => editForm.setData('job_title', e.target.value)} className={inputCls} required />
                    </Field>
                    <div className="grid grid-cols-2 gap-3">
                        <Field label="Job Type" error={editForm.errors.job_type}>
                            <select value={editForm.data.job_type} onChange={e => editForm.setData('job_type', e.target.value)} className={inputCls} required>
                                <option value="">Select type…</option>
                                {JOB_TYPES.map(t => <option key={t} value={t}>{t}</option>)}
                            </select>
                        </Field>
                        <Field label="Salary" error={editForm.errors.job_salary}>
                            <input type="text" value={editForm.data.job_salary} onChange={e => editForm.setData('job_salary', e.target.value)} className={inputCls} required />
                        </Field>
                    </div>
                    <Field label="Description" error={editForm.errors.description}>
                        <textarea value={editForm.data.description} onChange={e => editForm.setData('description', e.target.value)} className={inputCls} rows={3} required maxLength={255} />
                    </Field>
                    <div className="flex justify-end gap-3 pt-2">
                        <button type="button" onClick={() => setEditingJob(null)} className="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">Cancel</button>
                        <button type="submit" disabled={editForm.processing} className="px-4 py-2 text-sm bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-lg transition-colors disabled:opacity-60">
                            {editForm.processing ? 'Saving…' : 'Save Changes'}
                        </button>
                    </div>
                </form>
            </Modal>

            <div className="space-y-5">
                <div className="flex items-center justify-between gap-4 flex-wrap">
                    <div className="relative flex-1 min-w-[200px] max-w-sm">
                        <Icon path={mdiMagnify} size={0.75} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                        <input type="text" placeholder="Search jobs or companies…" value={search} onChange={e => setSearch(e.target.value)}
                            className="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" />
                    </div>
                    <div className="flex items-center gap-3">
                        <span className="text-sm text-gray-500">{filtered.length} job{filtered.length !== 1 ? 's' : ''}</span>
                        <button onClick={() => setShowCreate(true)} className="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                            <Icon path={mdiPlus} size={0.75} /> Add Job
                        </button>
                    </div>
                </div>

                <div className="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="border-b border-gray-100 bg-gray-50">
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Job Title</th>
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Company</th>
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Type</th>
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Salary</th>
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Applicants</th>
                                <th className="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-50">
                            {filtered.length === 0 ? (
                                <tr><td colSpan={6} className="text-center py-12 text-gray-400 text-sm">No jobs found</td></tr>
                            ) : filtered.map(job => (
                                <tr key={job.id} className="hover:bg-gray-50 transition-colors">
                                    <td className="px-4 py-3 font-medium text-gray-900">{job.job_title}</td>
                                    <td className="px-4 py-3 text-gray-600">
                                        <span className="flex items-center gap-1.5">
                                            <Icon path={mdiOfficeBuilding} size={0.6} className="text-gray-400" />
                                            {job.company?.name ?? '—'}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3">
                                        <span className={`inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ${typeBadge(job.job_type)}`}>
                                            {job.job_type ?? '—'}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3 text-gray-600 text-xs">{job.job_salary ?? '—'}</td>
                                    <td className="px-4 py-3 text-gray-600">
                                        <span className="flex items-center gap-1.5">
                                            <Icon path={mdiAccountMultiple} size={0.6} className="text-gray-400" />
                                            {job.applications_count ?? job.applications?.length ?? 0}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3">
                                        <div className="flex items-center gap-1 justify-end">
                                            <button onClick={() => openEdit(job)}
                                                className="inline-flex items-center gap-1 text-xs text-blue-600 hover:bg-blue-50 px-2 py-1 rounded transition-colors">
                                                <Icon path={mdiPencil} size={0.6} /> Edit
                                            </button>
                                            <button onClick={() => deleteJob(job.id)}
                                                className="inline-flex items-center gap-1 text-xs text-red-600 hover:bg-red-50 px-2 py-1 rounded transition-colors">
                                                <Icon path={mdiDelete} size={0.6} /> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </AdminLayout>
    );
}
