<x-form>
    <section>
        @if ($jobs->isEmpty())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-md ml-4 mt-4">
                <h4 class="font-bold text-lg">Oops!</h4>
                <p class="text-md">There are no job openings in this company right now. Please check back later!</p>
            </div>
        @else
        <h3 class="text-3xl mb-6 font-extrabold text-center text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-indigo-500 to-pink-600">
            Jobs in this Company
        </h3>
        <div class="grid lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1 gap-8">
            @foreach ($jobs as $job)
                <x-jobcard :job="$job" />
            @endforeach
        </div>
        @endif
    </section>   
</x-form>
