<?php
namespace App\Http\Controllers\Jobify;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\JobRequisition;
use App\Models\CandidateApplication;
use App\Models\Department;
use App\Models\PipelineStage;
use App\Models\PipelineAutomation;
use App\Mail\CandidateStageChangedMail;
use App\Mail\TeamStageNotificationMail;
use App\Services\JobAiService;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class CompanyController extends Controller
{
    // Company Setup
    public function setup() {
        return Inertia::render('Jobify/Company/Setup');
    }

    public function store(Request $request) {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'industry'              => 'required',
            'password'              => 'required|string|min:8|confirmed',
        ]);
        $slug = Str::slug($request->name) . '-' . Str::random(4);
        $org  = Organization::create([
            'name'     => $request->name,
            'slug'     => $slug,
            'industry' => $request->industry,
            'city'     => $request->city,
            'country'  => $request->country ?? 'QA',
            'website'  => $request->website,
            'size'     => $request->size,
        ]);
        OrganizationUser::create(['user_id' => auth()->id(), 'organization_id' => $org->id, 'role' => 'admin']);
        auth()->user()->update([
            'role_type' => 'company_admin',
            'password'  => bcrypt($request->password),
        ]);
        return redirect()->route('employer.dashboard')->with('success','Company created!');
    }

    // Company Dashboard
    public function dashboard() {
        $org    = $this->getOrg();
        $jobIds = $org->jobRequisitions()->pluck('id');

        $activeJobsCount   = $org->jobRequisitions()->where('status','active')->count();
        $applicationsCount = CandidateApplication::whereIn('job_requisition_id', $jobIds)->count();
        $needsReviewCount  = CandidateApplication::whereIn('job_requisition_id', $jobIds)->where('status','applied')->count();

        // Applicants by job (top 6 active jobs)
        $applicantsByJob = $org->jobRequisitions()
            ->where('status','active')
            ->withCount('applications')
            ->latest()->take(6)->get()
            ->map(fn($j) => ['title' => $j->title, 'count' => $j->applications_count]);

        // Stage distribution across all active jobs
        $activeJobIds = $org->jobRequisitions()->where('status','active')->pluck('id');
        $stageRows = PipelineStage::whereIn('job_requisition_id', $activeJobIds)
            ->withCount(['applications' => fn($q) => $q->whereIn('job_requisition_id', $activeJobIds)])
            ->orderBy('order')->get();
        $totalInStages = $stageRows->sum('applications_count');
        $stageDistribution = $stageRows->map(fn($s) => [
            'name'  => $s->name,
            'count' => $s->applications_count,
            'pct'   => $totalInStages > 0 ? round(($s->applications_count / $totalInStages) * 100) : 0,
        ])->filter(fn($s) => $s['count'] > 0)->values();

        // Pipeline totals per stage name (aggregate)
        $pipelineStages = $stageDistribution;

        // Recent jobs with applicant count
        $recentJobs = $org->jobRequisitions()
            ->withCount(['applications','applications as needs_review_count' => fn($q) => $q->where('status','applied')])
            ->latest()->take(6)->get();

        // Recent applications
        $recentApplications = CandidateApplication::whereIn('job_requisition_id', $jobIds)
            ->with(['candidate.user','job'])
            ->latest()->take(6)->get()
            ->map(fn($a) => [
                'id'             => $a->id,
                'candidate_name' => trim(($a->candidate?->user?->first_name ?? $a->candidate?->user?->name ?? '') . ' ' . ($a->candidate?->user?->last_name ?? '')),
                'job_title'      => $a->job?->title,
                'applied_at'     => $a->created_at?->diffForHumans(),
            ]);

        return Inertia::render('Jobify/Company/Dashboard', compact(
            'org','activeJobsCount','applicationsCount','needsReviewCount',
            'applicantsByJob','stageDistribution','pipelineStages',
            'recentJobs','recentApplications'
        ));
    }

    // Jobs
    public function jobIndex() {
        $org  = $this->getOrg();
        $jobs = $org->jobRequisitions()->withCount('applications')->latest()->paginate(20);
        return Inertia::render('Jobify/Company/Jobs/Index', ['org' => $org, 'jobs' => $jobs]);
    }

    public function jobCreate() {
        $org         = $this->getOrg();
        $departments = $org->departments()->get();
        $jobTypes    = \App\Models\JobTaxonomy::active()->type('job_type')->get();
        $functions   = \App\Models\JobTaxonomy::active()->type('job_functionality')->get();
        return Inertia::render('Jobify/Company/Jobs/Create', compact('org','departments','jobTypes','functions'));
    }

    public function jobAiDraft(Request $request, JobAiService $ai) {
        $org    = $this->getOrg();
        $result = $ai->draftJob($request->prompt, $org->name);
        \Log::info('AI draft result: ' . json_encode($result));
        return response()->json($result ?: ['error' => 'AI returned no data']);
    }

    public function jobStore(Request $request) {
        $org = $this->getOrg();
        $job = $org->jobRequisitions()->create(array_merge(
            $request->only(['title','summary','description','requirements','skills','screening_questions','salary_min','salary_max','currency','location','employment_type','visa_sponsorship','local_hire','urgent_hire','is_confidential','department_id','job_type_id','job_function_id','expires_at']),
            ['uuid' => Str::uuid(), 'status' => 'active', 'moderation_status' => 'approved']
        ));
        // Create default pipeline stages
        foreach (['Application Review','Phone Screen','Interview','Offer'] as $i => $name) {
            $job->pipelineStages()->create(['name' => $name, 'order' => $i]);
        }
        return redirect()->route('employer.jobs.show', $job->id)->with('success','Job created.');
    }

    public function jobShow(JobRequisition $job) {
        $org = $this->getOrg();
        abort_if($job->organization_id !== $org->id, 403);
        $job->load(['pipelineStages.applications.candidate.user','department','jobType','jobFunction']);
        return Inertia::render('Jobify/Company/Jobs/Show', ['org' => $org, 'job' => $job]);
    }

    public function jobEdit(JobRequisition $job) {
        $org = $this->getOrg();
        abort_if($job->organization_id !== $org->id, 403);
        $departments = $org->departments()->get();
        $jobTypes    = \App\Models\JobTaxonomy::active()->type('job_type')->get();
        $functions   = \App\Models\JobTaxonomy::active()->type('job_functionality')->get();
        return Inertia::render('Jobify/Company/Jobs/Edit', compact('job','org','departments','jobTypes','functions'));
    }

    public function jobUpdate(Request $request, JobRequisition $job) {
        $org = $this->getOrg();
        abort_if($job->organization_id !== $org->id, 403);
        $job->update($request->only(['title','summary','description','requirements','skills','salary_min','salary_max','currency','location','employment_type','visa_sponsorship','department_id','expires_at']));
        return back()->with('success','Job updated.');
    }

    public function jobPublish(JobRequisition $job) {
        $org = $this->getOrg();
        abort_if($job->organization_id !== $org->id, 403);
        $job->update(['status' => $job->status === 'active' ? 'paused' : 'active']);
        return back()->with('success','Job status updated.');
    }

    public function jobDestroy(JobRequisition $job) {
        $org = $this->getOrg();
        abort_if($job->organization_id !== $org->id, 403);
        $job->delete();
        return redirect()->route('employer.jobs')->with('success','Job deleted.');
    }

    // Applications
    public function applications(Request $request) {
        $org = $this->getOrg();
        $jobUuid     = $request->input('job', 'all');
        $stageFilter = $request->input('stage', 'all');
        $search      = $request->input('search');

        $jobs = $org->jobRequisitions()
            ->withCount('applications as count')
            ->latest()->get(['id','uuid','title','employment_type','location','salary_min','salary_max','created_at']);

        $selectedJob = ($jobUuid && $jobUuid !== 'all') ? $jobs->firstWhere('uuid', $jobUuid) : null;

        $stages = $selectedJob
            ? $selectedJob->pipelineStages()->orderBy('order')->get()
            : collect();

        $baseJobIds = $selectedJob
            ? [$selectedJob->id]
            : $org->jobRequisitions()->pluck('id')->toArray();

        $query = CandidateApplication::with(['candidate.user','candidate.resumes','job','pipelineStage'])
            ->whereIn('job_requisition_id', $baseJobIds);

        if ($search) {
            $query->whereHas('candidate.user', fn($q) =>
                $q->where('name','like',"%$search%")->orWhere('email','like',"%$search%")
            );
        }

        if ($stageFilter && $stageFilter !== 'all') {
            if ($stageFilter === 'unassigned') {
                $query->whereNull('pipeline_stage_id');
            } else {
                $query->where('pipeline_stage_id', $stageFilter);
            }
        }

        $baseCount   = CandidateApplication::whereIn('job_requisition_id', $baseJobIds);
        $stageCounts = [
            'all'        => (clone $baseCount)->count(),
            'unassigned' => (clone $baseCount)->whereNull('pipeline_stage_id')->count(),
        ];
        foreach ($stages as $stage) {
            $stageCounts[$stage->id] = (clone $baseCount)->where('pipeline_stage_id', $stage->id)->count();
        }

        $applications = $query->latest()->paginate(20)->withQueryString();

        return Inertia::render('Jobify/Company/Applications/Index', [
            'org'          => $org,
            'jobs'         => $jobs,
            'selectedJob'  => $selectedJob,
            'stages'       => $stages,
            'stageCounts'  => $stageCounts,
            'applications' => $applications,
            'filters'      => $request->only(['job','stage','search']),
        ]);
    }

    public function reviewApplication(CandidateApplication $application) {
        $org = $this->getOrg();
        abort_if(!$org->jobRequisitions()->where('id',$application->job_requisition_id)->exists(), 403);
        $application->load(['candidate.user','candidate.resumes','job','pipelineStage']);
        $stages = $application->job->pipelineStages()->orderBy('order')->get();
        return Inertia::render('Jobify/Company/Applications/Review', [
            'application' => $application,
            'stages'      => $stages,
            'org'         => $org,
        ]);
    }

    public function moveStage(Request $request, CandidateApplication $application) {
        $org = $this->getOrg();
        abort_if(!$org->jobRequisitions()->where('id',$application->job_requisition_id)->exists(), 403);
        $application->update(['pipeline_stage_id' => $request->stage_id, 'stage_entered_at' => now()]);

        // Fire automations for the new stage
        if ($request->stage_id) {
            $stage = PipelineStage::find($request->stage_id);
            if ($stage) {
                $this->dispatchAutomations($application->fresh(['candidate.user','job.organization.users']), $stage);
            }
        }

        return back()->with('success','Application moved.');
    }

    private function dispatchAutomations(CandidateApplication $application, PipelineStage $stage): void {
        $automations = PipelineAutomation::where('stage_id', $stage->id)->where('is_active', true)->get();
        if ($automations->isEmpty()) return;

        $candidateEmail = $application->candidate?->user?->email;
        $orgEmails      = $application->job?->organization?->users()->pluck('email')->filter()->all() ?? [];

        foreach ($automations as $automation) {
            try {
                match ($automation->action_type) {
                    'send_email', 'schedule_interview', 'request_assessment' =>
                        $candidateEmail ? Mail::to($candidateEmail)->send(new CandidateStageChangedMail($application, $automation, $stage->name)) : null,
                    'notify_team' =>
                        $orgEmails ? Mail::to($orgEmails)->send(new TeamStageNotificationMail($application, $automation, $stage->name)) : null,
                    default => null,
                };
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Automation mail failed for automation {$automation->id}: " . $e->getMessage());
            }
        }
    }

    // Automations
    public function automations(JobRequisition $job) {
        $org = $this->getOrg();
        abort_if($job->organization_id !== $org->id, 403);
        $stages      = $job->pipelineStages()->orderBy('order')->get();
        $automations = $job->automations()->with('stage')->get();
        return Inertia::render('Jobify/Company/Jobs/Automations', compact('org','job','stages','automations'));
    }

    public function storeAutomation(Request $request, JobRequisition $job) {
        $org = $this->getOrg();
        abort_if($job->organization_id !== $org->id, 403);
        $data = $request->validate([
            'stage_id'    => 'required|integer|exists:pipeline_stages,id',
            'action_type' => 'required|in:send_email,notify_team,request_assessment,schedule_interview',
            'action_config' => 'nullable',
        ]);
        $rawConfig    = $data['action_config'] ?? null;
        $actionConfig = is_array($rawConfig) ? $rawConfig : (is_string($rawConfig) && $rawConfig !== '' ? ['notes' => $rawConfig] : null);
        $job->automations()->create([
            'stage_id'      => $data['stage_id'],
            'trigger_event' => 'stage_entered',
            'action_type'   => $data['action_type'],
            'action_config' => $actionConfig,
            'is_active'     => true,
        ]);
        return back()->with('success','Automation created.');
    }

    public function toggleAutomation(PipelineAutomation $automation) {
        $org = $this->getOrg();
        abort_if(!$org->jobRequisitions()->where('id',$automation->job_requisition_id)->exists(), 403);
        $automation->update(['is_active' => !$automation->is_active]);
        return back();
    }

    public function destroyAutomation(PipelineAutomation $automation) {
        $org = $this->getOrg();
        abort_if(!$org->jobRequisitions()->where('id',$automation->job_requisition_id)->exists(), 403);
        $automation->delete();
        return back()->with('success','Automation removed.');
    }

    public function saveNote(Request $request, CandidateApplication $application) {
        $org = $this->getOrg();
        abort_if(!$org->jobRequisitions()->where('id',$application->job_requisition_id)->exists(), 403);
        $application->update(['note' => $request->note, 'screening_rating' => $request->rating]);
        return back()->with('success','Note saved.');
    }

    public function rejectApplication(CandidateApplication $application) {
        $org = $this->getOrg();
        abort_if(!$org->jobRequisitions()->where('id',$application->job_requisition_id)->exists(), 403);
        $application->update(['status' => 'rejected']);
        return back()->with('success','Application rejected.');
    }

    // Profile
    public function profile() {
        $org = $this->getOrg();
        return Inertia::render('Jobify/Company/Profile', ['org' => $org]);
    }

    public function updateProfile(Request $request) {
        $org = $this->getOrg();
        $org->update($request->only(['name','industry','size','city','country','website','description','phone','email']));
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('org-logos','public');
            $org->update(['logo' => $path]);
        }
        return back()->with('success','Profile updated.');
    }

    // Departments
    public function departments() {
        $org = $this->getOrg();
        return Inertia::render('Jobify/Company/Departments', ['org' => $org, 'departments' => $org->departments]);
    }

    public function storeDepartment(Request $request) {
        $org = $this->getOrg();
        $org->departments()->create(['name' => $request->name]);
        return back()->with('success','Department added.');
    }

    public function destroyDepartment(Department $dept) {
        $org = $this->getOrg();
        abort_if($dept->organization_id !== $org->id, 403);
        $dept->delete();
        return back()->with('success','Department removed.');
    }

    // Team
    public function team() {
        $org  = $this->getOrg();
        $team = $org->organizationUsers()->with('user')->get();
        return Inertia::render('Jobify/Company/Team', ['org' => $org, 'team' => $team]);
    }

    // Pipeline stages
    public function pipelineStages(Request $request, JobRequisition $job) {
        $org = $this->getOrg();
        abort_if($job->organization_id !== $org->id, 403);
        if ($request->isMethod('post')) {
            $job->pipelineStages()->create(['name' => $request->name, 'order' => $job->pipelineStages()->count()]);
            return back()->with('success','Stage added.');
        }
        if ($request->isMethod('put')) {
            $stage = PipelineStage::findOrFail($request->stage_id);
            $stage->update(['name' => $request->name]);
            return back()->with('success','Stage updated.');
        }
        if ($request->isMethod('delete')) {
            PipelineStage::findOrFail($request->stage_id)->delete();
            return back()->with('success','Stage removed.');
        }
    }

    public function updateStage(Request $request, PipelineStage $stage) {
        $org = $this->getOrg();
        abort_if(!$org->jobRequisitions()->where('id', $stage->job_requisition_id)->exists(), 403);
        $stage->update(['name' => $request->name]);
        return back()->with('success', 'Stage renamed.');
    }

    public function destroyStage(PipelineStage $stage) {
        $org = $this->getOrg();
        abort_if(!$org->jobRequisitions()->where('id', $stage->job_requisition_id)->exists(), 403);
        abort_if($stage->applications()->count() > 0, 422, 'Stage is not empty.');
        $stage->delete();
        return back()->with('success', 'Stage deleted.');
    }

    // AI Ranking
    public function aiRank(Request $request, JobAiService $ai, JobRequisition $job) {
        $org = $this->getOrg();
        abort_if($job->organization_id !== $org->id, 403);
        $applications = $job->applications()->with('candidate')->get();
        $candidates   = $applications->map(fn($a) => [
            'id'       => $a->id,
            'skills'   => $a->candidate->skills ?? [],
            'headline' => $a->candidate->headline,
            'experience'=> $a->candidate->experience ?? [],
        ])->toArray();
        $rankings = $ai->rankCandidates($candidates, ['title' => $job->title, 'skills' => $job->skills, 'description' => $job->description]);
        foreach ($rankings as $r) {
            CandidateApplication::where('id', $r['candidate_id'])->update([
                'match_score'  => $r['score'],
                'match_report' => [
                    'summary'        => $r['reasoning']       ?? null,
                    'matched_skills' => $r['matched_skills']  ?? [],
                    'missing_skills' => $r['missing_skills']  ?? [],
                ],
            ]);
        }
        return response()->json(['rankings' => $rankings]);
    }

    protected function getOrg(): Organization {
        return auth()->user()->organizations()->firstOrFail();
    }
}
