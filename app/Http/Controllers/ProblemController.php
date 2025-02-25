<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProblemReport;

class ProblemController extends Controller
{
    public function index()
    {
        $problems = ProblemReport::with('company','job')->latest()->get();
        return view('reportproblem.index', compact('problems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purpose' => 'required|string',
            'report_type' => 'required|string',
            'job_id' => 'nullable|string',
            'company_id' => 'nullable|string',
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
            'purpose' => $request->purpose,
            'report_type' => $request->report_type,
            'job_id' => $request->job_id,
            'company_id' => $request->company_id,
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
