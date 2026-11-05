<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PipelineStage extends Model {
    protected $fillable = ['job_requisition_id','name','order','automation_config'];
    protected $casts = ['automation_config'=>'array'];
    public function job() { return $this->belongsTo(JobRequisition::class,'job_requisition_id'); }
    public function applications() { return $this->hasMany(CandidateApplication::class,'pipeline_stage_id'); }
}
