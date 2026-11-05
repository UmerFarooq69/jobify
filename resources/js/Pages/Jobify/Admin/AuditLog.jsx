import JobifyLayout from '../../../Layouts/JobifyLayout';

export default function AuditLog({ logs }) {
    return (
        <JobifyLayout title="Audit Log">
            <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div className="p-5 border-b border-gray-100">
                    <h2 className="font-semibold text-gray-900">Audit Log ({logs?.total ?? 0})</h2>
                </div>
                <table className="w-full">
                    <thead className="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th className="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Event</th>
                            <th className="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">User</th>
                            <th className="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Target</th>
                            <th className="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Time</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-100">
                        {(logs?.data ?? []).map(log => (
                            <tr key={log.id} className="hover:bg-gray-50">
                                <td className="px-5 py-3 text-sm font-medium text-gray-900">{log.event}</td>
                                <td className="px-5 py-3 text-sm text-gray-600">{log.user?.email ?? 'System'}</td>
                                <td className="px-5 py-3 text-sm text-gray-500">{log.auditable_type ? `${log.auditable_type}#${log.auditable_id}` : '—'}</td>
                                <td className="px-5 py-3 text-right text-xs text-gray-400">{new Date(log.created_at).toLocaleString()}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </JobifyLayout>
    );
}
