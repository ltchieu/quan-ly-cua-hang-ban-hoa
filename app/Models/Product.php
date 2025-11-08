<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;  

class Product extends Model
{
    use HasFactory;
    protected $fillable=['category_id','name','slug','price','sale_price','stock','image','description'];
    public function category(){ return $this->belongsTo(Category::class); }
    public function reviews(){ return $this->hasMany(Review::class); }
    public function getDiscountPercentAttribute(): ?int
    {
        if (!$this->sale_price || $this->price <= 0) return null;
        return (int) round(100 * (1 - $this->sale_price / $this->price));
    }
    public function scopeActive($q){ return $q->whereHas('category', fn($c)=>$c->where('is_active',1)); }
    public function scopeInStock($q){ return $q->where('stock','>',0); }
    public function scopeOnSale($q){ return $q->whereNotNull('sale_price'); }
    public function scopeVisible($q){ return $q->where('is_active',1); }

}
