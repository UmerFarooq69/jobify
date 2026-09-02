import AdminLayout from '../../Layouts/AdminLayout';
import Icon from '@mdi/react';
import {
    mdiBriefcase,
    mdiOfficeBuilding,
    mdiAccountGroup,
    mdiFileDocument,
    mdiEmail,
    mdiAlertCircle,
    mdiTrendingUp,
    mdiCreditCard,
} from '@mdi/js';

const stats = [
    { label: 'Total Jobs',         key: 'jobs',         icon: mdiBriefcase,      color: 'bg-blue-50 text-blue-700',   ring: 'ring-blue-100' },
    { label: 'Companies',          key: 'companies',    icon: mdiOfficeBuilding, color: 'bg-emerald-50 text-emerald-700', ring: 'ring-emerald-100' },
    { label: 'Users',              key: 'users',        icon: mdiAccountGroup,   color: 'bg-violet-50 text-violet-700',  ring: 'ring-violet-100' },
    { label: 'Applications',       key: 'applications', icon: mdiFileDocument,   color: 'bg-amber-50 text-amber-700',    ring: 'ring-amber-100' },
    { label: 'Contact Messages',   key: 'contact',      icon: mdiEmail,          color: 'bg-sky-50 text-sky-700',        ring: 'ring-sky-100' },
    { label: 'Problem Reports',    key: 'reports',      icon: mdiAlertCircle,    color: 'bg-red-50 text-red-700',        ring: 'ring-red-100' },
];

export default function Dashboard({ jobs, companies, users, applications, contact, reports }) {
    const data = { jobs, companies, users, applications, contact, reports };

    return (
        <AdminLayout title="Dashboard">
            <div className="space-y-6">
                <div>
                    <h2 className="text-lg font-semibold text-gray-900">Overview</h2>
                    <p className="text-sm text-gray-500 mt-0.5">Platform statistics at a glance</p>
                </div>

                <div className="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
                    {stats.map(({ label, key, icon, color, ring }) => (
                        <div key={key} className="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
                            <div className={`w-10 h-10 rounded-lg ${color} ring-1 ${ring} flex items-center justify-center mb-4`}>
                                <Icon path={icon} size={0.85} />
                            </div>
                            <div className="text-2xl font-bold text-gray-900">{data[key] ?? 0}</div>
                            <div className="text-xs text-gray-500 mt-1 font-medium">{label}</div>
                        </div>
                    ))}
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <div className="flex items-center gap-2 mb-4">
                            <Icon path={mdiTrendingUp} size={0.85} className="text-blue-700" />
                            <h3 className="font-semibold text-gray-900 text-sm">Quick Stats</h3>
                        </div>
                        <div className="space-y-3">
                            {stats.map(({ label, key }) => (
                                <div key={key} className="flex items-center justify-between">
                                    <span className="text-sm text-gray-600">{label}</span>
                                    <span className="text-sm font-semibold text-gray-900 bg-gray-100 px-2 py-0.5 rounded-full">
                                        {data[key] ?? 0}
                                    </span>
                                </div>
                            ))}
                        </div>
                    </div>

                    <div className="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                        <div className="flex items-center gap-2 mb-4">
                            <Icon path={mdiCreditCard} size={0.85} className="text-blue-700" />
                            <h3 className="font-semibold text-gray-900 text-sm">Quick Actions</h3>
                        </div>
                        <div className="space-y-2">
                            {[
                                { label: 'View All Jobs', href: '/admin/jobs' },
                                { label: 'View Companies', href: '/admin/companies' },
                                { label: 'Manage Users', href: '/users' },
                                { label: 'Check Applications', href: '/applications' },
                                { label: 'Contact Submissions', href: '/contact' },
                                { label: 'Problem Reports', href: '/problems' },
                            ].map(({ label, href }) => (
                                <a
                                    key={href}
                                    href={href}
                                    className="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-700 transition-colors group"
                                >
                                    <span>{label}</span>
                                    <span className="text-gray-300 group-hover:text-blue-400">→</span>
                                </a>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
