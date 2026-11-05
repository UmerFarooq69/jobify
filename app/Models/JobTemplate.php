<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JobTemplate extends Model {
    protected $fillable = ['organization_id','title','description','requirements','salary_min','salary_max','currency','location','employment_type','visa_sponsorship'];
    protected $casts = ['requirements'=>'array','visa_sponsorship'=>'boolean'];
    public function organization() { return $this->belongsTo(Organization::class); }
}
