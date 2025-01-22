<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    protected $fillable = ['name', 'city', 'location', 'image'];

    public function jobTasks()
    {
        return $this->hasMany(Job_task::class, 'company_id');
    }
    public function jobs()
    {
        return $this->hasMany(Job_task::class);
    }   

    public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function job_tasks()
    {
        return $this->hasMany(Job_task::class);
    }
}
