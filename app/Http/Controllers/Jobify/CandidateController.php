<?php
namespace App\Http\Controllers\Jobify;

use App\Http\Controllers\Controller;
use App\Models\CandidateProfile;
use App\Models\JobRequisition;
use App\Models\SavedJob;
use App\Models\JobAlert;
use App\Models\CandidateRecommendation;
use App\Services\ResumeParserService;
use App\Services\SkillGapService;
use App\Services\InterviewPrepService;
use App\Services\MatchScoringService;
use App\Services\ResumeGeneratorService;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CandidateController extends Controller
{
    // Onboarding
    public function onboarding() {
        $user = auth()->user();
        if ($user->candidateProfile?->onboarding_completed) return redirect()->route('candidate.dashboard');
        return Inertia::render('Jobify/Candidate/Onboarding', ['user' => $user]);
    }

    public function completeOnboarding(Request $request) {
        $user = auth()->user();
        $profile = $user->candidateProfile ?? CandidateProfile::create([
            'uuid'    => Str::uuid(),
            'user_id' => $user->id,
        ]);

        $profile->update([
            'headline'             => $request->headline,
            'summary'              => $request->summary,
            'skills'               => $request->skills ?? [],
            'experience'           => $request->experience ?? [],
            'education'            => $request->education ?? [],
            'onboarding_completed' => true,
        ]);
        $profile->update(['completeness_score' => $this->calculateCompleteness($profile->fresh())]);

        if ($request->filled('resume_path')) {
            $isPrimary = !$profile->resumes()->where('is_primary', true)->exists();
            $profile->resumes()->create([
                'file_path'     => $request->resume_path,
                'original_name' => basename($request->resume_path),
                'is_primary'    => $isPrimary,
            ]);
        }

        return redirect()->route('candidate.dashboard')->with('success','Profile setup complete!');
    }

    public function parseResume(Request $request, ResumeParserService $parser) {
        $request->validate(['resume' => 'required|file|mimes:pdf,doc,docx|max:5120']);
        $path = $request->file('resume')->store('resumes', 'public');
        $parsed = $parser->parse(storage_path('app/public/'.$path));
        return response()->json(['parsed' => $parsed, 'file_path' => $path]);
    }

    // Dashboard
    public function dashboard() {
        $user    = auth()->user();
        $profile = $user->candidateProfile;
        if (!$profile || !$profile->onboarding_completed) return redirect()->route('candidate.onboarding');

        $recentApplications = $profile->applications()
            ->with(['job.organization','stage'])
            ->latest()->take(5)->get();

        $savedJobsCount = $profile->savedJobs()->count();
        $recommendation = $profile->recommendation;

        return Inertia::render('Jobify/Candidate/Dashboard', [
            'profile'            => $profile,
            'recentApplications' => $recentApplications,
            'savedJobsCount'     => $savedJobsCount,
            'recommendations'    => $recommendation?->recommendations ?? [],
            'stats'              => [
                'applications' => $profile->applications()->count(),
                'interviews'   => $profile->applications()->where('status','interview')->count(),
                'saved_jobs'   => $savedJobsCount,
            ],
        ]);
    }

    // Profile
    public function profile() {
        $profile = auth()->user()->candidateProfile;
        return Inertia::render('Jobify/Candidate/Profile', ['profile' => $profile, 'resumes' => $profile?->resumes ?? []]);
    }

    public function updateProfile(Request $request) {
        $profile = auth()->user()->candidateProfile;
        abort_if(!$profile, 404);
        $profile->update($request->only(['headline','summary','skills','experience','education','certifications','preferences']));
        $profile->update(['completeness_score' => $this->calculateCompleteness($profile->fresh())]);
        return back()->with('success','Profile updated.');
    }

    // Applications
    public function applications() {
        $profile = auth()->user()->candidateProfile;
        abort_if(!$profile, 404);
        $apps    = $profile->applications()->with(['job.organization','stage'])->latest()->paginate(20);
        return Inertia::render('Jobify/Candidate/Applications', ['applications' => $apps]);
    }

    // Saved Jobs
    public function savedJobs() {
        $profile  = auth()->user()->candidateProfile;
        abort_if(!$profile, 404);
        $saved    = $profile->savedJobs()->with('job.organization')->latest()->paginate(20);
        return Inertia::render('Jobify/Candidate/SavedJobs', ['savedJobs' => $saved]);
    }

    public function saveJob(Request $request) {
        $profile = auth()->user()->candidateProfile;
        abort_if(!$profile, 404);
        $job     = JobRequisition::findOrFail($request->job_id);
        SavedJob::firstOrCreate(['candidate_profile_id' => $profile->id, 'job_requisition_id' => $job->id]);
        return back()->with('success','Job saved.');
    }

    public function unsaveJob(SavedJob $savedJob) {
        $profile = auth()->user()->candidateProfile;
        abort_if(!$profile || $savedJob->candidate_profile_id !== $profile->id, 403);
        $savedJob->delete();
        return back()->with('success','Job removed from saved.');
    }

    // Job Alerts
    public function alerts() {
        $profile = auth()->user()->candidateProfile;
        abort_if(!$profile, 404);
        return Inertia::render('Jobify/Candidate/JobAlerts', ['alerts' => $profile->alerts]);
    }

    public function storeAlert(Request $request) {
        $profile = auth()->user()->candidateProfile;
        abort_if(!$profile, 404);
        $profile->alerts()->create($request->only(['keywords','employment_type','location','frequency']));
        return back()->with('success','Alert created.');
    }

    public function deleteAlert(JobAlert $alert) {
        $profile = auth()->user()->candidateProfile;
        abort_if(!$profile || $alert->candidate_profile_id !== $profile->id, 403);
        $alert->delete();
        return back()->with('success','Alert deleted.');
    }

    // AI: Skill Gap
    public function skillGap(Request $request, SkillGapService $service) {
        $profile = auth()->user()->candidateProfile;
        abort_if(!$profile, 404);
        if ($request->isMethod('post')) {
            $result = $service->analyse($profile, $request->target_role);
            return response()->json($result);
        }
        return Inertia::render('Jobify/Candidate/SkillGap', ['profile' => $profile]);
    }

    // AI: Interview Prep
    public function interviewPrep(Request $request, InterviewPrepService $service) {
        $profile = auth()->user()->candidateProfile;
        abort_if(!$profile, 404);
        if ($request->isMethod('post')) {
            $job    = JobRequisition::findOrFail($request->job_id);
            $result = $service->generate($profile, $job);
            return response()->json($result);
        }
        $jobs = $profile->applications()->with('job.organization')->get()->pluck('job');
        return Inertia::render('Jobify/Candidate/InterviewPrep', ['profile' => $profile, 'appliedJobs' => $jobs]);
    }

    // AI: Resume Generator
    public function resumeGenerator(Request $request, ResumeGeneratorService $service) {
        $profile = auth()->user()->candidateProfile;
        abort_if(!$profile, 404);
        if ($request->isMethod('post')) {
            $job    = $request->job_id ? JobRequisition::find($request->job_id) : null;
            $result = $service->generate($profile, $job);
            return response()->json($result);
        }
        $jobs = $profile->applications()->with('job.organization')->get()->pluck('job');
        return Inertia::render('Jobify/Candidate/ResumeGenerator', ['profile' => $profile, 'appliedJobs' => $jobs]);
    }

    // Upload resume file
    public function uploadResume(Request $request) {
        $request->validate(['resume' => 'required|file|mimes:pdf,doc,docx|max:5120']);
        $profile    = auth()->user()->candidateProfile;
        abort_if(!$profile, 404);
        $path       = $request->file('resume')->store('resumes', 'public');
        $isPrimary  = !$profile->resumes()->where('is_primary',true)->exists();
        $profile->resumes()->create([
            'file_path'     => $path,
            'original_name' => $request->file('resume')->getClientOriginalName(),
            'is_primary'    => $isPrimary,
        ]);
        return back()->with('success','Resume uploaded.');
    }

    private function calculateCompleteness(CandidateProfile $profile): int {
        $score = 0;
        if ($profile->headline)     $score += 20;
        if ($profile->summary)      $score += 15;
        if (!empty($profile->skills)) $score += 20;
        if (!empty($profile->experience)) $score += 25;
        if (!empty($profile->education))  $score += 20;
        return $score;
    }
}
