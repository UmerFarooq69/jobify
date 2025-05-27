<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

     protected $fillable = [
        'payment_uuid',
        'plan',
        'name',
        'email',
        'payment_method',
        'attachment',
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            if (!$payment->payment_uuid) {
                $payment->payment_uuid = (string) mt_rand(1000, 9999);
            }
        });
    }
}
