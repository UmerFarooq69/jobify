<a href="{{ route('company.jobs', $company->id) }}" class="block">
    <div
        class="p-6 bg-white shadow-md rounded-lg flex flex-col text-left mx-4 hover:bg-gray-200 transition-all duration-100">
        <div class="flex items-center mb-4">
            <img src="{{ asset('storage/' . $company->image) }}" alt="Company Image"
                class="w-[70px] h-[70px] object-contain rounded-md shadow-lg mr-4">
            <h1 class="text-xl font-semibold"><span class="font-bold">{{$company->name}}</span></h1>
        </div>
        <h1 class="font-bold text-lg">Company ID: <span class="text-blue-800">{{$company->id}}</span></h1>
        <h2 class="text-xl font-semibold text-gray-700">{{ \Illuminate\Support\Str::limit($company->description, 170) }}
        </h2>
        <h3 class="text-lg mt-2"><i class="mdi mdi-city-variant-outline text-2xl mr-2"></i><span
                class="font-normal text-gray-500">{{ $company->city }}</span></h3>
        <h3 class="text-lg"><i class="mdi mdi-map-marker-outline text-2xl mr-2"></i><span
                class="font-normal text-gray-500">{{ $company->location }}</span></h3>
        <h3 class="text-lg text-blue-500 mt-2">Jobs:
            <span class="font-normal text-gray-500">{{ $company->jobs_count }}</span>
        </h3>
    </div>
</a>