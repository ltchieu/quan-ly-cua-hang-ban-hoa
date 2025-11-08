<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;  

class Voucher extends Model
{
    use HasFactory;
    protected $fillable=['code','type','value','min_total','starts_at','ends_at','usage_limit','used'];
}
