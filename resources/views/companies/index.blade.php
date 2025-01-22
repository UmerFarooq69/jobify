<x-form>
    <h3 class="text-3xl mb-6 font-extrabold text-center text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-indigo-500 to-pink-600">
        Companies
    </h3>    
    <div class="grid grid-cols-4 gap-8">
        @foreach ($companies as $company)
            <x-companycard :company="$company"/>
        @endforeach
    </div>
</x-form>
