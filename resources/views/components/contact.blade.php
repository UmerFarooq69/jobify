<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        <!-- Section Label -->
        <div class="text-center mb-12">
            <span class="inline-block text-xs font-semibold text-blue-600 uppercase tracking-widest mb-3">Contact Us</span>
            <h2 class="text-3xl font-bold text-gray-900">Get in Touch</h2>
            <p class="text-gray-500 mt-3 max-w-md mx-auto text-sm leading-relaxed">Have a question or want to work with us? Send us a message and we'll respond within 24 hours.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-0 rounded-2xl overflow-hidden shadow-lg border border-gray-100">

            <!-- Left: Contact Info Panel -->
            <div class="lg:col-span-2 bg-blue-600 p-8 lg:p-10 flex flex-col justify-between">
                <div>
                    <h3 class="text-white font-bold text-xl mb-2">Contact Information</h3>
                    <p class="text-blue-200 text-sm mb-8">Reach out through any of these channels</p>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-map-marker-alt text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-white text-xs font-semibold uppercase tracking-wide mb-1">Address</p>
                                <p class="text-blue-100 text-sm leading-relaxed">Ross Residentia, Canal Rd, Lahore, Punjab, Pakistan</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-white text-xs font-semibold uppercase tracking-wide mb-1">Phone</p>
                                <p class="text-blue-100 text-sm">+92-301-4370259</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-white text-xs font-semibold uppercase tracking-wide mb-1">Email</p>
                                <p class="text-blue-100 text-sm">info@jobify.official</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-white text-xs font-semibold uppercase tracking-wide mb-1">Hours</p>
                                <p class="text-blue-100 text-sm">Mon–Fri, 10:00am–6:00pm</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                <div class="mt-8 rounded-xl overflow-hidden h-40 border-2 border-white/20">
                    <iframe class="w-full h-full"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13609.928126770877!2d74.27810847759245!3d31.483431772521445!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391903c2002b3995%3A0x320b96105e77f88!2sRoss%20Residentia!5e0!3m2!1sen!2s!4v1738928990351!5m2!1sen!2s"
                        allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </div>

            <!-- Right: Form -->
            <div class="lg:col-span-3 bg-white p-8 lg:p-10">
                <h3 class="text-xl font-bold text-gray-900 mb-1">Send a Message</h3>
                <p class="text-gray-400 text-sm mb-7">We'll get back to you as soon as possible</p>

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Full Name</label>
                            <input type="text" name="name" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all"
                                placeholder="John Doe">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Email Address</label>
                            <input type="email" name="email" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all"
                                placeholder="john@example.com">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Message</label>
                        <textarea name="message" rows="5" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all resize-none"
                            placeholder="How can we help you?"></textarea>
                    </div>
                    <div class="flex items-center justify-between pt-1">
                        <div class="flex items-center gap-4 text-xs text-gray-400">
                            <a href="/problem" class="hover:text-red-500 transition-colors">Report a problem</a>
                            <a href="/pay" class="hover:text-blue-600 transition-colors">Payment info</a>
                        </div>
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors text-sm shadow-sm">
                            Send Message <i class="fas fa-paper-plane text-xs"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({ icon: "success", title: "Message Sent!", text: "{{ session('success') }}", timer: 2500, showConfirmButton: false });
        });
    </script>
@endif
