import { useForm } from '@inertiajs/react';

export default function CompanySetup() {
    const { data, setData, post, processing, errors } = useForm({
        name: '', industry: '', size: '', city: '', country: 'QA', website: '',
    });

    const submit = (e) => { e.preventDefault(); post('/employer/setup'); };

    const industries = ['Technology','Finance','Healthcare','Education','Construction','Oil & Gas','Retail','Hospitality','Logistics','Manufacturing','Government','Other'];
    const sizes = ['1-10','11-50','51-200','201-500','501-1000','1000+'];

    return (
        <div className="min-h-screen bg-gradient-to-br from-indigo-50 to-white flex items-center justify-center p-4">
            <div className="w-full max-w-lg">
                <div className="text-center mb-8">
                    <div className="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center mx-auto mb-3">
                        <span className="text-white font-bold text-xl">Q</span>
                    </div>
                    <h1 className="text-2xl font-bold text-gray-900">Set up your company</h1>
                    <p className="text-gray-500 mt-1">Tell candidates about your organization</p>
                </div>

                <form onSubmit={submit} className="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4">
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Company Name *</label>
                        <input value={data.name} onChange={e => setData('name', e.target.value)} required
                            placeholder="e.g. Acme Corporation" className="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                        {errors.name && <p className="text-red-500 text-xs mt-1">{errors.name}</p>}
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Industry *</label>
                        <select value={data.industry} onChange={e => setData('industry', e.target.value)} required
                            className="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">Select industry</option>
                            {industries.map(i => <option key={i} value={i}>{i}</option>)}
                        </select>
                    </div>
                    <div className="grid grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Company Size</label>
                            <select value={data.size} onChange={e => setData('size', e.target.value)}
                                className="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <option value="">Select size</option>
                                {sizes.map(s => <option key={s} value={s}>{s}</option>)}
                            </select>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input value={data.city} onChange={e => setData('city', e.target.value)}
                                placeholder="e.g. Doha" className="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                        </div>
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">Website</label>
                        <input value={data.website} onChange={e => setData('website', e.target.value)}
                            placeholder="https://company.com" className="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                    </div>
                    <button type="submit" disabled={processing}
                        className="w-full bg-indigo-600 text-white py-3 rounded-lg font-medium hover:bg-indigo-700 disabled:opacity-50 transition-colors">
                        {processing ? 'Creating...' : 'Create Company Profile'}
                    </button>
                </form>
            </div>
        </div>
    );
}
