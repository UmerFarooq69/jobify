<x-form>
    <!-- Page Header -->
    <div class="bg-white border-b border-gray-100 py-10 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <span class="inline-block text-xs font-semibold text-blue-600 uppercase tracking-widest mb-2">Subscription</span>
            <h1 class="text-3xl font-bold text-gray-900">Choose a Plan</h1>
            <p class="text-gray-500 mt-2 text-sm">All transactions are secure and encrypted. Cancel anytime.</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">

        <!-- Plans -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
            <!-- Monthly -->
            <div class="bg-white border-2 border-gray-200 hover:border-blue-300 rounded-2xl p-7 text-center cursor-pointer transition-all hover:shadow-md">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-alt text-blue-600 text-lg"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Monthly</h3>
                <p class="text-sm text-gray-500 mb-4">Billed every month, flexible</p>
                <div class="text-4xl font-extrabold text-gray-900">$15</div>
                <div class="text-sm text-gray-400 mt-1">/ Rs 4,300 per month</div>
            </div>

            <!-- Yearly (featured) -->
            <div class="bg-blue-600 border-2 border-blue-600 rounded-2xl p-7 text-center cursor-pointer relative overflow-hidden shadow-lg hover:bg-blue-700 transition-colors">
                <div class="absolute top-4 right-4">
                    <span class="bg-amber-400 text-amber-900 text-xs font-bold px-2.5 py-1 rounded-full">BEST VALUE</span>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-crown text-amber-300 text-lg"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-1">Yearly</h3>
                <p class="text-sm text-blue-200 mb-4">Billed once per year, save 17%</p>
                <div class="text-4xl font-extrabold text-white">$150</div>
                <div class="text-sm text-blue-200 mt-1">/ Rs 43,000 per year</div>
            </div>
        </div>

        <div class="flex justify-center mb-12">
            <a href="/payments/create" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3.5 rounded-xl transition-colors shadow-sm text-sm">
                Proceed to Payment <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>

        <!-- Payment Methods -->
        <div>
            <div class="text-center mb-6">
                <h2 class="text-lg font-bold text-gray-900">Accepted Payment Methods</h2>
                <p class="text-sm text-gray-400 mt-1">Pay securely using any of the following</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:shadow-md transition-shadow hover:border-gray-300">
                    <div class="w-14 h-14 bg-gray-50 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-university text-gray-600 text-xl"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-0.5">Bank Transfer</h3>
                    <p class="text-xs text-gray-400">Direct bank deposit</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:shadow-md transition-shadow hover:border-gray-300">
                    <img src="{{ asset('storage/img/Easypaisa-logo.png') }}" alt="Easypaisa" class="w-14 h-14 object-contain mx-auto mb-3 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-900 mb-0.5">Easypaisa</h3>
                    <p class="text-xs text-gray-400">Mobile wallet</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:shadow-md transition-shadow hover:border-gray-300">
                    <img src="{{ asset('storage/img/jazz.png') }}" alt="JazzCash" class="w-14 h-14 object-contain mx-auto mb-3 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-900 mb-0.5">JazzCash</h3>
                    <p class="text-xs text-gray-400">Mobile wallet</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:shadow-md transition-shadow hover:border-gray-300">
                    <img src="{{ asset('storage/img/mastercard.png') }}" alt="Card" class="w-14 h-14 object-contain mx-auto mb-3 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-900 mb-0.5">Credit / Debit Card</h3>
                    <p class="text-xs text-gray-400">Visa, Mastercard, Amex</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:shadow-md transition-shadow hover:border-gray-300">
                    <div class="w-14 h-14 bg-blue-50 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fab fa-paypal text-blue-700 text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-0.5">PayPal</h3>
                    <p class="text-xs text-gray-400">Secure online payment</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-5 text-center hover:shadow-md transition-shadow hover:border-gray-300">
                    <img src="{{ asset('storage/img/google.jpeg') }}" alt="Google Pay" class="w-14 h-14 object-contain mx-auto mb-3 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-900 mb-0.5">Google Pay</h3>
                    <p class="text-xs text-gray-400">Pay with Google</p>
                </div>
            </div>
        </div>
    </div>
</x-form>
