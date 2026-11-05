<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model {
    protected $fillable = ['identifier','type','otp','role','expires_at','used_at'];
    protected $casts = ['expires_at'=>'datetime','used_at'=>'datetime'];
    public function isValid(): bool {
        return !$this->used_at && $this->expires_at->isFuture();
    }
}
