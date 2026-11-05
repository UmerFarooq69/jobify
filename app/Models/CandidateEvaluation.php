<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CandidateEvaluation extends Model {
    protected $fillable = ['user_id','candidate_profile_id','job_requisition_id','notes','rating'];
    public function user() { return $this->belongsTo(User::class); }
    public function candidate() { return $this->belongsTo(CandidateProfile::class,'candidate_profile_id'); }
    public function job() { return $this->belongsTo(JobRequisition::class,'job_requisition_id'); }
}
