<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsVerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'code',
        'status',
    ];

    protected $casts = [
        'phone_number' => 'string',
        'code' => 'string',
    ];
}
