<div class="bg-gray-50 py-16 px-4 sm:px-6">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900">Get in Touch</h2>
            <p class="text-gray-500 mt-2 text-sm">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <!-- Left: Contact Info -->
                <div class="bg-gradient-to-br from-slate-800 to-blue-900 p-8 text-white">
                    <h3 class="text-xl font-semibold mb-6">Contact Information</h3>
                    <div class="space-y-4 text-slate-300 text-sm">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-blue-400 mt-0.5 flex-shrink-0"></i>
                            <span>Ross Residentia, Canal Rd, Quaid-i-Azam Campus, Lahore, Punjab, Pakistan</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-phone text-blue-400 flex-shrink-0"></i>
                            <span>+92 3014370259</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-envelope text-blue-400 flex-shrink-0"></i>
                            <span>info@jobify.official</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-clock text-blue-400 flex-shrink-0"></i>
                            <span>Monday — Friday, 10:00am – 6:00pm</span>
                        </div>
                    </div>

                    <div class="mt-8 rounded-xl overflow-hidden h-48">
                        <iframe class="w-full h-full"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13609.928126770877!2d74.27810847759245!3d31.483431772521445!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391903c2002b3995%3A0x320b96105e77f88!2sRoss%20Residentia!5e0!3m2!1sen!2s!4v1738928990351!5m2!1sen!2s"
                            allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                </div>

                <!-- Right: Contact Form -->
                <div class="p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Send a Message</h3>
                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                            <input type="text" name="name"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                                placeholder="Your full name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                            <input type="email" name="email"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                                placeholder="your@email.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Message</label>
                            <textarea name="message" rows="4"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors resize-none"
                                placeholder="How can we help you?"></textarea>
                        </div>
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors shadow-sm text-sm">
                            Send Message
                        </button>
                        <div class="flex items-center justify-center gap-4 pt-2 text-sm">
                            <a href="/problem" class="text-gray-400 hover:text-red-500 transition-colors">Report a problem</a>
                            <span class="text-gray-200">|</span>
                            <a href="/pay" class="text-gray-400 hover:text-blue-600 transition-colors">Payment</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({ icon: "success", title: "Message Sent!", text: "{{ session('success') }}", timer: 2500, showConfirmButton: false });
        });
    </script>
@endif
