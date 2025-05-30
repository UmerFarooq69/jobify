<div
    class="w-full min-h-screen bg-gradient-to-r from-blue-900 to-blue-100 flex items-center justify-center p-4 sm:p-6 md:p-10">
    <div class="w-full max-w-6xl bg-white shadow-xl rounded-lg p-6 sm:p-8 md:p-10 flex flex-col md:flex-row gap-10">

        <!-- Left Side (Contact Details) -->
        <div class="w-full md:w-1/2 space-y-6">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-800">Our Contact</h2>

            <div class="space-y-3 text-gray-600 text-sm sm:text-base">
                <p class="flex items-start">
                    <span class="mr-2 text-xl">📍</span>
                    Ross Resenditia, Canal Rd, Quaid-i-Azam Campus, Lahore, Punjab, Pakistan
                </p>
                <p class="flex items-center">
                    <span class="mr-2 text-xl">📞</span> +92 3014370259
                </p>
                <p class="flex items-center">
                    <span class="mr-2 text-xl">✉️</span> info@jobify.official
                </p>
                <p class="flex items-center">
                    <span class="mr-2 text-xl">⏰</span> Monday — Friday | 10:00am - 6:00pm
                </p>
            </div>

            <div class="w-full h-56 sm:h-64 overflow-hidden rounded-lg shadow-md">
                <iframe class="w-full h-full"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13609.928126770877!2d74.27810847759245!3d31.483431772521445!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391903c2002b3995%3A0x320b96105e77f88!2sRoss%20Residentia!5e0!3m2!1sen!2s!4v1738928990351!5m2!1sen!2s"
                    allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>

        <!-- Right Side (Contact Form) -->
        <div class="w-full md:w-1/2 bg-white p-4 sm:p-6 border rounded-lg shadow-md">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-800 mb-6">Contact Form</h2>

            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Full Name</label>
                    <input type="text" name="name"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-300 focus:border-indigo-500 shadow-sm text-sm sm:text-base">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">E-mail</label>
                    <input type="email" name="email"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-300 focus:border-indigo-500 shadow-sm text-sm sm:text-base">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Message</label>
                    <textarea name="message" rows="4"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-300 focus:border-indigo-500 shadow-sm text-sm sm:text-base"></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-900 to-blue-300 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-800 transition-all duration-300 shadow-md">
                    Send Message
                </button>

                <div class="mt-6 space-y-2 text-center">
                    <a href="/problem" class="text-red-500 block hover:underline">Report a problem</a>
                    <a href="/pay" class="text-red-500 block hover:underline">Payment?</a>
                </div>
            </form>
        </div>

    </div>
</div>
<!-- SweetAlert Success -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                icon: "success",
                title: "Success!",
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
@endif