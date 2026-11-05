<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JobTaxonomy extends Model {
    protected $fillable = ['type','name','icon','sort_order','active'];
    protected $casts = ['active'=>'boolean'];
    public function scopeActive($q) { return $q->where('active',true); }
    public function scopeType($q,$type) { return $q->where('type',$type); }
}
