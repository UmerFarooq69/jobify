<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CompanyInvitation extends Model {
    protected $fillable = ['organization_id','invited_by','email','role','token','accepted_at','expires_at'];
    protected $casts = ['accepted_at'=>'datetime','expires_at'=>'datetime'];
    public function organization() { return $this->belongsTo(Organization::class); }
    public function inviter() { return $this->belongsTo(User::class,'invited_by'); }
}
