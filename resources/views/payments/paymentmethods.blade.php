<x-form>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900">Choose a Plan</h1>
            <p class="text-gray-500 mt-2 text-sm">All transactions are secure and encrypted</p>
        </div>

        <!-- Plans -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
            <div class="bg-white border-2 border-gray-200 hover:border-blue-400 rounded-2xl p-6 text-center cursor-pointer transition-colors">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-alt text-blue-600 text-lg"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Monthly Plan</h3>
                <p class="text-sm text-gray-500 mb-3">Billed every month</p>
                <div class="text-3xl font-extrabold text-blue-600">$15</div>
                <div class="text-sm text-gray-400">/ Rs 4,300 per month</div>
            </div>
            <div class="bg-gradient-to-br from-slate-800 to-blue-900 border-2 border-transparent rounded-2xl p-6 text-center cursor-pointer relative overflow-hidden">
                <div class="absolute top-3 right-3">
                    <span class="bg-amber-400 text-amber-900 text-xs font-bold px-2 py-0.5 rounded-full">BEST VALUE</span>
                </div>
                <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-crown text-amber-400 text-lg"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-1">Yearly Plan</h3>
                <p class="text-sm text-blue-200 mb-3">Billed once per year</p>
                <div class="text-3xl font-extrabold text-white">$150</div>
                <div class="text-sm text-blue-300">/ Rs 43,000 per year</div>
            </div>
        </div>

        <div class="flex justify-center mb-10">
            <a href="/payments/create" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition-colors shadow-sm">
                Proceed to Payment <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>

        <!-- Payment Methods -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-5">Accepted Payment Methods</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 bg-gray-50 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-university text-gray-600 text-xl"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Bank Transfer</h3>
                    <p class="text-xs text-gray-500">Direct bank deposit</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:shadow-md transition-shadow">
                    <img src="{{ asset('storage/img/Easypaisa-logo.png') }}" alt="Easypaisa" class="w-14 h-14 object-contain mx-auto mb-3 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Easypaisa</h3>
                    <p class="text-xs text-gray-500">Mobile wallet</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:shadow-md transition-shadow">
                    <img src="{{ asset('storage/img/jazz.png') }}" alt="JazzCash" class="w-14 h-14 object-contain mx-auto mb-3 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">JazzCash</h3>
                    <p class="text-xs text-gray-500">Mobile wallet</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:shadow-md transition-shadow">
                    <img src="{{ asset('storage/img/mastercard.png') }}" alt="Card" class="w-14 h-14 object-contain mx-auto mb-3 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Credit / Debit Card</h3>
                    <p class="text-xs text-gray-500">Visa, Mastercard, Amex</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:shadow-md transition-shadow">
                    <div class="w-14 h-14 bg-blue-50 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fab fa-paypal text-blue-700 text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">PayPal</h3>
                    <p class="text-xs text-gray-500">Secure online payment</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:shadow-md transition-shadow">
                    <img src="{{ asset('storage/img/google.jpeg') }}" alt="Google Pay" class="w-14 h-14 object-contain mx-auto mb-3 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Google Pay</h3>
                    <p class="text-xs text-gray-500">Pay with Google</p>
                </div>
            </div>
        </div>
    </div>
</x-form>
