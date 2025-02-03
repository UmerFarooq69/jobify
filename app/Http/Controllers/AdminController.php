<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use App\Models\Job_task;
use App\Models\Company;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
          $companies = Company::all();

          return view('admin.index', compact('companies'));
    }

    public function dashboard()
    {
        $jobs = Job_task::count();
        $companies = Company::count();
        $users = User::where('role', '!=', 'admin')->count(); 
        
        return view('admin.dashboard', compact('jobs', 'companies', 'users'));
    }
    
    public function showJobs(Company $company)
    {
        $jobs = $company->jobs;
        
        return view('companies.show', compact('company', 'jobs'));
    }
    public function Jobs(Company $company)
    {
        $jobs = Job_task::all();
        
        return view('admin.jobs', compact('company', 'jobs'));
    }
    public function Applications(Request $request, Company $company = null)
    {
        $companies = Company::all();
        $jobs = Job_task::all();
    
        $applications = Application::with('job.company')
        ->when($request->company_id, function ($query) use ($request) {
            return $query->whereRelation('job', 'company_id', $request->company_id);
        })->get();
       
        return view('application.index', compact('applications', 'jobs', 'company', 'companies'));
    }
    
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('admin.index')->with('success', 'Company deleted successfully');
    }
    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.index')->with('success', 'User deleted successfully');
    }

    public function destroyJob(Job_task $job)
    {
        $job->delete();
        return redirect()->route('admin.jobs')->with('success', 'Job deleted successfully');
    }
    public function destroyApplication(Application $application)
    {
        $application->delete();
    
        return redirect()->route('applications.index')->with('success', 'Application deleted successfully');
    } 
}

