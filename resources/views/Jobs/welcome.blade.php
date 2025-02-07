<x-form>
    <section>
        <div class="w-full bg-gradient-to-r from-teal-400 via-indigo-500 to-pink-600 py-6">
            <h3 class="text-3xl font-extrabold text-center text-white">
                Jobs Right Now
            </h3>
        </div>        
        <div class="grid lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1 gap-8 mt-3">
            @foreach ($jobs as $job)
                <x-jobcard :job="$job" />
            @endforeach
        </div>
    </section>
</x-form>

