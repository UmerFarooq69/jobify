<x-form>
    <div class="w-full bg-gradient-to-r from-teal-400 via-indigo-500 to-pink-600 py-6">
        <h3 class="text-3xl font-extrabold text-center text-white">
            Companies
        </h3>
    </div>       
    <div class="grid grid-cols-4 gap-8 mt-3">
        @foreach ($companies as $company)
            <x-companycard :company="$company"/>
        @endforeach
    </div>
</x-form>
