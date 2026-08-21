<x-form>
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 overflow-hidden">
    <!-- Subtle dot grid overlay -->
    <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 32px 32px;"></div>
    <!-- Colored glow accents -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-indigo-600/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-24 lg:py-36">
        <div class="max-w-3xl">
            <span class="inline-flex items-center gap-1.5 bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-semibold px-3 py-1.5 rounded-full mb-6 uppercase tracking-wide">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                Now hiring across 50+ industries
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-[1.15] text-white">
                Find Your Next<br>
                <span id="typed-text" class="text-blue-400"></span>
                <span class="text-white"> Career</span>
            </h1>
            <p class="text-base text-slate-300 mt-6 max-w-lg leading-relaxed">
                Join thousands of professionals advancing their careers with Jobify. Connect with top companies in Tech, Finance, Engineering, and more.
            </p>
            <div class="flex flex-wrap gap-3 mt-8">
                <a href="#featured-jobs" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-6 rounded-xl transition-colors shadow-lg shadow-blue-600/30">
                    Browse Jobs <i class="fas fa-arrow-right text-sm"></i>
                </a>
                <a href="{{ route('companies') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/15 text-white font-semibold py-3 px-6 rounded-xl transition-colors border border-white/15 backdrop-blur-sm">
                    Explore Companies
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Strip -->
<section class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 py-8 grid grid-cols-3 divide-x divide-gray-100">
        <div class="text-center px-4">
            <div class="text-3xl font-extrabold text-blue-600">500K+</div>
            <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mt-1">Jobs Listed</div>
        </div>
        <div class="text-center px-4">
            <div class="text-3xl font-extrabold text-blue-600">200K+</div>
            <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mt-1">Successful Hires</div>
        </div>
        <div class="text-center px-4">
            <div class="text-3xl font-extrabold text-blue-600">50+</div>
            <div class="text-xs font-medium text-gray-400 uppercase tracking-wide mt-1">Industries</div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="inline-block text-xs font-semibold text-blue-600 uppercase tracking-widest mb-3">Why Jobify</span>
            <h2 class="text-3xl font-bold text-gray-900">Built for Modern Careers</h2>
            <p class="text-gray-500 mt-3 max-w-xl mx-auto text-sm leading-relaxed">We connect talented individuals with top companies using cutting-edge tools and a seamless experience.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white p-7 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
                <div class="w-11 h-11 bg-blue-600 rounded-xl flex items-center justify-center mb-5 group-hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search text-white text-base"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">Smart Job Search</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Filter by location, salary, type, and industry. Find exactly what you're looking for in seconds.</p>
            </div>
            <div class="bg-white p-7 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
                <div class="w-11 h-11 bg-violet-600 rounded-xl flex items-center justify-center mb-5 group-hover:bg-violet-700 transition-colors">
                    <i class="fas fa-robot text-white text-base"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">AI-Powered Matching</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Get job recommendations aligned perfectly with your experience and career goals automatically.</p>
            </div>
            <div class="bg-white p-7 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
                <div class="w-11 h-11 bg-emerald-600 rounded-xl flex items-center justify-center mb-5 group-hover:bg-emerald-700 transition-colors">
                    <i class="fas fa-chart-line text-white text-base"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">Career Development</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Access mentorship, salary insights, and resources to grow your career at every stage.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Jobs -->
<section id="featured-jobs" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between mb-10">
            <div>
                <span class="inline-block text-xs font-semibold text-blue-600 uppercase tracking-widest mb-2">Opportunities</span>
                <h2 class="text-3xl font-bold text-gray-900">Featured Jobs</h2>
                <p class="text-gray-500 mt-1 text-sm">Hand-picked opportunities from top employers</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="hidden sm:inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-700 text-sm font-semibold transition-colors">
                View all jobs <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper pb-10">
                @foreach($featuredJobs as $job)
                <div class="swiper-slide">
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow p-5 h-full flex flex-col">
                        @if($job->image)
                            <img src="{{ asset('storage/' . $job->image) }}" alt="{{ $job->job_title }}" class="w-full h-40 object-cover rounded-xl mb-4">
                        @else
                            <div class="w-full h-40 bg-blue-50 rounded-xl mb-4 flex items-center justify-center border border-blue-100">
                                <i class="fas fa-briefcase text-blue-300 text-3xl"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-gray-900 mb-0.5">{{ $job->job_title }}</h3>
                            <p class="text-xs text-gray-500">{{ $job->company->name }}</p>
                            <div class="flex items-center gap-1 mt-2">
                                <i class="fas fa-map-marker-alt text-gray-300 text-xs"></i>
                                <span class="text-xs text-gray-400">{{ $job->company->location }}</span>
                            </div>
                        </div>
                        <a href="{{ route('jobs.show', $job->id) }}" class="mt-4 inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-700 text-sm font-semibold">
                            View Job <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-button-prev !text-blue-600 !w-9 !h-9 after:!text-xs bg-white border border-gray-200 rounded-full shadow-sm !top-[40%]"></div>
            <div class="swiper-button-next !text-blue-600 !w-9 !h-9 after:!text-xs bg-white border border-gray-200 rounded-full shadow-sm !top-[40%]"></div>
            <div class="swiper-pagination [&_.swiper-pagination-bullet-active]:!bg-blue-600"></div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="inline-block text-xs font-semibold text-blue-600 uppercase tracking-widest mb-3">Reviews</span>
            <h2 class="text-3xl font-bold text-gray-900">What Our Users Say</h2>
            <p class="text-gray-500 mt-2 text-sm">Real stories from professionals who found their dream jobs</p>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white p-7 rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-0.5 text-amber-400 mb-5">
                    <i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i>
                </div>
                <p class="text-gray-700 text-sm leading-relaxed">"This platform helped me land my dream job in just two weeks. The AI matching was incredibly accurate and saved me so much time."</p>
                <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                    <img src="{{ asset('storage/img/female-avatar.jpg') }}" alt="Sarah M." class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100">
                    <div>
                        <div class="text-sm font-bold text-gray-900">Sarah M.</div>
                        <div class="text-xs text-gray-400">Software Engineer</div>
                    </div>
                </div>
            </div>
            <div class="bg-white p-7 rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-0.5 text-amber-400 mb-5">
                    <i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i><i class="fas fa-star text-xs"></i>
                </div>
                <p class="text-gray-700 text-sm leading-relaxed">"The AI-powered matching system made job searching incredibly easy. I received relevant offers within days of signing up."</p>
                <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                    <img src="{{ asset('storage/img/male-avatar.jpg') }}" alt="David L." class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100">
                    <div>
                        <div class="text-sm font-bold text-gray-900">David L.</div>
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
            breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
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
                if (charIndex === 0) { isDeleting = false; wordIndex = (wordIndex + 1) % words.length; delay = 500; }
            }
            setTimeout(type, delay);
        }
        type();
    });
</script>
<style>
    html { scroll-behavior: smooth; }
    #typed-text {
        position: relative;
        font-weight: 800;
    }
    #typed-text::after {
        content: '|';
        position: absolute;
        right: -6px;
        color: #93c5fd;
        animation: blink 1s infinite;
        font-weight: 300;
    }
    @keyframes blink { 0%, 49% { opacity: 1; } 50%, 100% { opacity: 0; } }
</style>
