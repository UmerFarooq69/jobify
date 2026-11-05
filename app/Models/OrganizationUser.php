<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrganizationUser extends Model {
    protected $fillable = ['user_id','organization_id','role','permissions'];
    protected $casts = ['permissions'=>'array'];
    public function user() { return $this->belongsTo(User::class); }
    public function organization() { return $this->belongsTo(Organization::class); }
}
