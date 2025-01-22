<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'applications';
    protected $fillable = ['applicant_name', 'applicant_email', 'cv','job_id'];
    public function job()
    {
        return $this->belongsTo(Job_task::class, 'job_id', 'id');
    }
}
