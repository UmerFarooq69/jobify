<?php

namespace App\Http\Controllers;

use App\Models\Job_task;
use Illuminate\Http\Request;

class SalariesController extends Controller
{
 
    public function index(){
        $jobs = Job_task::with('company')->get();
        return view ('salaries.index', compact('jobs'));
    }
    public function show($id)
    {
        $job = Job_task::findOrFail($id);
        return view('jobs.apply', compact('job'));
    }
}
