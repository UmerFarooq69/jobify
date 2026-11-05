<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CandidateApplication extends Model {
    protected $fillable = ['uuid','candidate_profile_id','job_requisition_id','pipeline_stage_id','resume_id','status','source','match_score','match_report','screening_answers','note','stage_entered_at','applied_at'];
    protected $casts = ['match_report'=>'array','screening_answers'=>'array','applied_at'=>'datetime','stage_entered_at'=>'datetime'];

    public function candidate() { return $this->belongsTo(CandidateProfile::class,'candidate_profile_id'); }
    public function job() { return $this->belongsTo(JobRequisition::class,'job_requisition_id'); }
    public function stage() { return $this->belongsTo(PipelineStage::class,'pipeline_stage_id'); }
    public function pipelineStage() { return $this->belongsTo(PipelineStage::class,'pipeline_stage_id'); }
    public function resume() { return $this->belongsTo(Resume::class); }
}
