<x-form>
    <div>
        <!-- Hero Section -->
        <header class="relative h-auto min-h-[400px] flex items-center justify-center text-white bg-cover bg-center" 
            style="background-image: url('{{ asset('storage/img/logo.jpeg') }}');">
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
            <div class="relative z-10 text-center px-6 mb-2">
                <h1 class="text-5xl md:text-7xl font-bold mt-8">Find Your Next Professional Adventure</h1>
                <p class="text-lg md:text-2xl mt-4 max-w-3xl mx-auto">
                    Discover opportunities in Tech, Finance, Engineering, and more. Build your career with top companies worldwide.
                </p>
                <a href="#explore" class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg text-lg">Take to my dream job</a>
            </div>
        </header>
    
        <!-- About Section -->
        <section id="explore" class="py-16 px-6 text-center bg-white">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-4xl font-bold text-gray-800">Why Choose Us?</h2>
                <p class="text-lg text-gray-600 mt-4">We connect talented individuals with top companies across various industries. 
                    Our platform offers seamless job searching, personalized recommendations, and career growth opportunities tailored to your skills.</p>
            </div>
        </section>
<!-- Featured Jobs Section -->
<section class="py-16 px-6 bg-white">
    <div class="max-w-6xl mx-auto text-center">
        <h2 class="text-4xl font-bold text-gray-800">Featured Jobs</h2>

        <!-- Swiper Container -->
        <div class="swiper mySwiper mt-8">
            <div class="swiper-wrapper">
                @foreach($featuredJobs as $job)
                    <div class="swiper-slide">
                        <div class="p-6 bg-gray-100 shadow-lg rounded-lg text-left">
                            @if($job->image)
                                <img src="{{ asset('storage/' . $job->image) }}" alt="{{ $job->job_title }}" class="w-full h-48 object-cover rounded-lg mb-4">
                            @else
                                <img src="{{ asset('images/default-job.png') }}" alt="Default Image" class="w-full h-48 object-cover rounded-lg mb-4">
                            @endif

                            <h3 class="text-2xl font-semibold">{{ $job->job_title }}</h3>
                            <p class="text-gray-600 mt-2">{{ $job->company->name }}</p>
                            <p class="text-gray-500 mt-2">{{ $job->company->location }}</p>
                            <a href="{{ route('jobs.show', $job->id) }}" class="text-blue-600 font-semibold mt-2 inline-block">
                                View Job →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Navigation Buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

        <!-- Features Section -->
        <section class="py-16 px-6 bg-gray-100">
            <div class="max-w-6xl mx-auto text-center">
                <h2 class="text-4xl font-bold text-gray-800">Our Key Features</h2>
                <div class="grid md:grid-cols-3 gap-8 mt-8">
                    <div class="p-6 bg-white shadow-lg rounded-lg">
                        <h3 class="text-2xl font-semibold">Advanced Job Search</h3>
                        <p class="text-gray-600 mt-2">Filter and find the perfect job based on your preferences and skills.</p>
                    </div>
                    <div class="p-6 bg-white shadow-lg rounded-lg">
                        <h3 class="text-2xl font-semibold">AI-Powered Matching</h3>
                        <p class="text-gray-600 mt-2">Get job recommendations that align perfectly with your experience.</p>
                    </div>
                    <div class="p-6 bg-white shadow-lg rounded-lg">
                        <h3 class="text-2xl font-semibold">Career Development</h3>
                        <p class="text-gray-600 mt-2">Access courses, mentorship, and resources to grow your career.</p>
                    </div>
                </div>
            </div>
        </section>
    
        <!-- Statistics Section -->
        <section class="py-16 px-6 text-center bg-white">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-4xl font-bold text-gray-800">Our Impact</h2>
                <div class="grid md:grid-cols-3 gap-8 mt-8">
                    <div>
                        <h3 class="text-5xl font-bold text-blue-600">500K+</h3>
                        <p class="text-lg text-gray-600">Jobs Listed</p>
                    </div>
                    <div>
                        <h3 class="text-5xl font-bold text-blue-600">200K+</h3>
                        <p class="text-lg text-gray-600">Successful Hires</p>
                    </div>
                    <div>
                        <h3 class="text-5xl font-bold text-blue-600">50+</h3>
                        <p class="text-lg text-gray-600">Industries Covered</p>
                    </div>
                </div>
            </div>
        </section>
    
        <!-- Blog Section -->
        <section class="py-16 px-6 bg-gray-100 text-center">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-4xl font-bold text-gray-800">Latest Career Insights</h2>
                <div class="grid md:grid-cols-2 gap-8 mt-8">
                    <div class="p-6 bg-white shadow-lg rounded-lg">
                        <h3 class="text-2xl font-semibold">How to Ace Your Next Interview</h3>
                        <p class="text-gray-600 mt-2">Top strategies to impress recruiters and land your dream job.</p>
                        <a href="/blog/interview-tips" class="text-blue-600 font-semibold mt-2 inline-block">Read More</a>
                    </div>
                    <div class="p-6 bg-white shadow-lg rounded-lg">
                        <h3 class="text-2xl font-semibold">Top In-Demand Skills in 2025</h3>
                        <p class="text-gray-600 mt-2">Stay ahead of the curve with these essential career skills.</p>
                        <a href="/blog/in-demand-skills" class="text-blue-600 font-semibold mt-2 inline-block">Read More</a>
                    </div>
                </div>
            </div>
        </section>
                <!-- Testimonials Section -->
                <section class="py-16 px-6 text-center bg-white">
                    <div class="max-w-6xl mx-auto">
                        <h2 class="text-4xl font-bold text-gray-800">What Our Users Say</h2>
                        <div class="grid md:grid-cols-2 gap-8 mt-8">
                            <div class="p-6 bg-gray-100 shadow-md rounded-lg">
                                <img src="{{ asset('storage/img/female-avatar.jpg') }}" alt="Sarah M." class="w-20 h-20 rounded-full mx-auto mb-4">
                                <p class="text-gray-700">“This platform helped me land my dream job in just two weeks. Highly recommended!”</p>
                                <h3 class="font-semibold mt-4">— Sarah M.</h3>
                            </div>
                            <div class="p-6 bg-gray-100 shadow-md rounded-lg">
                                <img src="{{ asset('storage/img/male-avatar.jpg') }}" alt="David L." class="w-20 h-20 rounded-full mx-auto mb-4">
                                <p class="text-gray-700">“The AI-powered matching system made job searching incredibly easy for me.”</p>
                                <h3 class="font-semibold mt-4">— David L.</h3>
                            </div>
                        </div>
                    </div>
                </section>
    
        <!-- Newsletter Subscription -->
        <div class="w-full min-h-screen bg-gradient-to-b from-blue-100 to-white flex items-center justify-center p-10">
            <div class="w-full max-w-6xl bg-white shadow-xl rounded-lg p-10 flex flex-wrap md:flex-nowrap gap-10">
    
                <!-- Left Side (Contact Details) -->
                <div class="w-full md:w-1/2 space-y-6">
                    <h2 class="text-3xl font-extrabold text-gray-800">Our Contact</h2>
    
                    <div class="space-y-3 text-gray-600">
                        <p class="flex items-center">
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
    
                    <div class="w-full h-56 overflow-hidden rounded-lg shadow-md">
                        <iframe 
                            class="w-full h-full"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13609.928126770877!2d74.27810847759245!3d31.483431772521445!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391903c2002b3995%3A0x320b96105e77f88!2sRoss%20Residentia!5e0!3m2!1sen!2s!4v1738928990351!5m2!1sen!2s"
                            allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                </div>
                <!-- Right Side (Contact Form) -->
                <div class="w-full md:w-1/2 bg-white p-6 border rounded-lg shadow-md">
                    <h2 class="text-3xl font-extrabold text-gray-800 mb-6">Contact Form</h2>
    
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium">Full Name</label>
                            <input type="text" name="name" class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-300 focus:border-indigo-500 shadow-sm">
                        </div>
    
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium">E-mail</label>
                            <input type="email" name="email" class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-300 focus:border-indigo-500 shadow-sm">
                        </div>
    
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium">Message</label>
                            <textarea name="message" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-300 focus:border-indigo-500 shadow-sm"></textarea>
                        </div>
    
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-800 transition-all duration-300 shadow-md">
                            Send Message
                        </button>
                        <a href="/problem" class="text-red-500 block text-center mt-6">Report a problem</a>
                    </form>
                </div>
    
            </div>
        </div>
    </div>
</x-form>
<!-- Swiper.js CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<!-- Swiper.js Configuration -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 1, 
            spaceBetween: 20,
            loop: true, 
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            autoplay: {
                delay: 1500, 
                disableOnInteraction: false, 
            },
            breakpoints: {
                768: { slidesPerView: 3 },
            }
        });

        document.querySelector(".swiper-button-next").addEventListener("click", () => swiper.slideNext());
        document.querySelector(".swiper-button-prev").addEventListener("click", () => swiper.slidePrev());
    });
</script>
