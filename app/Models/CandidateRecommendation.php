<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CandidateRecommendation extends Model {
    protected $fillable = ['candidate_profile_id','recommendations','computed_at'];
    protected $casts = ['recommendations'=>'array','computed_at'=>'datetime'];
    public function candidate() { return $this->belongsTo(CandidateProfile::class,'candidate_profile_id'); }
}
