<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

     protected $fillable = [
        'name',
        'email',
        'payment_method',
        'attachment',
    ];
}
