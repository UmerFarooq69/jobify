<x-form>
    <div class="bg-gray-200 min-h-48 flex items-center justify-center pt-3">
        <img src="{{ asset('storage/img/logo.jpeg') }}" alt="Logo" class="w-[500px] mr-6 mb-4">
        <div class="max-w-3xl bg-white shadow-lg p-8 rounded-lg">
            <div class="flex items-center space-x-4">
                <h2 class="text-4xl font-semibold">Find a workplace that works for you</h2>
            </div>
            <p class="text-gray-600 mt-4 text-xl">
                Discover what an employer is really like before you make your next move. 
                Search reviews and ratings, and filter companies based on the qualities 
                that matter most to your job search.
            </p>
            <div class="mt-4 space-x-2">
                <button class="bg-black text-white px-8 py-4 mt-2 text-lg rounded-lg">Work/life balance</button>
                <button class="bg-black text-white px-8 py-4 mt-2 text-lg rounded-lg">Diversity & inclusion</button>
                <button class="bg-black text-white px-8 py-4 mt-2 text-lg rounded-lg">Compensation and benefits</button>
            </div>            
        </div>
    </div>
    
    <div class="flex justify-between mt-3 ml-auto">
        <div class="w-full pr-4">
            <div class="grid grid-cols-1 gap-8">
                @foreach ($companies as $company)
                    <x-companycard :company="$company"/>
                @endforeach
            </div>
        </div>
    </div>
</x-form>
