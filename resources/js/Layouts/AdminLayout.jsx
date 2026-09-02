import { Link, usePage } from '@inertiajs/react';
import Icon from '@mdi/react';
import {
    mdiViewDashboard,
    mdiBriefcase,
    mdiOfficeBuilding,
    mdiAccountGroup,
    mdiFileDocument,
    mdiEmail,
    mdiAlertCircle,
    mdiCreditCard,
    mdiOpenInNew,
    mdiLogout,
} from '@mdi/js';
import { useEffect } from 'react';

const navItems = [
    { label: 'Dashboard',           icon: mdiViewDashboard,  href: '/admin' },
    { label: 'Manage Jobs',         icon: mdiBriefcase,      href: '/admin/jobs' },
    { label: 'Companies',           icon: mdiOfficeBuilding, href: '/admin/index' },
    { label: 'Users',               icon: mdiAccountGroup,   href: '/users' },
    { label: 'Applications',        icon: mdiFileDocument,   href: '/applications' },
    { label: 'Contact Submissions', icon: mdiEmail,          href: '/contact/index' },
    { label: 'Reports',             icon: mdiAlertCircle,    href: '/problems' },
    { label: 'Payments',            icon: mdiCreditCard,     href: '/payments' },
];

function isActive(href, url) {
    if (href === '/admin') return url === '/admin' || url === '/admin/';
    return url.startsWith(href);
}

export default function AdminLayout({ children, title = 'Admin Dashboard' }) {
    const { url, props } = usePage();
    const flash = props.flash ?? {};

    useEffect(() => {
        if (flash.success) {
            window.Swal?.fire({ toast: true, position: 'top-end', icon: 'success', title: flash.success, showConfirmButton: false, timer: 3000, timerProgressBar: true });
        }
        if (flash.error) {
            window.Swal?.fire({ toast: true, position: 'top-end', icon: 'error', title: flash.error, showConfirmButton: false, timer: 3000, timerProgressBar: true });
        }
    }, [flash.success, flash.error]);

    return (
        <div className="flex h-screen overflow-hidden bg-gray-50">
            {/* Sidebar */}
            <aside className="w-60 flex-shrink-0 bg-gray-900 flex flex-col">
                <div className="h-16 flex items-center px-5 border-b border-gray-700 gap-3">
                    <img src="/assets/images/logo/logo.jpg" alt="Jobify" className="w-8 h-8 rounded-full object-cover ring-2 ring-gray-600" />
                    <div>
                        <div className="text-white font-semibold text-sm leading-tight">Jobify</div>
                        <div className="text-gray-400 text-xs">Admin Panel</div>
                    </div>
                </div>

                <nav className="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                    {navItems.map(({ label, icon, href }) => (
                        <Link
                            key={href}
                            href={href}
                            className={`flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors ${
                                isActive(href, url)
                                    ? 'bg-blue-700 text-white'
                                    : 'text-gray-400 hover:bg-gray-800 hover:text-white'
                            }`}
                        >
                            <Icon path={icon} size={0.75} />
                            {label}
                        </Link>
                    ))}
                </nav>

                <div className="p-3 border-t border-gray-700 space-y-0.5">
                    <a
                        href="/index"
                        className="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white transition-colors"
                    >
                        <Icon path={mdiOpenInNew} size={0.75} />
                        View Site
                    </a>
                    <form method="POST" action="/logout">
                        <input type="hidden" name="_token" value={document.querySelector('meta[name=csrf-token]')?.content} />
                        <button
                            type="submit"
                            className="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-red-400 transition-colors"
                        >
                            <Icon path={mdiLogout} size={0.75} />
                            Sign Out
                        </button>
                    </form>
                </div>
            </aside>

            {/* Content */}
            <div className="flex-1 flex flex-col overflow-hidden">
                <header className="h-16 bg-white border-b border-gray-200 flex items-center px-6 flex-shrink-0 shadow-sm">
                    <h1 className="text-base font-semibold text-gray-900">{title}</h1>
                </header>
                <main className="flex-1 overflow-y-auto p-6 bg-gray-50">
                    {children}
                </main>
            </div>
        </div>
    );
}
