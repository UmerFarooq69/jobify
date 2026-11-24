import { useState } from 'react';
import { Link } from '@inertiajs/react';

function getCsrf() {
    return document.head.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

export default function EmployerLogin() {
    const [email, setEmail]       = useState('');
    const [password, setPassword] = useState('');
    const [error, setError]       = useState('');
    const [loading, setLoading]   = useState(false);

    async function handleSubmit(e) {
        e.preventDefault();
        setError(''); setLoading(true);
        try {
            const res = await fetch('/employer/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrf(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });
            const d = await res.json();
            if (!res.ok) {
                setError(d.errors?.email?.[0] ?? d.errors?.password?.[0] ?? 'Invalid credentials.');
                return;
            }
            if (d.csrf) {
                const meta = document.head.querySelector('meta[name="csrf-token"]');
                if (meta) meta.setAttribute('content', d.csrf);
            }
            window.location.href = d.redirect ?? '/employer/dashboard';
        } catch {
            setError('Something went wrong. Please try again.');
        } finally {
            setLoading(false);
        }
    }

    return (
        <div className="min-h-screen bg-gradient-to-br from-purple-50 to-white flex items-center justify-center p-4">
            <div className="w-full max-w-md">
                {/* Logo */}
                <div className="text-center mb-8">
                    <Link href="/">
                        <img src="/assets/images/logo/logo.jpg" alt="Jobify" className="h-12 w-auto mx-auto object-contain" onError={e => { e.target.style.display = 'none'; }} />
                    </Link>
                    <h1 className="text-2xl font-bold text-gray-900 mt-4">Employer Sign In</h1>
                    <p className="text-gray-500 mt-1 text-sm">Sign in to manage your jobs and candidates</p>
                </div>

                <div className="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
                    <form onSubmit={handleSubmit} className="space-y-5">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                            <input
                                type="email"
                                value={email}
                                onChange={e => setEmail(e.target.value)}
                                required
                                placeholder="you@company.com"
                                className="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                style={{ '--tw-ring-color': '#9E0059' }}
                            />
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                            <input
                                type="password"
                                value={password}
                                onChange={e => setPassword(e.target.value)}
                                required
                                placeholder="Enter your password"
                                className="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            />
                        </div>

                        {error && (
                            <div className="rounded-xl bg-red-50 border border-red-100 px-4 py-3 text-sm text-red-600">
                                {error}
                            </div>
                        )}

                        <button
                            type="submit"
                            disabled={loading}
                            className="w-full py-3 rounded-xl text-sm font-semibold text-white transition disabled:opacity-60"
                            style={{ background: 'linear-gradient(135deg, #38009A, #9E0059)' }}
                        >
                            {loading ? 'Signing in...' : 'Sign In'}
                        </button>
                    </form>

                    <div className="mt-6 text-center text-sm text-gray-500">
                        Don't have an account?{' '}
                        <Link href="/" className="font-semibold" style={{ color: '#9E0059' }}>
                            Register with OTP
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    );
}
