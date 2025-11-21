<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;  

class Voucher extends Model
{
    use HasFactory;
    protected $fillable=['code','type','value','min_total','starts_at','ends_at','usage_limit','used','is_active','expiry_date','discount_percentage','description'];
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'expiry_date' => 'datetime',
        'is_active' => 'boolean',
    ];
}
