<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProblemReport;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class ProblemController extends Controller
{

    public function index()
    {
        $problems = ProblemReport::latest()->get();

        return view('reportproblem.index', compact('problems'));
    }

    public function store(Request $request)
    {
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'problem' => 'required|string|min:3',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('problem_reports', 'public');
        }
    
        ProblemReport::create([
            'name' => $request->name,
            'email' => $request->email,
            'problem' => $request->problem,
            'image' => $imagePath,
        ]);
    
        return back()->with('success', 'Your problem has been reported successfully.');
    }
    

        public function destroy($id)
        {
            $problem = ProblemReport::find($id);
        
            if (!$problem) {
                return redirect()->back()->with('error', 'Problem not found');
            }

            if ($problem->image) {
                $imagePath = storage_path('app/public/' . $problem->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        
            $problem->delete();
        
            return redirect()->back()->with('success', 'Problem deleted successfully');
        }
    
}
