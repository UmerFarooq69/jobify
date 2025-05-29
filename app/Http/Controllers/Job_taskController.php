<?php

namespace App\Http\Controllers;

use App\Models\Job_task;
use App\Models\Application;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Job_taskController extends Controller
{
    public function index()
    {
        $jobs = Job_task::whereHas('company.user', function ($query) {
            $query->where('active', true);
        })
        ->with('company')
        ->paginate(10);
        
        $jobTypes = Job_task::select('job_type')->distinct()->pluck('job_type');
        $locations = Company::select('location')->distinct()->pluck('location');
        $companies = Company::select('name')->distinct()->pluck('name');
        $cities = Company::select('city')->distinct()->pluck('city');
        
        return view('jobs.index', compact('jobs', 'jobTypes', 'locations', 'companies', 'cities'));
    }
    
    public function create()
    {
        $jobTypes = [
            'Full-Time',
            'Part-Time',
            'Contract',
            'Internship',
        ];
        if (Auth::user()->role === 'admin') {
            $companies = Company::all();
        } else {
            $companies = Auth::user()->companies;
        }
        return view('jobs.create', compact('jobTypes', 'companies'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'job_title' => 'required|string|max:255',
            'job_type' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'job_salary' => 'required|string|max:255',
        ]);
        
        $company = Company::findOrFail($validated['company_id']);
        $companyImage = $company->image;
        $job = new Job_task();
        $job->company_id = $validated['company_id'];
        $job->job_title = $validated['job_title'];
        $job->job_type = $validated['job_type'];
        $job->description = $validated['description'];
        $job->job_salary = $validated['job_salary'];
        $job->image = $companyImage;
        $job->save();
        
        return redirect()->route('jobs.index')->with('success', 'Job posted successfully!');
        
    }
    
    public function edit(Job_task $job)
    {
        if (Auth::user()->role === 'admin') {
            $companies = Company::all();
        } else {
            $companies = Auth::user()->companies;
        }
        
        $jobTypes = ['Full-Time', 'Part-Time', 'Contract', 'Internship'];
        
        return view('jobs.edit', compact('job', 'companies', 'jobTypes'));
    }
    public function update(Request $request, Job_task $job)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'job_title' => 'required|string|max:255',
            'job_type' => 'required|string',
            'description' => 'required|string',
            'job_salary' => 'required|string',
        ]);
        
        $job->update($request->all());
        
        if (Auth::user()->role === 'admin') {
            
            return redirect()->route('admin.job.jobs')->with('success', 'Job updated successfully.');
        } else {
            
            return redirect()->route('User.job.jobs')->with('success', 'Job updated successfully.');    }
        }
        
        public function apply($jobId)
        {
            $job = Job_task::findOrFail($jobId);
            return view('jobs.apply', compact('job'));
        }
        
        public function submitApplication(Request $request, $jobId)
        {
            $request->validate([
                'applicant_name' => 'required|string|max:255',
                'applicant_email' => 'required|email|max:255',
                'cv' => 'required|file|mimes:pdf,docx|max:5120',
            ]);
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('cvs', 'public');
            }
            Application::create([
                'applicant_name' => $request->applicant_name,
                'applicant_email' => $request->applicant_email,
                'cv' => $cvPath,  
                'job_id' => $jobId,
            ]);
            return redirect()->route('jobs.index')->with('success', 'Application submitted successfully!');
        }
    }
    