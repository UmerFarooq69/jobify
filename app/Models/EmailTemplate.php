<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model {
    protected $fillable = ['name','display_name','subject','body','description','variables','is_active'];
    protected $casts = ['variables'=>'array','is_active'=>'boolean'];
}
