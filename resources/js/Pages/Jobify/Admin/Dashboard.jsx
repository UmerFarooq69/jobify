import JobifyLayout from '../../../Layouts/JobifyLayout';
import { Link } from '@inertiajs/react';
import { Users, UserCheck, Building2, Briefcase, Clock, Shield, Tag, Mail, Settings, ClipboardList, HeartPulse } from 'lucide-react';

function StatCard({ value, label, icon, href, bg, color }) {
    return (
        <Link href={href} className="bg-white rounded-2xl p-5 shadow-sm ring-1 ring-gray-100 hover:shadow-md hover:ring-[#38009A]/20 transition-all flex items-center gap-4">
            <div className="h-12 w-12 rounded-xl flex items-center justify-center flex-shrink-0" style={{ background: bg }}>
                {icon}
            </div>
            <div>
                <div className="text-2xl font-bold" style={{ color }}>{value ?? 0}</div>
                <div className="text-sm text-gray-500">{label}</div>
            </div>
        </Link>
    );
}

export default function QTAdminDashboard({ stats }) {
    return (
        <JobifyLayout title="Dashboard">
            <div className="space-y-5 max-w-5xl">

                {/* Header banner */}
                <div className="rounded-2xl p-6 text-white relative overflow-hidden" style={{ background: 'linear-gradient(135deg, #1F0055 0%, #38009A 100%)' }}>
                    <div className="relative z-10">
                        <div className="flex items-center gap-3 mb-1">
                            <div className="h-9 w-9 rounded-xl flex items-center justify-center" style={{ background: 'rgba(255,255,255,0.15)' }}>
                                <svg viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth={1.8} className="w-5 h-5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <h1 className="text-xl font-bold">Platform Admin</h1>
                        </div>
                        <p className="text-white/60 text-sm">Monitor the platform, review content, and manage settings.</p>
                    </div>
                    <div className="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/5" />
                    <div className="absolute -right-4 -bottom-10 h-48 w-48 rounded-full bg-white/5" />
                </div>

                {/* Stats */}
                <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                    <StatCard value={stats?.users}        label="Total Users"    icon={<Users size={22} color="#38009A" />}     href="/platform-admin/users"      bg="linear-gradient(135deg,#EBE6F5,#D6C9F0)" color="#38009A" />
                    <StatCard value={stats?.candidates}   label="Candidates"     icon={<UserCheck size={22} color="#92400E" />} href="/platform-admin/moderation" bg="linear-gradient(135deg,#FEF3C7,#FDE68A)" color="#92400E" />
                    <StatCard value={stats?.companies}    label="Companies"      icon={<Building2 size={22} color="#065F46" />} href="/platform-admin/moderation" bg="linear-gradient(135deg,#D1FAE5,#A7F3D0)" color="#065F46" />
                    <StatCard value={stats?.active_jobs}  label="Active Jobs"    icon={<Briefcase size={22} color="#1E40AF" />} href="/platform-admin/moderation" bg="linear-gradient(135deg,#DBEAFE,#BFDBFE)" color="#1E40AF" />
                    <StatCard value={stats?.pending_jobs} label="Pending Review" icon={<Clock size={22} color="#991B1B" />}    href="/platform-admin/moderation" bg="linear-gradient(135deg,#FEE2E2,#FECACA)" color="#991B1B" />
                </div>

                {/* Quick Links */}
                <div>
                    <h2 className="text-base font-bold text-[#1F0055] mb-3">Platform Management</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {[
                            { label: 'Moderation Queue',  desc: 'Review pending jobs and companies',    href: '/platform-admin/moderation',      icon: Shield,         urgent: stats?.pending_jobs > 0 },
                            { label: 'Job Categories',    desc: 'Manage job types and functions',       href: '/platform-admin/job-taxonomy',    icon: Tag,            urgent: false },
                            { label: 'Email Templates',   desc: 'Edit transactional email templates',   href: '/platform-admin/email-templates', icon: Mail,           urgent: false },
                            { label: 'Platform Settings', desc: 'Configure AI, parser & notifications', href: '/platform-admin/settings',        icon: Settings,       urgent: false },
                            { label: 'Audit Log',         desc: 'Track all admin actions and changes',  href: '/platform-admin/audit',           icon: ClipboardList,  urgent: false },
                            { label: 'System Health',     desc: 'Monitor database, cache and queue',    href: '/platform-admin/health',          icon: HeartPulse,     urgent: false },
                        ].map(item => (
                            <Link key={item.label} href={item.href}
                                className="bg-white rounded-2xl p-5 shadow-sm ring-1 ring-gray-100 hover:shadow-md hover:ring-[#38009A]/20 transition-all flex items-center gap-4 group">
                                <div className="h-11 w-11 rounded-xl flex items-center justify-center flex-shrink-0" style={{ background: item.urgent ? '#FEE2E2' : '#EBE6F5' }}>
                                    <item.icon size={20} color={item.urgent ? '#991B1B' : '#38009A'} />
                                </div>
                                <div className="flex-1 min-w-0">
                                    <div className="font-semibold text-sm text-[#1F0055] group-hover:text-[#38009A] transition-colors flex items-center gap-2">
                                        {item.label}
                                        {item.urgent && <span className="text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">{stats.pending_jobs} pending</span>}
                                    </div>
                                    <div className="text-xs text-gray-400 mt-0.5">{item.desc}</div>
                                </div>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} className="w-4 h-4 text-gray-300 group-hover:text-[#38009A] transition-colors flex-shrink-0"><polyline points="9 18 15 12 9 6"/></svg>
                            </Link>
                        ))}
                    </div>
                </div>
            </div>
        </JobifyLayout>
    );
}
