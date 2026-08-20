<x-form>
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 py-24 lg:py-32 overflow-hidden">
    <!-- Subtle background pattern -->
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 25% 50%, #3b82f6 0%, transparent 50%), radial-gradient(circle at 75% 20%, #6366f1 0%, transparent 40%);"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="inline-flex items-center gap-2 bg-blue-600/20 text-blue-400 text-sm font-medium px-3 py-1 rounded-full mb-6 border border-blue-500/30">
                <i class="fas fa-bolt text-xs"></i> Now hiring across 50+ industries
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight text-white animate-fade-slide-in">
                Find Your Next <span id="typed-text"></span><br class="hidden lg:block"/> Career Opportunity
            </h1>
            <p class="text-lg text-slate-300 mt-6 max-w-xl animate-fade-slide-in" style="animation-delay:0.2s">
                Join thousands of professionals advancing their careers with Jobify. Connect with top companies in Tech, Finance, Engineering, and more.
            </p>
            <div class="flex flex-wrap gap-4 mt-8 animate-fade-slide-in" style="animation-delay:0.4s">
                <a href="#featured-jobs" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-lg">
                    Browse Jobs <i class="fas fa-arrow-right text-sm"></i>
                </a>
                <a href="{{ route('companies') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold py-3 px-6 rounded-lg transition-colors border border-white/20">
                    Explore Companies
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Strip -->
<section class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-6 py-8 grid grid-cols-3 gap-6 text-center">
        <div>
            <div class="text-3xl font-bold text-blue-600">500K+</div>
            <div class="text-sm text-gray-500 mt-1">Jobs Listed</div>
        </div>
        <div>
            <div class="text-3xl font-bold text-blue-600">200K+</div>
            <div class="text-sm text-gray-500 mt-1">Successful Hires</div>
        </div>
        <div>
            <div class="text-3xl font-bold text-blue-600">50+</div>
            <div class="text-sm text-gray-500 mt-1">Industries</div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Why Choose Jobify?</h2>
            <p class="text-gray-500 mt-3 max-w-2xl mx-auto">We connect talented individuals with top companies across various industries with a seamless experience.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-search text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Advanced Job Search</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Filter and find the perfect job based on your preferences, location, salary, and skills.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-robot text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">AI-Powered Matching</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Get job recommendations that align perfectly with your experience and career goals.</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Career Development</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Access courses, mentorship, and resources to grow your career at every stage.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Jobs -->
<section id="featured-jobs" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Featured Jobs</h2>
                <p class="text-gray-500 mt-1">Hand-picked opportunities from top employers</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1">
                View all <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper pb-8">
                @foreach($featuredJobs as $job)
                <div class="swiper-slide">
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow p-5 h-full flex flex-col">
                        @if($job->image)
                            <img src="{{ asset('storage/' . $job->image) }}" alt="{{ $job->job_title }}" class="w-full h-40 object-cover rounded-lg mb-4">
                        @else
                            <div class="w-full h-40 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-lg mb-4 flex items-center justify-center">
                                <i class="fas fa-briefcase text-blue-300 text-3xl"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-900 mb-1">{{ $job->job_title }}</h3>
                            <p class="text-sm text-gray-500">{{ $job->company->name }}</p>
                            <div class="flex items-center gap-1 mt-2">
                                <i class="fas fa-map-marker-alt text-gray-400 text-xs"></i>
                                <span class="text-xs text-gray-400">{{ $job->company->location }}</span>
                            </div>
                        </div>
                        <a href="{{ route('jobs.show', $job->id) }}" class="mt-4 inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm font-medium">
                            View Job <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-button-prev !text-blue-600 !w-9 !h-9 after:!text-sm bg-white border border-gray-200 rounded-full shadow-sm"></div>
            <div class="swiper-button-next !text-blue-600 !w-9 !h-9 after:!text-sm bg-white border border-gray-200 rounded-full shadow-sm"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- Career Insights -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Latest Career Insights</h2>
            <p class="text-gray-500 mt-2">Stay ahead with expert advice and industry trends</p>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Interview Tips</span>
                <h3 class="text-lg font-semibold text-gray-900 mt-3 mb-2">How to Ace Your Next Interview</h3>
                <p class="text-gray-500 text-sm">Top strategies to impress recruiters and land your dream job with confidence.</p>
                <a href="#" class="text-blue-600 text-sm font-medium mt-4 inline-flex items-center gap-1 hover:text-blue-700">Read More <i class="fas fa-arrow-right text-xs"></i></a>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-1 rounded-full">Skills</span>
                <h3 class="text-lg font-semibold text-gray-900 mt-3 mb-2">Top In-Demand Skills in 2025</h3>
                <p class="text-gray-500 text-sm">Stay ahead of the curve with these essential career skills employers are seeking.</p>
                <a href="#" class="text-blue-600 text-sm font-medium mt-4 inline-flex items-center gap-1 hover:text-blue-700">Read More <i class="fas fa-arrow-right text-xs"></i></a>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">What Our Users Say</h2>
            <p class="text-gray-500 mt-2">Real stories from professionals who found their dream jobs</p>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                <div class="flex items-center gap-1 text-amber-400 mb-4">
                    <i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i>
                </div>
                <p class="text-gray-700 text-sm leading-relaxed italic">"This platform helped me land my dream job in just two weeks. The AI matching was incredibly accurate and saved me so much time."</p>
                <div class="flex items-center gap-3 mt-4">
                    <img src="{{ asset('storage/img/female-avatar.jpg') }}" alt="Sarah M." class="w-10 h-10 rounded-full object-cover">
                    <div>
                        <div class="text-sm font-semibold text-gray-900">Sarah M.</div>
                        <div class="text-xs text-gray-400">Software Engineer</div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                <div class="flex items-center gap-1 text-amber-400 mb-4">
                    <i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i>
                </div>
                <p class="text-gray-700 text-sm leading-relaxed italic">"The AI-powered matching system made job searching incredibly easy. I received relevant offers within days of signing up."</p>
                <div class="flex items-center gap-3 mt-4">
                    <img src="{{ asset('storage/img/male-avatar.jpg') }}" alt="David L." class="w-10 h-10 rounded-full object-cover">
                    <div>
                        <div class="text-sm font-semibold text-gray-900">David L.</div>
                        <div class="text-xs text-gray-400">Product Manager</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<x-contact></x-contact>
</x-form>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
            pagination: { el: ".swiper-pagination", clickable: true },
            autoplay: { delay: 3500, disableOnInteraction: false },
            breakpoints: { 768: { slidesPerView: 3 } }
        });

        const words = ["Professional", "Tech", "Finance", "Engineering", "Dream"];
        const typedText = document.getElementById("typed-text");
        let wordIndex = 0, charIndex = 0, isDeleting = false, delay = 150;
        function type() {
            const currentWord = words[wordIndex];
            if (!isDeleting) {
                typedText.textContent = currentWord.slice(0, charIndex + 1);
                charIndex++;
                delay = 130 - Math.random() * 40;
                if (charIndex === currentWord.length) { isDeleting = true; delay = 1800; }
            } else {
                typedText.textContent = currentWord.slice(0, charIndex - 1);
                charIndex--;
                delay = 70;
                if (charIndex === 0) { isDeleting = false; wordIndex = (wordIndex + 1) % words.length; delay = 600; }
            }
            setTimeout(type, delay);
        }
        type();
    });
</script>
<style>
    html { scroll-behavior: smooth; }
    @keyframes fade-slide-in {
        from { opacity: 0; transform: translateY(1.5rem); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-slide-in { animation: fade-slide-in 0.7s ease forwards; }
    #typed-text {
        display: inline-block;
        min-width: 10ch;
        color: #60a5fa;
        font-weight: 800;
        position: relative;
    }
    #typed-text::after {
        content: '|';
        position: absolute;
        left: 100%;
        margin-left: 2px;
        color: #60a5fa;
        animation: blink 1.1s infinite;
    }
    @keyframes blink { 0%, 50% { opacity: 1; } 51%, 100% { opacity: 0; } }
</style>
