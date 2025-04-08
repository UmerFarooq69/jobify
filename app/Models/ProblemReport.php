<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProblemReport extends Model
{
    protected $fillable = ['name', 'email', 'purpose', 'report_type', 'problem', 'image', 'job_id', 'company_id', 'company_name'];
    
    
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    
    public function job()
    {
        return $this->belongsTo(Job_task::class, 'job_id','job_uuid');
    }
    
}