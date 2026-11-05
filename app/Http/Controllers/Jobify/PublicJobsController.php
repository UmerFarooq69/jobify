<?php
namespace App\Http\Controllers\Jobify;

use App\Http\Controllers\Controller;
use App\Models\JobRequisition;
use App\Models\JobTaxonomy;
use Inertia\Inertia;

class PublicJobsController extends Controller
{
    public function index()
    {
        $filters = request()->only(['q','type','location','sort','datePosted','experience','salary','jobShift','selected']);

        $jobs = JobRequisition::with(['organization','jobType','jobFunction'])
            ->active()->approved()
            ->when($filters['q'] ?? null, fn($q,$s) => $q->where(fn($q) => $q->where('title','like',"%{$s}%")->orWhere('description','like',"%{$s}%")->orWhere('location','like',"%{$s}%")))
            ->when($filters['type'] ?? null, fn($q,$v) => $q->where('employment_type',$v))
            ->when($filters['location'] ?? null, fn($q,$v) => $q->where('location','like',"%{$v}%"))
            ->when($filters['sort'] ?? null, function($q,$v) {
                match($v) {
                    'newest'      => $q->latest(),
                    'salary_high' => $q->orderByDesc('salary_max'),
                    'salary_low'  => $q->orderBy('salary_min'),
                    default       => $q->latest(),
                };
            })
            ->when($filters['datePosted'] ?? null, function($q,$v) {
                match($v) {
                    '24h'     => $q->where('created_at','>=', now()->subDay()),
                    'week'    => $q->where('created_at','>=', now()->subWeek()),
                    'month'   => $q->where('created_at','>=', now()->subMonth()),
                    '3months' => $q->where('created_at','>=', now()->subMonths(3)),
                    default   => null,
                };
            })
            ->latest()->paginate(30)->withQueryString();

        $selectedJob = null;
        if ($filters['selected'] ?? null) {
            $selectedJob = JobRequisition::with(['organization','jobType','jobFunction','department'])
                ->where('uuid', $filters['selected'])->active()->approved()->first();
        } elseif ($jobs->count() > 0) {
            $selectedJob = $jobs->first();
        }

        $appliedIds = [];
        if (auth()->check()) {
            $profile = auth()->user()->candidateProfile;
            if ($profile) {
                $appliedIds = $profile->applications()
                    ->pluck('job_requisition_id')
                    ->toArray();
            }
        }

        return Inertia::render('Jobify/Jobs', [
            'jobs'        => $jobs,
            'filters'     => $filters,
            'selectedJob' => $selectedJob,
            'appliedIds'  => $appliedIds,
        ]);
    }

    public function show(string $uuid)
    {
        $job = JobRequisition::with(['organization','jobType','jobFunction','department','pipelineStages'])
            ->where('uuid',$uuid)->where('status','active')->where('moderation_status','approved')->firstOrFail();

        $related = JobRequisition::with('organization')
            ->active()->approved()
            ->where('id','!=',$job->id)
            ->where(fn($q) => $q->where('job_function_id',$job->job_function_id)->orWhere('location',$job->location))
            ->take(4)->get();

        $hasApplied = false;
        $isSaved    = false;
        if (auth()->check()) {
            $profile    = auth()->user()->candidateProfile;
            $hasApplied = $profile && $profile->applications()->where('job_requisition_id',$job->id)->exists();
            $isSaved    = $profile && $profile->savedJobs()->where('job_requisition_id',$job->id)->exists();
        }

        return Inertia::render('Jobify/JobDetail', [
            'job'        => $job,
            'related'    => $related,
            'hasApplied' => $hasApplied,
            'isSaved'    => $isSaved,
        ]);
    }

    public function apply(string $uuid)
    {
        $job     = JobRequisition::where('uuid',$uuid)->active()->approved()->firstOrFail();
        $user    = auth()->user();
        $profile = $user->candidateProfile;

        if (!$profile || !$profile->onboarding_completed) {
            return redirect()->route('candidate.onboarding')->with('error','Complete your profile before applying.');
        }

        if ($profile->applications()->where('job_requisition_id',$job->id)->exists()) {
            return back()->with('error','You have already applied for this job.');
        }

        $application = $profile->applications()->create([
            'uuid'             => (string)\Illuminate\Support\Str::uuid(),
            'job_requisition_id' => $job->id,
            'resume_id'        => $profile->primaryResume?->id,
            'status'           => 'applied',
            'source'           => 'direct',
            'applied_at'       => now(),
        ]);

        // Assign to first pipeline stage if any
        $firstStage = $job->pipelineStages()->first();
        if ($firstStage) {
            $application->update(['pipeline_stage_id' => $firstStage->id, 'stage_entered_at' => now()]);
        }

        return back()->with('success','Application submitted successfully!');
    }
}
