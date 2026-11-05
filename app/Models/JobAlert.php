<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JobAlert extends Model {
    protected $fillable = ['candidate_profile_id','keywords','employment_type','location','frequency','new_matches_count','last_notified_at','is_active'];
    protected $casts = ['is_active'=>'boolean','last_notified_at'=>'datetime'];
    public function candidate() { return $this->belongsTo(CandidateProfile::class,'candidate_profile_id'); }
}
