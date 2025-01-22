<div class="p-6 bg-white shadow-md rounded-lg flex flex-col text-left mx-4">
    <div class="flex justify-center mb-4">
        <img src="{{ asset('storage/' . $company->image) }}" alt="Company Image" class="w-full md:w-2/3 lg:w-1/2 mx-auto rounded-md shadow-lg">
    </div>
    <h1 class="text-xl font-semibold mt-6">
        Company: <span class="font-normal text-gray-500">{{$company->name}}</span>
    </h1>
    <h3 class="text-lg mt-2">City: <span class="font-normal text-gray-500">{{ $company->city }}</span></h3>
    <h3 class="text-lg">Location: <span class="font-normal text-gray-500">{{ $company->location }}</span></h3>
    <div class="text-center mt-6">
        <a href="{{ route('company.jobs', $company->id) }}" class="bg-gradient-to-r from-green-400 to-green-600 text-white py-3 px-6 rounded-md shadow-lg hover:from-green-500 hover:to-green-700 transition-all duration-300 text-center flex items-center justify-center">
            Jobs in this Company
        </a>
    </div>
</div>
