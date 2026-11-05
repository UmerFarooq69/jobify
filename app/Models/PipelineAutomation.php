<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PipelineAutomation extends Model {
    protected $fillable = ['job_requisition_id','stage_id','trigger_event','action_type','action_config','is_active'];
    protected $casts = ['action_config'=>'array','is_active'=>'boolean'];
    public function job() { return $this->belongsTo(JobRequisition::class,'job_requisition_id'); }
    public function stage() { return $this->belongsTo(PipelineStage::class); }
}
