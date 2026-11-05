<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'role',
        'role_type',
        'phone',
        'status',
        'email_verified_at',
        'phone_verified_at',
        'auth_provider',
        'first_name',
        'last_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function isAdmin(): bool { return $this->role === 'admin' || $this->role_type === 'platform_admin'; }
    public function isUser(): bool { return $this->role === 'user'; }
    public function isCandidate(): bool { return $this->role_type === 'candidate'; }
    public function isEmployer(): bool { return in_array($this->role_type, ['company_admin','company_hiring_manager','company_recruiter','company_viewer']); }

    public function company() { return $this->hasOne(Company::class); }
    public function companies() { return $this->hasMany(Company::class); }
    public function candidateProfile() { return $this->hasOne(\App\Models\CandidateProfile::class); }
    public function organizationUsers() { return $this->hasMany(\App\Models\OrganizationUser::class); }
    public function organizations() { return $this->belongsToMany(\App\Models\Organization::class, 'organization_users')->withPivot(['role','permissions']); }
    public function primaryOrganization() { return $this->hasOneThrough(\App\Models\Organization::class, \App\Models\OrganizationUser::class, 'user_id', 'id', 'id', 'organization_id'); }

}
