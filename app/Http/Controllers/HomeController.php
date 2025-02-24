<?php

namespace App\Http\Controllers;

use App\Models\Job_task;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredJobs = Job_task::latest()->take(6)->get();
        return view('Jobify', compact('featuredJobs'));
    }
    
}
