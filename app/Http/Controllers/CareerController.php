<?php

namespace App\Http\Controllers;

use App\Models\Job_task;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index(){
        $jobs = Job_task::with('company')->get();
        return view ('careers.index', compact('jobs'));
    }
}
