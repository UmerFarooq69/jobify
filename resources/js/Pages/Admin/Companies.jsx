import AdminLayout from '../../Layouts/AdminLayout';
import { router, useForm } from '@inertiajs/react';
import Icon from '@mdi/react';
import { mdiMagnify, mdiDelete, mdiPencil, mdiPlus, mdiClose, mdiMapMarker, mdiWeb, mdiDomain } from '@mdi/js';
import { useState } from 'react';

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

export default function Companies({ companies = [] }) {
    const [search, setSearch]         = useState('');
    const [showCreate, setShowCreate] = useState(false);
    const [editingCo, setEditingCo]   = useState(null);

    const createForm = useForm({ name: '', city: '', location: '', description: '', image: null });
    const editForm   = useForm({ name: '', city: '', location: '', description: '', image: null, _method: 'PUT' });

    const filtered = companies.filter(c =>
        c.name?.toLowerCase().includes(search.toLowerCase()) ||
        c.location?.toLowerCase().includes(search.toLowerCase()) ||
        c.city?.toLowerCase().includes(search.toLowerCase())
    );

    function openEdit(co) {
        editForm.setData({ name: co.name ?? '', city: co.city ?? '', location: co.location ?? '', description: co.description ?? '', image: null, _method: 'PUT' });
        setEditingCo(co);
    }

    function submitCreate(e) {
        e.preventDefault();
        createForm.post('/companies', { forceFormData: true, onSuccess: () => { setShowCreate(false); createForm.reset(); } });
    }

    function submitEdit(e) {
        e.preventDefault();
        editForm.post(`/company/${editingCo.id}`, { forceFormData: true, onSuccess: () => setEditingCo(null) });
    }

    function deleteCompany(id) {
        window.Swal?.fire({
            title: 'Delete this company?', text: 'All associated jobs will also be removed.', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
        }).then(r => { if (r.isConfirmed) router.delete(`/admin/company/${id}`); });
    }

    const FormFields = (form) => (
        <>
            <Field label="Company Name" error={form.errors.name}>
                <input type="text" value={form.data.name} onChange={e => form.setData('name', e.target.value)} className={inputCls} placeholder="e.g. Acme Corp" required />
            </Field>
            <div className="grid grid-cols-2 gap-3">
                <Field label="City" error={form.errors.city}>
                    <input type="text" value={form.data.city} onChange={e => form.setData('city', e.target.value)} className={inputCls} placeholder="e.g. Lahore" required />
                </Field>
                <Field label="Location / Address" error={form.errors.location}>
                    <input type="text" value={form.data.location} onChange={e => form.setData('location', e.target.value)} className={inputCls} placeholder="e.g. Canal Rd" required />
                </Field>
            </div>
            <Field label="Description" error={form.errors.description}>
                <textarea value={form.data.description} onChange={e => form.setData('description', e.target.value)} className={inputCls} rows={3} placeholder="Brief company description…" maxLength={1000} />
            </Field>
            <Field label="Logo / Image" error={form.errors.image}>
                <input type="file" accept="image/*" onChange={e => form.setData('image', e.target.files[0])}
                    className="w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
            </Field>
        </>
    );

    return (
        <AdminLayout title="Companies">
            {/* Create Modal */}
            <Modal open={showCreate} onClose={() => setShowCreate(false)} title="Add New Company">
                <form onSubmit={submitCreate} className="space-y-4">
                    {FormFields(createForm)}
                    <div className="flex justify-end gap-3 pt-2">
                        <button type="button" onClick={() => setShowCreate(false)} className="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">Cancel</button>
                        <button type="submit" disabled={createForm.processing} className="px-4 py-2 text-sm bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-lg transition-colors disabled:opacity-60">
                            {createForm.processing ? 'Saving…' : 'Add Company'}
                        </button>
                    </div>
                </form>
            </Modal>

            {/* Edit Modal */}
            <Modal open={!!editingCo} onClose={() => setEditingCo(null)} title={`Edit — ${editingCo?.name ?? ''}`}>
                <form onSubmit={submitEdit} className="space-y-4">
                    {FormFields(editForm)}
                    <div className="flex justify-end gap-3 pt-2">
                        <button type="button" onClick={() => setEditingCo(null)} className="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">Cancel</button>
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
                        <input type="text" placeholder="Search companies…" value={search} onChange={e => setSearch(e.target.value)}
                            className="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent" />
                    </div>
                    <div className="flex items-center gap-3">
                        <span className="text-sm text-gray-500">{filtered.length} compan{filtered.length !== 1 ? 'ies' : 'y'}</span>
                        <button onClick={() => setShowCreate(true)} className="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                            <Icon path={mdiPlus} size={0.75} /> Add Company
                        </button>
                    </div>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                    {filtered.length === 0 ? (
                        <div className="col-span-full text-center py-16 text-gray-400 text-sm bg-white rounded-xl border border-gray-200">No companies found</div>
                    ) : filtered.map(co => (
                        <div key={co.id} className="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow p-5">
                            <div className="flex items-start gap-4">
                                {co.image ? (
                                    <img src={`/storage/${co.image}`} alt={co.name} className="w-12 h-12 rounded-lg object-cover border border-gray-100" />
                                ) : (
                                    <div className="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                        <Icon path={mdiDomain} size={0.9} className="text-gray-400" />
                                    </div>
                                )}
                                <div className="flex-1 min-w-0">
                                    <h3 className="font-semibold text-gray-900 text-sm truncate">{co.name}</h3>
                                    {co.city && <p className="text-xs text-gray-500 mt-0.5">{co.city}</p>}
                                    {co.location && (
                                        <p className="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                            <Icon path={mdiMapMarker} size={0.55} /> {co.location}
                                        </p>
                                    )}
                                </div>
                            </div>
                            {co.description && (
                                <p className="text-xs text-gray-500 mt-3 line-clamp-2">{co.description}</p>
                            )}
                            <div className="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between">
                                <span className="text-xs text-gray-400">{co.jobs_count ?? 0} jobs</span>
                                <div className="flex items-center gap-1">
                                    <button onClick={() => openEdit(co)}
                                        className="inline-flex items-center gap-1 text-xs text-blue-600 hover:bg-blue-50 px-2 py-1 rounded transition-colors">
                                        <Icon path={mdiPencil} size={0.6} /> Edit
                                    </button>
                                    <button onClick={() => deleteCompany(co.id)}
                                        className="inline-flex items-center gap-1 text-xs text-red-600 hover:bg-red-50 px-2 py-1 rounded transition-colors">
                                        <Icon path={mdiDelete} size={0.6} /> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </AdminLayout>
    );
}
