<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job_task;
use App\Models\Company;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query    = $request->input('query');
        $jobType  = $request->input('job_type');
        $location = $request->input('location');
        $company  = $request->input('company');
        $city     = $request->input('city');

        $jobs = Job_task::with('company')
            ->when($query,    fn ($q) => $q->where('job_title', 'LIKE', "%{$query}%"))
            ->when($company,  fn ($q) => $q->whereHas('company', fn ($q) => $q->where('name', 'LIKE', "%{$company}%")))
            ->when($location, fn ($q) => $q->whereHas('company', fn ($q) => $q->where('location', 'LIKE', "%{$location}%")))
            ->when($city,     fn ($q) => $q->whereHas('company', fn ($q) => $q->where('city', 'LIKE', "%{$city}%")))
            ->when($jobType,  fn ($q) => $q->where('job_type', $jobType))
            ->paginate(10);

        $jobTypes  = Job_task::select('job_type')->distinct()->pluck('job_type');
        $locations = Company::select('location')->distinct()->pluck('location');
        $companies = Company::select('name')->distinct()->pluck('name');
        $cities    = Company::select('city')->distinct()->pluck('city');

        return view('jobs.index', compact('jobs', 'jobTypes', 'locations', 'companies', 'cities'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->input('query');
        $suggestions = Job_task::where('job_title', 'LIKE', "%{$query}%")->limit(5)->pluck('job_title');
        return response()->json($suggestions);
    }

    public function adminjobs(Request $request)
    {
        $query = Job_task::with('company');

        if ($request->has('job_uuid') && !empty($request->job_uuid)) {
            $query->where('job_uuid', $request->job_uuid);
        }

        $jobs = $query->get();
        return Inertia::render('Admin/Jobs', ['jobs' => $jobs]);
    }
}
