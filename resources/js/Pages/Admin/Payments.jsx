import AdminLayout from '../../Layouts/AdminLayout';
import { router } from '@inertiajs/react';
import Icon from '@mdi/react';
import { mdiMagnify, mdiDelete, mdiAccount, mdiEmail, mdiPhone, mdiCreditCard, mdiCalendar, mdiFileDocument } from '@mdi/js';
import { useState } from 'react';

export default function Payments({ payments = [] }) {
    const [search, setSearch] = useState('');

    const filtered = payments.filter(p =>
        p.name?.toLowerCase().includes(search.toLowerCase()) ||
        p.email?.toLowerCase().includes(search.toLowerCase()) ||
        p.plan?.toLowerCase().includes(search.toLowerCase()) ||
        p.payment_method?.toLowerCase().includes(search.toLowerCase())
    );

    function deletePayment(id) {
        window.Swal?.fire({
            title: 'Delete this payment?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
        }).then(r => {
            if (r.isConfirmed) router.delete(`/payments/${id}`);
        });
    }

    const planBadge = (plan) => ({
        Monthly: 'bg-blue-50 text-blue-700 ring-blue-100',
        Yearly:  'bg-emerald-50 text-emerald-700 ring-emerald-100',
    })[plan] ?? 'bg-gray-100 text-gray-600 ring-gray-200';

    const methodBadge = (method) => {
        if (!method) return 'bg-gray-100 text-gray-600 ring-gray-200';
        if (method.toLowerCase().includes('jazz')) return 'bg-red-50 text-red-700 ring-red-100';
        if (method.toLowerCase().includes('easy')) return 'bg-green-50 text-green-700 ring-green-100';
        if (method.toLowerCase().includes('bank')) return 'bg-blue-50 text-blue-700 ring-blue-100';
        return 'bg-gray-100 text-gray-600 ring-gray-200';
    };

    return (
        <AdminLayout title="Payments">
            <div className="space-y-5">
                <div className="flex items-center justify-between gap-4">
                    <div className="relative flex-1 max-w-sm">
                        <Icon path={mdiMagnify} size={0.75} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
                        <input
                            type="text"
                            placeholder="Search payments…"
                            value={search}
                            onChange={e => setSearch(e.target.value)}
                            className="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        />
                    </div>
                    <span className="text-sm text-gray-500">{filtered.length} payment{filtered.length !== 1 ? 's' : ''}</span>
                </div>

                <div className="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="border-b border-gray-100 bg-gray-50">
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Name</th>
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Email</th>
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Phone</th>
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Method</th>
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Plan</th>
                                <th className="text-left px-4 py-3 font-medium text-gray-600 text-xs uppercase tracking-wide">Date</th>
                                <th className="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-50">
                            {filtered.length === 0 ? (
                                <tr>
                                    <td colSpan={7} className="text-center py-12 text-gray-400 text-sm">No payments found</td>
                                </tr>
                            ) : filtered.map(payment => (
                                <tr key={payment.id} className="hover:bg-gray-50 transition-colors">
                                    <td className="px-4 py-3 font-medium text-gray-900">
                                        <span className="flex items-center gap-1.5">
                                            <Icon path={mdiAccount} size={0.6} className="text-gray-400" />
                                            {payment.name}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3 text-gray-600">
                                        <span className="flex items-center gap-1.5">
                                            <Icon path={mdiEmail} size={0.6} className="text-gray-400" />
                                            {payment.email}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3 text-gray-600">
                                        <span className="flex items-center gap-1.5">
                                            <Icon path={mdiPhone} size={0.6} className="text-gray-400" />
                                            {payment.number}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3">
                                        <span className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium ring-1 ${methodBadge(payment.payment_method)}`}>
                                            <Icon path={mdiCreditCard} size={0.55} />
                                            {payment.payment_method}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3">
                                        <span className={`inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ring-1 ${planBadge(payment.plan)}`}>
                                            {payment.plan}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3 text-gray-500 text-xs">
                                        <span className="flex items-center gap-1">
                                            <Icon path={mdiCalendar} size={0.6} className="text-gray-400" />
                                            {payment.created_at ? new Date(payment.created_at).toLocaleDateString() : '—'}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3">
                                        <div className="flex items-center gap-2 justify-end">
                                            {payment.attachment && (
                                                <a
                                                    href={`/storage/${payment.attachment}`}
                                                    target="_blank"
                                                    rel="noreferrer"
                                                    className="text-xs text-blue-600 hover:text-blue-700 hover:bg-blue-50 px-2 py-1 rounded transition-colors flex items-center gap-1"
                                                >
                                                    <Icon path={mdiFileDocument} size={0.6} />
                                                    Receipt
                                                </a>
                                            )}
                                            <button
                                                onClick={() => deletePayment(payment.id)}
                                                className="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded transition-colors"
                                            >
                                                <Icon path={mdiDelete} size={0.6} />
                                                Delete
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
