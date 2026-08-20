<x-form>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
        <!-- Back link -->
        <a href="/pay" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-6 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i> Back to Plans
        </a>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Submit Payment</h1>
                <p class="text-gray-500 text-sm mt-1">Complete your subscription by filling in the details below</p>
            </div>

            <form action="/payments/store" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                        placeholder="Enter your full name">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" name="email" id="email" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                        placeholder="your@email.com">
                </div>

                <div>
                    <label for="number" class="block text-sm font-medium text-gray-700 mb-1.5">Mobile Number</label>
                    <input type="tel" name="number" id="number" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                        placeholder="+92 300 0000000">
                </div>

                <div>
                    <label for="plan" class="block text-sm font-medium text-gray-700 mb-1.5">Payment Plan</label>
                    <select name="plan" id="plan" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-sm text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors">
                        <option value="">Select a Plan</option>
                        <option value="Monthly">Monthly — $15 / Rs 4,300</option>
                        <option value="Yearly">Yearly — $150 / Rs 43,000</option>
                    </select>
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1.5">Payment Method</label>
                    <select name="payment_method" id="payment_method" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-sm text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors">
                        <option value="">Select Payment Method</option>
                        <option value="JazzCash">JazzCash</option>
                        <option value="EasyPaisa">EasyPaisa</option>
                        <option value="Bank">Bank Transfer</option>
                        <option value="GooglePay">Google Pay</option>
                        <option value="Paypal">PayPal</option>
                        <option value="CreditCard/DebitCard">Credit Card / Debit Card</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Upload Payment Receipt</label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 transition-colors cursor-pointer">
                        <input type="file" name="attachment" id="attachment" accept="image/*,application/pdf" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">Drag & drop or <span class="text-blue-600 font-medium">browse</span></p>
                        <p class="text-xs text-gray-400 mt-1">Image or PDF accepted</p>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg transition-colors font-semibold text-sm shadow-sm">
                    Submit Payment
                </button>
            </form>
        </div>
    </div>
</x-form>
