<x-form>
    <section>
        <h3 class="text-3xl mb-6 font-extrabold text-center text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-indigo-500 to-pink-600">
            Jobs Right Now
        </h3>
        <div class="grid lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1 gap-8">
            @foreach ($jobs as $job)
                <x-jobcard :job="$job" />
            @endforeach
        </div>
    </section>
</x-form>

