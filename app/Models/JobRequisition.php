<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobRequisition extends Model {
    use HasFactory;
    protected $fillable = ['uuid','organization_id','department_id','job_type_id','job_function_id','title','summary','description','requirements','skills','screening_questions','salary_min','salary_max','currency','location','employment_type','visa_sponsorship','local_hire','urgent_hire','is_confidential','status','moderation_status','expires_at'];
    protected $casts = ['skills'=>'array','screening_questions'=>'array','visa_sponsorship'=>'boolean','local_hire'=>'boolean','urgent_hire'=>'boolean','is_confidential'=>'boolean','expires_at'=>'datetime'];

    public function organization() { return $this->belongsTo(Organization::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function jobType() { return $this->belongsTo(JobTaxonomy::class,'job_type_id'); }
    public function jobFunction() { return $this->belongsTo(JobTaxonomy::class,'job_function_id'); }
    public function pipelineStages() { return $this->hasMany(PipelineStage::class)->orderBy('order'); }
    public function applications() { return $this->hasMany(CandidateApplication::class); }
    public function template() { return $this->belongsTo(JobTemplate::class); }
    public function automations() { return $this->hasMany(PipelineAutomation::class); }
    public function savedBy() { return $this->hasMany(SavedJob::class); }
    public function scopeActive($q) { return $q->where('status','active'); }
    public function scopeApproved($q) { return $q->where('moderation_status','approved'); }
}
