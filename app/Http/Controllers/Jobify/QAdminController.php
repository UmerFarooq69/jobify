<?php
namespace App\Http\Controllers\Jobify;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\JobRequisition;
use App\Models\AdminSetting;
use App\Models\AuditLog;
use App\Models\EmailTemplate;
use App\Models\JobTaxonomy;
use App\Models\CandidateProfile;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class QAdminController extends Controller
{
    public function dashboard() {
        return Inertia::render('Jobify/Admin/Dashboard', [
            'stats' => [
                'users'        => \App\Models\User::count(),
                'candidates'   => CandidateProfile::count(),
                'companies'    => Organization::count(),
                'active_jobs'  => JobRequisition::active()->count(),
                'pending_jobs' => JobRequisition::where('moderation_status','pending')->count(),
            ],
        ]);
    }

    public function users(Request $request) {
        $query = User::query()->latest();
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name','like',"%$search%")->orWhere('email','like',"%$search%");
            });
        }
        if ($role = $request->input('role')) {
            $query->where('role_type', $role);
        }
        $users = $query->paginate(20)->withQueryString();
        return Inertia::render('Jobify/Admin/Users', ['users' => $users, 'filters' => $request->only(['search','role'])]);
    }

    public function toggleUserStatus(Request $request, User $user) {
        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);
        AuditLog::create(['user_id' => auth()->id(), 'event' => 'user_toggle_status', 'auditable_type' => User::class, 'auditable_id' => $user->id, 'ip_address' => request()->ip()]);
        return back()->with('success', 'User status updated.');
    }

    public function moderation() {
        $pendingJobs = JobRequisition::with('organization')->where('moderation_status','pending')->latest()->get();
        $pendingOrgs = Organization::where('moderation_status','pending')->latest()->get();
        return Inertia::render('Jobify/Admin/Moderation', compact('pendingJobs','pendingOrgs'));
    }

    public function moderateJob(Request $request, JobRequisition $job) {
        $job->update(['moderation_status' => $request->status, 'status' => $request->status === 'approved' ? 'active' : 'draft']);
        AuditLog::create(['user_id' => auth()->id(), 'event' => "job_{$request->status}", 'auditable_type' => JobRequisition::class, 'auditable_id' => $job->id, 'ip_address' => request()->ip()]);
        return back()->with('success','Job moderated.');
    }

    public function moderateOrg(Request $request, Organization $org) {
        $org->update(['moderation_status' => $request->status]);
        return back()->with('success','Organization moderated.');
    }

    public function settings() {
        $settings = AdminSetting::all()->keyBy('key');
        return Inertia::render('Jobify/Admin/Settings', ['settings' => $settings]);
    }

    public function updateSettings(Request $request) {
        foreach ($request->settings ?? [] as $key => $value) {
            AdminSetting::updateOrCreate(['key' => $key], ['value' => is_bool($value) ? ($value ? 'true' : 'false') : $value]);
        }
        return back()->with('success','Settings saved.');
    }

    public function jobTaxonomy() {
        $taxonomies = JobTaxonomy::orderBy('type')->orderBy('sort_order')->get();
        return Inertia::render('Jobify/Admin/JobTaxonomy', ['taxonomies' => $taxonomies]);
    }

    public function storeTaxonomy(Request $request) {
        JobTaxonomy::create($request->only(['type','name','icon','sort_order']));
        return back()->with('success','Taxonomy added.');
    }

    public function updateTaxonomy(Request $request, JobTaxonomy $taxonomy) {
        $taxonomy->update($request->only(['name','icon','sort_order','active']));
        return back()->with('success','Taxonomy updated.');
    }

    public function destroyTaxonomy(JobTaxonomy $taxonomy) {
        $taxonomy->delete();
        return back()->with('success','Taxonomy removed.');
    }

    public function emailTemplates() {
        return Inertia::render('Jobify/Admin/EmailTemplates', ['templates' => EmailTemplate::all()]);
    }

    public function updateEmailTemplate(Request $request, EmailTemplate $template) {
        $template->update($request->only(['subject','body','is_active']));
        return back()->with('success','Template updated.');
    }

    public function auditLog() {
        $logs = AuditLog::with('user')->latest()->paginate(50);
        return Inertia::render('Jobify/Admin/AuditLog', ['logs' => $logs]);
    }

    public function health() {
        $checks = [
            'database' => $this->checkDb(),
            'cache'    => $this->checkCache(),
            'storage'  => $this->checkStorage(),
            'queue'    => $this->checkQueue(),
        ];
        return Inertia::render('Jobify/Admin/Health', ['checks' => $checks]);
    }

    private function checkDb(): array {
        try { \DB::connection()->getPdo(); return ['status'=>'ok','message'=>'Connected']; }
        catch(\Exception $e) { return ['status'=>'fail','message'=>$e->getMessage()]; }
    }
    private function checkCache(): array {
        try { \Cache::put('health_check',1,10); return ['status'=>'ok','message'=>'Working']; }
        catch(\Exception $e) { return ['status'=>'fail','message'=>$e->getMessage()]; }
    }
    private function checkStorage(): array {
        return is_writable(storage_path()) ? ['status'=>'ok','message'=>'Writable'] : ['status'=>'fail','message'=>'Not writable'];
    }
    private function checkQueue(): array {
        try { $count = \DB::table('jobs')->count(); return ['status'=>'ok','message'=>"Queue ok, {$count} pending"]; }
        catch(\Exception $e) { return ['status'=>'fail','message'=>$e->getMessage()]; }
    }
}
