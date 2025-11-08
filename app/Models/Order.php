<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;  

class Order extends Model
{
    use HasFactory;
    protected $fillable=['user_id','full_name','phone','address','payment_method','status','total','note'];
    public function items(){ return $this->hasMany(OrderItem::class); }
    public function user(){ return $this->belongsTo(User::class); }
}
