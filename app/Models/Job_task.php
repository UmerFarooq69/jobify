<?php

namespace App\Models;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Job_task extends Model
{
    protected $table = 'job_task';
    protected $fillable = [
        'company_id', 'job_title', 'job_type', 'description', 'job_salary', 'image', 'job_uuid'
    ];
    protected static function booted()
    {
        static::creating(function ($jobTask) {
            if (!$jobTask->job_uuid) {
                $jobTask->job_uuid = (string) mt_rand(10000, 999999);
            }
        });
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id'); 
    }   
    public function applications()
{
    return $this->hasMany(Application::class, 'job_id', 'id');
}
    
}
