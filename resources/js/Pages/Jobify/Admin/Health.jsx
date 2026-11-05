import JobifyLayout from '../../../Layouts/JobifyLayout';

export default function Health({ checks }) {
    return (
        <JobifyLayout title="System Health">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-3xl">
                {Object.entries(checks ?? {}).map(([name, check]) => (
                    <div key={name} className="bg-white rounded-xl border border-gray-200 p-5">
                        <div className="flex items-center justify-between mb-2">
                            <h3 className="font-semibold text-gray-900 capitalize">{name}</h3>
                            <span className={`px-3 py-1 rounded-full text-xs font-semibold ${check.status==='ok' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                                {check.status==='ok' ? '✓ OK' : '✗ Failed'}
                            </span>
                        </div>
                        <p className="text-gray-500 text-sm">{check.message}</p>
                    </div>
                ))}
            </div>
        </JobifyLayout>
    );
}
