<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use App\Models\Job_task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $companies = Auth::user()->companies;
    
        $jobCounts = $companies->map(function ($company) {
            return $company->job_tasks()->count();
        });
    
        $totalJobs = $jobCounts->sum();
        $totalCompanies = $companies->count();
    
        return view('Users.dashboard', compact('totalJobs','totalCompanies', 'companies'));
    }
    
    public function Jobs(Company $company)
    {
        $jobs = $company->jobs;
        return view('Users.jobs', compact('company', 'jobs'));
    }
    public function company()
    {
        $companies = Auth::user()->companies ? Auth::user()->companies : collect();
        return view('Users.company', compact('companies'));
    }
    public function Applications(Request $request)
    {
        $user = auth()->user();

        $companies = $user->companies;

        $jobs = Job_task::whereIn('company_id', $companies->pluck('id'))->get();
    
        $applications = Application::whereIn('job_id', $jobs->pluck('id'))
            ->when($request->company_id, fn($query) => $query->whereHas('job', function($query) use ($request) {
                $query->where('company_id', $request->company_id);
            }))
            ->get();

        return view('Users.application', compact('applications', 'jobs', 'companies'));
    }
    

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('Users.company')->with('success', 'Company deleted successfully');
    }
}
