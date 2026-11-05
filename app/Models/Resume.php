<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model {
    protected $fillable = ['candidate_profile_id','file_path','original_name','parsed_data','version','is_ai_generated','is_primary'];
    protected $casts = ['parsed_data'=>'array','is_ai_generated'=>'boolean','is_primary'=>'boolean'];
    public function candidate() { return $this->belongsTo(CandidateProfile::class,'candidate_profile_id'); }
}
