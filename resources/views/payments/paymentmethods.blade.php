<x-form>
  <!-- Header Section -->
  <div class="text-center bg-gray-100 p-6 rounded-lg shadow-lg mt-10 max-w-4xl mx-auto">
    <h2 class="text-3xl font-bold text-gray-800 mb-4">Choose a Payment Method</h2>
    <p class="text-gray-600 mb-6">Select your preferred way to pay for your order. All transactions are secure and encrypted.</p>
  </div>

  <!-- Subscription Plans -->
  <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-6 mt-8 flex justify-center gap-12">
    <!-- Monthly Plan -->
    <div class="border border-gray-300 rounded-xl p-6 w-48 text-center hover:shadow-lg transition cursor-pointer">
      <h3 class="text-2xl font-bold mb-2">Monthly Plan</h3>
      <p class="text-gray-600 text-lg mb-1">Billed every month</p>
      <p class="text-3xl font-extrabold text-blue-600">$15 / Rs 4,300<span class="text-base font-normal text-gray-500">/ month</span></p>
    </div>

    <!-- Yearly Plan -->
    <div class="border border-gray-300 rounded-xl p-6 w-48 text-center hover:shadow-lg transition cursor-pointer">
      <h3 class="text-2xl font-bold mb-2">Yearly Plan</h3>
      <p class="text-gray-600 text-lg mb-1">Billed once per year</p>
      <p class="text-3xl font-extrabold text-blue-600">$150 / Rs 43,000<span class="text-base font-normal text-gray-500">/ year</span></p>
    </div>
  </div>
  <div class="flex justify-center mt-6 mb-2">
    <a href="/payments/create"
       class="w-full md:w-1/2 lg:w-1/3 bg-blue-600 text-white px-8 py-4 rounded-md hover:bg-blue-700 transition text-center block">
      Pay the Amount
    </a>
  </div>
  <!-- Payment Methods Grid -->
  <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6 mb-6">
    <!-- Bank Transfer -->
    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition">
      <div class="flex items-center justify-center mb-4">
        <img src="https://img.icons8.com/ios-filled/50/bank.png" class="w-20 h-20 rounded-full mx-auto mb-4" alt="Bank" />
      </div>
      <h3 class="text-xl font-semibold text-center mb-2">Bank Transfer</h3>
      <p class="text-gray-600 text-sm text-center">Transfer directly to our bank account and upload the receipt.</p>
    </div>

    <!-- Easypaisa -->
    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition">
      <div class="flex items-center justify-center mb-4">
        <img src="{{ asset('storage/img/Easypaisa-logo.png') }}" alt="Easypaisa" class="w-20 h-20 rounded-full mx-auto mb-4">
      </div>
      <h3 class="text-xl font-semibold text-center mb-2">Easypaisa</h3>
      <p class="text-gray-600 text-sm text-center">Send payment via Easypaisa mobile wallet.</p>
    </div>

    <!-- JazzCash -->
    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition">
      <div class="flex items-center justify-center mb-4">
        <img src="{{ asset('storage/img/jazz.png') }}" class="w-20 h-20 rounded-full mx-auto mb-4" alt="JazzCash" />
      </div>
      <h3 class="text-xl font-semibold text-center mb-2">JazzCash</h3>
      <p class="text-gray-600 text-sm text-center">Transfer funds via JazzCash wallet or app.</p>
    </div>

    <!-- Credit/Debit Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition">
      <div class="flex items-center justify-center mb-4">
        <img src="{{ asset('storage/img/mastercard.png') }}" class="w-20 h-20 rounded-full mx-auto mb-4" alt="Card" />
      </div>
      <h3 class="text-xl font-semibold text-center mb-2">Credit / Debit Card</h3>
      <p class="text-gray-600 text-sm text-center">Pay using Visa, MasterCard, or American Express.</p>
    </div>

    <!-- PayPal -->
    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition">
      <div class="flex items-center justify-center mb-4">
        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="w-20 h-20 rounded-full mx-auto mb-4" alt="PayPal" />
      </div>
      <h3 class="text-xl font-semibold text-center mb-2">PayPal</h3>
      <p class="text-gray-600 text-sm text-center">You will be redirected to PayPal to complete your purchase securely.</p>
    </div>

    <!-- Google Pay -->
    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition">
      <div class="flex items-center justify-center mb-4">
        <img src="{{ asset('storage/img/google.jpeg') }}" class="w-20 h-20 rounded-full mx-auto mb-4" alt="Google Pay" />
      </div>
      <h3 class="text-xl font-semibold text-center mb-2">Google Pay</h3>
      <p class="text-gray-600 text-sm text-center">Pay quickly using your Google account and saved cards.</p>
    </div>
  </div> <!-- End of Grid -->
</x-form>
