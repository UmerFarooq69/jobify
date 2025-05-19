<x-form>
  <form action="/payments/store" method="POST" enctype="multipart/form-data" class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg mt-10">
    @csrf
    <div>
    </div>
    <!-- Name -->
    <div class="mb-4">
      <label for="name" class="block text-gray-700 font-semibold mb-2">Name</label>
      <input type="text" name="name" id="name" required
      class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    
    <!-- Email -->
    <div class="mb-4">
      <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
      <input type="email" name="email" id="email" required
      class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    
    <div class="mb-4">
      <label for="payment_method" class="block text-gray-700 font-semibold mb-2">Payment Method</label>
      <select name="payment_method" id="payment_method" required
      class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
      <option value="">Select Payment Method</option>
      <option value="JazzCash">JazzCash</option>
      <option value="EasyPaisa">EasyPaisa</option>
      <option value="Bank">Bank</option>
      <option value="GooglePay">GooglePay</option>
      <option value="Paypal">Paypal</option>
      <option value="CreditCard/DebitCard">Credit Card / Debit Card</option>
    </select>
  </div>
  
  
  <!-- Attachment -->
  <div class="mb-6">
    <label for="attachment" class="block text-gray-700 font-semibold mb-2">Upload Payment Receipt</label>
    <input type="file" name="attachment" id="attachment" accept="image/*,application/pdf" required
    class="w-full px-4 py-2 border rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
  </div>
  
  <!-- Submit Button -->
  <div class="flex justify-center">
    <button type="submit"
    class="w-full md:w-1/2 lg:w-1/3 bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 transition text-center">
    Submit Payment
  </button>
</div>
</form>
</x-form>
