<x-form>
    <section id="hero" class="relative overflow-hidden bg-gradient-to-r from-blue-900 to-blue-100 py-20 lg:py-36">
        
        <div class="allowed-content-width relative z-10 px-6 md:px-12">
            <div class="mx-auto flex flex-col-reverse lg:flex-row items-center justify-center gap-x-16 gap-y-8 max-w-7xl">
                <div class="w-full lg:w-2/3 text-center lg:text-left space-y-6 transition-transform duration-300 ease-in-out hover:scale-105 hover:brightness-110">
                    <!-- Animated Headline -->
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight text-white opacity-0 translate-y-8 animate-fade-slide-in delay-0">
                        Find Your Next <span id="typed-text"></span> Adventure.<br/>
                        Discover opportunities in Tech, Finance, Engineering, and more.
                    </h1>
                    
                    <p class="text-lg md:text-xl text-white opacity-80 animate-fade-slide-in delay-1">
                        Join thousands of professionals already advancing their careers with Jobify.
                    </p>
                    
                    <a href="#featured-jobs" 
                    class="inline-flex items-center gap-3 bg-gradient-to-b from-blue-900 to-blue-300 text-white font-bold rounded-full py-3 px-8 shadow-lg 
                  transform transition-transform hover:scale-105 hover:shadow-2xl animate-pulse hover:animate-none mt-8">
                    Get Started
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" 
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
        
        <div class="relative w-full lg:w-1/3 h-72 lg:h-96 flex justify-center items-center">
            <div class="absolute bg-yellow-300 rounded-full w-24 h-24 top-8 left-16 opacity-70 animate-float-slow hover:scale-110 transition-transform"></div>
            <div class="absolute bg-pink-400 rounded-full w-20 h-20 bottom-12 right-20 opacity-60 animate-float-fast hover:scale-110 transition-transform"></div>
            <div class="absolute bg-purple-600 rounded-full w-32 h-32 top-20 right-16 opacity-50 animate-float-slower hover:scale-110 transition-transform"></div>
        </div>
    </div>
</div>
</section>

<hr class="bg-blue-500 border-b border-blue-500">
<!-- About Section -->
<section id="explore" class="py-16 px-6 text-center bg-white">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-4xl font-bold text-gray-800">Why Choose Us?</h2>
        <p class="text-lg text-gray-600 mt-4">We connect talented individuals with top companies across various industries. 
            Our platform offers seamless job searching, personalized recommendations, and career growth opportunities tailored to your skills.</p>
        </div>
    </section>
    <!-- Featured Jobs Section -->
    <section id="featured-jobs" class="py-16 px-6 bg-gradient-to-b from-blue-900 to-blue-100">
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
    <hr class="bg-blue-500 border-b border-blue-500">
    
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
    <x-contact></x-contact>
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
    
    document.addEventListener("DOMContentLoaded", function() {
        const words = ["Professional", "Tech", "Finance", "Engineering", "Career"];
        const typedText = document.getElementById("typed-text");
        let wordIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let delay = 200;
        
        function type() {
            const currentWord = words[wordIndex];
            
            if (!isDeleting) {
                typedText.textContent = currentWord.slice(0, charIndex + 1);
                charIndex++;
                delay = 150 - Math.random() * 50; // natural typing speed variation
                
                if (charIndex === currentWord.length) {
                    isDeleting = true;
                    delay = 1800; // pause before deleting
                }
            } else {
                typedText.textContent = currentWord.slice(0, charIndex - 1);
                charIndex--;
                delay = 80 - Math.random() * 20;
                
                if (charIndex === 0) {
                    isDeleting = false;
                    wordIndex = (wordIndex + 1) % words.length;
                    delay = 800; // pause before typing next word
                }
            }
            
            setTimeout(type, delay);
        }
        
        type();
    });
</script>
<style>
    html {
        scroll-behavior: smooth;
    }
    @keyframes gradient-x {
        0%, 100% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
    }
    
    .animate-gradient-x {
        background-size: 200% 200%;
        animation: gradient-x 15s ease infinite;
    }
    
    @keyframes fade-slide-in {
        0% {
            opacity: 0;
            transform: translateY(2rem);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-slide-in {
        animation: fade-slide-in 1s forwards;
    }
    
    /* Staggered fade delays */
    .delay-0 { animation-delay: 0s; }
    .delay-1 { animation-delay: 0.3s; }
    .delay-2 { animation-delay: 0.6s; }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        50% {
            transform: translateY(-15px) rotate(5deg);
        }
    }
    
    .animate-float-slow {
        animation: float 6s ease-in-out infinite;
    }
    .animate-float-fast {
        animation: float 4s ease-in-out infinite;
    }
    .animate-float-slower {
        animation: float 8s ease-in-out infinite;
    }
    
    /* Fix typewriter width jumping */
    #typed-text {
    display: inline-block;
    min-width: 11ch;
    white-space: nowrap;
    position: relative;
    color: #3b82f6; /* blue-500 from Tailwind */
    text-shadow: 0 0 8px #3b82f6; /* blue glow */
    font-weight: 700;
    font-family: monospace;
}

/* Blinking cursor */
#typed-text::after {
    content: '|';
    position: absolute;
    top: 0;
    left: 100%;
    margin-left: 2px;
    animation: blink 1.2s infinite;
    opacity: 1;
    height: 100%;
    line-height: 1;
    display: inline-block;
    vertical-align: middle;
    color: #3b82f6; /* cursor color matches text */
    text-shadow: 0 0 8px #3b82f6;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0; }
}
