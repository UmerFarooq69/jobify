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
    
    public function Jobs()
    {
        $jobs = Job_task::whereIn('company_id', Auth::user()->companies->pluck('id'))->get();
    
        return view('User.job.jobs', compact('jobs'));
    }
    public function company()
    {
        $companies = Auth::user()->companies ? Auth::user()->companies : collect();
        return view('User.company.companies', compact('companies'));
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

        return view('User.applications.application', compact('applications', 'jobs', 'companies'));
    }
    public function destroyApplication(Application $application){
        
        $application->delete();

        return redirect()->route('User.applications.application')->with('success', 'Application deleted successfully');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('User.company.companies')->with('success', 'Company deleted successfully');
    }
    public function destroyJob(Job_task $job)
    {
        $job->delete();
        return redirect()->route('User.job.jobs')->with('success', 'Job deleted successfully');
    }
}
