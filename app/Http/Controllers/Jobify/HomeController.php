<?php
namespace App\Http\Controllers\Jobify;

use App\Http\Controllers\Controller;
use App\Models\JobRequisition;
use App\Models\Organization;
use App\Models\JobTaxonomy;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $featuredJobs = JobRequisition::with(['organization','jobType'])
            ->active()->approved()
            ->latest()->take(8)->get()
            ->map(fn($j) => [
                'id'          => $j->id,
                'uuid'        => $j->uuid,
                'title'       => $j->title,
                'location'    => $j->location,
                'employment_type' => $j->employment_type,
                'salary_min'  => $j->salary_min,
                'salary_max'  => $j->salary_max,
                'currency'    => $j->currency,
                'urgent_hire' => $j->urgent_hire,
                'created_at'  => $j->created_at,
                'organization'=> ['name' => $j->organization->name, 'logo' => $j->organization->logo, 'verified' => $j->organization->verified],
                'job_type'    => $j->jobType?->name,
            ]);

        $topEmployers = Organization::where('status','active')
            ->where('moderation_status','approved')
            ->where('is_top_employer',true)
            ->withCount(['jobRequisitions as active_jobs' => fn($q) => $q->active()->approved()])
            ->take(8)->get()
            ->map(fn($o) => ['id'=>$o->id,'name'=>$o->name,'logo'=>$o->logo,'industry'=>$o->industry,'verified'=>$o->verified,'active_jobs'=>$o->active_jobs]);

        $industries = JobTaxonomy::active()->type('job_functionality')->orderBy('sort_order')->get(['id','name','icon']);

        return Inertia::render('Jobify/Home', [
            'featuredJobs' => $featuredJobs,
            'topEmployers' => $topEmployers,
            'industries'   => $industries,
            'stats'        => [
                'jobs'       => JobRequisition::active()->approved()->count(),
                'companies'  => Organization::where('status','active')->where('moderation_status','approved')->count(),
                'candidates' => \App\Models\CandidateProfile::count(),
            ],
        ]);
    }
}
