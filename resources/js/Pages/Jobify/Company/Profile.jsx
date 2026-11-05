import JobifyLayout from '../../../Layouts/JobifyLayout';
import { useForm } from '@inertiajs/react';
import { Building2, Globe, Phone, MapPin, Users } from 'lucide-react';

export default function CompanyProfile({ org }) {
    const { data, setData, put, processing } = useForm({
        name: org?.name ?? '',
        industry: org?.industry ?? '',
        size: org?.size ?? '',
        city: org?.city ?? '',
        country: org?.country ?? 'QA',
        website: org?.website ?? '',
        description: org?.description ?? '',
        phone: org?.phone ?? '',
        email: org?.email ?? '',
    });

    const industries = ['Technology','Finance','Healthcare','Education','Construction','Oil & Gas','Retail','Hospitality','Logistics','Manufacturing','Government','Other'];

    const inputClass = "w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#38009A]/30 focus:border-[#38009A]/50 transition-colors";

    return (
        <JobifyLayout title="Company Profile">
            <div className="space-y-5 max-w-4xl">

                {/* Header banner */}
                <div className="rounded-2xl p-6 text-white relative overflow-hidden" style={{ background: 'linear-gradient(135deg, #1F0055 0%, #38009A 100%)' }}>
                    <div className="relative z-10 flex items-center gap-4">
                        <div className="h-16 w-16 rounded-2xl flex items-center justify-center overflow-hidden flex-shrink-0 text-2xl font-bold bg-white/20">
                            {org?.logo
                                ? <img src={`/storage/${org.logo}`} alt="" className="w-full h-full object-cover" />
                                : <span className="text-white font-bold text-2xl">{org?.name?.[0]}</span>}
                        </div>
                        <div>
                            <h1 className="text-xl font-bold">{org?.name ?? 'Your Company'}</h1>
                            <p className="text-white/60 text-sm mt-0.5">Manage your company profile and branding</p>
                            {!org?.verified && (
                                <span className="mt-2 inline-flex items-center gap-1 text-xs font-medium text-amber-200 bg-white/10 px-2.5 py-1 rounded-full">
                                    ⏳ Pending verification
                                </span>
                            )}
                        </div>
                    </div>
                    <div className="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/5" />
                    <div className="absolute -right-4 -bottom-10 h-48 w-48 rounded-full bg-white/5" />
                </div>

                {/* Logo upload */}
                <div className="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-6">
                    <h2 className="font-bold text-[#1F0055] mb-4">Company Logo</h2>
                    <div className="flex items-center gap-5">
                        <div className="w-20 h-20 rounded-2xl flex items-center justify-center overflow-hidden flex-shrink-0" style={{ background: 'linear-gradient(135deg,#EBE6F5,#D6C9F0)' }}>
                            {org?.logo
                                ? <img src={`/storage/${org.logo}`} alt="" className="w-full h-full object-cover" />
                                : <span className="text-3xl font-bold" style={{ color: '#38009A' }}>{org?.name?.[0]}</span>}
                        </div>
                        <div>
                            <label className="inline-flex items-center gap-2 border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50 cursor-pointer transition-colors">
                                <Building2 size={15} />
                                <input type="file" accept="image/*" className="hidden"
                                    onChange={e => {
                                        if (e.target.files[0]) {
                                            const fd = new FormData();
                                            fd.append('logo', e.target.files[0]);
                                            fd.append('_method', 'PUT');
                                            fd.append('_token', document.querySelector('meta[name=csrf-token]').content);
                                            fetch('/employer/profile', { method: 'POST', body: fd }).then(() => window.location.reload());
                                        }
                                    }} />
                                Upload Logo
                            </label>
                            <p className="text-xs text-gray-400 mt-2">PNG, JPG up to 2MB</p>
                        </div>
                    </div>
                </div>

                {/* Company information */}
                <form onSubmit={e => { e.preventDefault(); put('/employer/profile'); }}
                    className="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-6 space-y-5">
                    <h2 className="font-bold text-[#1F0055]">Company Information</h2>

                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">Company Name</label>
                            <div className="relative">
                                <Building2 size={15} className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" />
                                <input value={data.name} onChange={e => setData('name', e.target.value)}
                                    className={inputClass + " pl-9"} placeholder="Your company name" />
                            </div>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">Industry</label>
                            <select value={data.industry} onChange={e => setData('industry', e.target.value)} className={inputClass}>
                                <option value="">Select industry...</option>
                                {industries.map(i => <option key={i} value={i}>{i}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">City</label>
                            <div className="relative">
                                <MapPin size={15} className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" />
                                <input value={data.city} onChange={e => setData('city', e.target.value)}
                                    className={inputClass + " pl-9"} placeholder="e.g. Doha, Lahore" />
                            </div>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">Company Size</label>
                            <div className="relative">
                                <Users size={15} className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" />
                                <select value={data.size} onChange={e => setData('size', e.target.value)} className={inputClass + " pl-9"}>
                                    <option value="">Select size...</option>
                                    {['1-10','11-50','51-200','201-500','501-1000','1000+'].map(s => <option key={s} value={s}>{s}</option>)}
                                </select>
                            </div>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">Website</label>
                            <div className="relative">
                                <Globe size={15} className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" />
                                <input value={data.website} onChange={e => setData('website', e.target.value)}
                                    className={inputClass + " pl-9"} placeholder="https://yourcompany.com" />
                            </div>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                            <div className="relative">
                                <Phone size={15} className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" />
                                <input value={data.phone} onChange={e => setData('phone', e.target.value)}
                                    className={inputClass + " pl-9"} placeholder="+974 xxxx xxxx" />
                            </div>
                        </div>
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1.5">Company Description</label>
                        <textarea value={data.description} onChange={e => setData('description', e.target.value)} rows={4}
                            placeholder="Tell candidates about your company culture, mission, and what makes you a great place to work..."
                            className="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#38009A]/30 focus:border-[#38009A]/50 transition-colors resize-none" />
                    </div>

                    <div className="flex items-center gap-3 pt-1">
                        <button type="submit" disabled={processing}
                            className="px-6 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-50"
                            style={{ background: 'linear-gradient(135deg, #38009A, #9E0059)' }}>
                            {processing ? 'Saving...' : 'Save Changes'}
                        </button>
                    </div>
                </form>
            </div>
        </JobifyLayout>
    );
}
