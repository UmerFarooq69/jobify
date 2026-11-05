<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model {
    protected $fillable = ['uuid','user_id','headline','summary','skills','experience','education','certifications','preferences','completeness_score','onboarding_completed','identity_status','identity_rejection_reason','identity_doc_front','identity_doc_back'];
    protected $casts = ['skills'=>'array','experience'=>'array','education'=>'array','certifications'=>'array','preferences'=>'array','onboarding_completed'=>'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function resumes() { return $this->hasMany(Resume::class); }
    public function primaryResume() { return $this->hasOne(Resume::class)->where('is_primary',true); }
    public function applications() { return $this->hasMany(CandidateApplication::class); }
    public function savedJobs() { return $this->hasMany(SavedJob::class); }
    public function alerts() { return $this->hasMany(JobAlert::class); }
    public function recommendation() { return $this->hasOne(CandidateRecommendation::class); }
    public function conversations() { return $this->hasMany(Conversation::class); }
    public function evaluations() { return $this->hasMany(CandidateEvaluation::class); }
}
