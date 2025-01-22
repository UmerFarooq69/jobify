<?php

namespace App\Http\Controllers;

use App\Models\Job_task;
use App\Models\Salaries;
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
        return view('salaries.show', compact('job'));
    }
    

}
