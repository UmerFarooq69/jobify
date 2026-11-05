<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SavedJob extends Model {
    protected $fillable = ['candidate_profile_id','job_requisition_id'];
    public function candidate() { return $this->belongsTo(CandidateProfile::class,'candidate_profile_id'); }
    public function job() { return $this->belongsTo(JobRequisition::class,'job_requisition_id'); }
}
