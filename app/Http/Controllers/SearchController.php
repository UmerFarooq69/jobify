<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job_task;
use App\Models\Company;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $jobType = $request->input('job_type');
        $location = $request->input('location');
        $company = $request->input('company');
        $city = $request->input('city');

        $jobs = Job_task::with('company')
            ->when($query, function ($q) use ($query) {
                $q->where('job_title', 'LIKE', "%{$query}%");
            })
            ->when($company, function ($q) use ($company) {
                $q->whereHas('company', function ($q) use ($company) {
                    $q->where('name', 'LIKE', "%{$company}%");
                });
            })
            ->when($location, function ($q) use ($location) {
                $q->whereHas('company', function ($q) use ($location) {
                    $q->where('location', 'LIKE', "%{$location}%");
                });
            })
            ->when($city, function ($q) use ($city) {
                $q->whereHas('company', function ($q) use ($city) {
                    $q->where('city', 'LIKE', "%{$city}%");
                });
            })
            ->when($jobType, function ($q) use ($jobType) {
                $q->where('job_type', $jobType);
            })
            ->paginate(10);

        $jobTypes = Job_task::select('job_type')->distinct()->pluck('job_type');
        $locations = Company::select('location')->distinct()->pluck('location');
        $companies = Company::select('name')->distinct()->pluck('name');
        $cities = Company::select('city')->distinct()->pluck('city'); // New City filter

        return view('jobs.welcome', compact('jobs', 'jobTypes', 'locations', 'companies', 'cities'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('query');
        $suggestions = Job_task::where('job_title', 'LIKE', "%{$query}%")
            ->limit(5)
            ->pluck('job_title');

        return response()->json($suggestions);
    }
}
