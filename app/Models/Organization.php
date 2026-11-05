<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model {
    use HasFactory;
    protected $fillable = ['name','slug','type','entity_type','website','industry','size','city','country','description','logo','cover','phone','email','verified','moderation_status','is_top_employer','status','subscription_tier','settings','parent_id'];
    protected $casts = ['verified'=>'boolean','is_top_employer'=>'boolean','settings'=>'array'];

    public function users() { return $this->belongsToMany(User::class, 'organization_users')->withPivot(['role','permissions'])->withTimestamps(); }
    public function organizationUsers() { return $this->hasMany(OrganizationUser::class); }
    public function departments() { return $this->hasMany(Department::class); }
    public function jobRequisitions() { return $this->hasMany(JobRequisition::class); }
    public function parent() { return $this->belongsTo(Organization::class, 'parent_id'); }
    public function subCompanies() { return $this->hasMany(Organization::class, 'parent_id'); }
    public function conversations() { return $this->hasMany(Conversation::class); }
    public function invitations() { return $this->hasMany(CompanyInvitation::class); }
    public function templates() { return $this->hasMany(JobTemplate::class); }
}
