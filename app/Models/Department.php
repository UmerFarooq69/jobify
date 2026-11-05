<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Department extends Model {
    protected $fillable = ['organization_id','name'];
    public function organization() { return $this->belongsTo(Organization::class); }
    public function jobRequisitions() { return $this->hasMany(JobRequisition::class); }
}
