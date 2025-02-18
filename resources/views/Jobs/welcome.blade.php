<x-form>
    <section>
        <div class="font-bold text-2xl px-10 pt-2">All jobs</div>  
        <div class="mt-4">
            <p class="text-xl text-gray-500 px-10">
                Showing {{ $jobs->count() }} jobs of {{ $jobs->total() }}
            </p>
        </div>
        <div class="flex justify-between mt-3 ml-auto">
            <div class="w-full pr-4">
                <div class="grid grid-cols-1 gap-8">
            @foreach ($jobs as $job)
                <x-jobcard :job="$job" />
            @endforeach
        </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $jobs->links() }}
    </div>
    </section>
</x-form>

