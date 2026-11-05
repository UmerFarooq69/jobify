<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model {
    protected $fillable = ['uuid','candidate_profile_id','organization_id','last_message_at'];
    protected $casts = ['last_message_at'=>'datetime'];
    public function candidate() { return $this->belongsTo(CandidateProfile::class,'candidate_profile_id'); }
    public function organization() { return $this->belongsTo(Organization::class); }
    public function messages() { return $this->hasMany(Message::class)->orderBy('created_at'); }
    public function lastMessage() { return $this->hasOne(Message::class)->latestOfMany(); }
}
