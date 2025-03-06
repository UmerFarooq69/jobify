<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(){
        $companies = Company::whereHas('user', function ($query) {
            $query->where('active', true);
        })->withCount('jobs')->get();

            $users = User::with('company')->get();

        return view ('companies.index',compact('companies','users'));
    }

    public function create()
    {
        return view('companies.create'); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:1024',
            'description' => 'nullable|string|max:1000',
        ]);
        

        $imagePath = $request->file('image') ? $request->file('image')->store('company_images', 'public') : null;

        $company = new Company([
            'name' => $request->name,
            'city' => $request->city,
            'location' => $request->location,
            'image' => $imagePath,
            'description' => $request->description,
        ]);
        
        auth()->user()->company()->save($company);
        
        return redirect()->route('companies')->with('success', 'Company created successfully!');
    }

    public function edit (Company $company){

        return view('companies.edit', compact('company'));
        
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:1024',
            'description' => 'nullable|string|max:1000',
        ]);
    
        if ($request->hasFile('image')) {

            if ($company->image) {
                \Storage::disk('public')->delete($company->image);
            }
            $imagePath = $request->file('image')->store('company_images', 'public');
            $company->image = $imagePath;
        }
    
        $company->update([
            'name' => $request->name,
            'city' => $request->city,
            'location' => $request->location,
            'description' => $request->description,
        ]);
    
        return redirect()->route('companies')->with('success', 'Company updated successfully!');
    }
    
    public function showJobs(Company $company)
    {
        $jobs = $company->jobs;
        
        return view('companies.show', compact('company', 'jobs'));
    }

}
