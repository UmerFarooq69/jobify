<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use App\Models\Contact;
use App\Models\Job_task;
use App\Models\Company;
use App\Models\ProblemReport;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        $companies = Company::withCount('jobs')->get();
        return Inertia::render('Admin/Companies', ['companies' => $companies]);
    }

    public function dashboard()
    {
        return Inertia::render('Admin/Dashboard', [
            'jobs'         => Job_task::count(),
            'companies'    => Company::count(),
            'applications' => Application::count(),
            'reports'      => ProblemReport::count(),
            'contact'      => Contact::count(),
            'users'        => User::where('role', '!=', 'admin')->count(),
        ]);
    }

    public function showJobs(Company $company)
    {
        $jobs = $company->jobs;
        return view('companies.show', compact('company', 'jobs'));
    }

    public function Jobs(Company $company = null)
    {
        $jobs      = Job_task::with('company')->get();
        $companies = Company::all();
        return Inertia::render('Admin/Jobs', ['jobs' => $jobs, 'companies' => $companies]);
    }

    public function Applications(Request $request, Company $company = null)
    {
        $companies = Company::all();
        $jobs = Job_task::all();

        $applications = Application::with('job.company')
            ->when($request->company_id, function ($query) use ($request) {
                return $query->whereRelation('job', 'company_id', $request->company_id);
            })->get();

        return Inertia::render('Admin/Applications', [
            'applications' => $applications,
            'companies'    => $companies,
            'jobs'         => $jobs,
        ]);
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('admin.company.companies')->with('success', 'Company deleted successfully');
    }

    public function destroyJob(Job_task $job)
    {
        $job->delete();
        return redirect()->route('admin.job.jobs')->with('success', 'Job deleted successfully');
    }

    public function destroyApplication(Application $application)
    {
        $application->delete();
        return redirect()->route('applications.index')->with('success', 'Application deleted successfully');
    }
}
