<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'description', 'price', 'unit', 'image', 'status'];

    public function category() { return $this->belongsTo(Category::class); }
    public function inventory() { return $this->hasOne(Inventory::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function purchaseItems() { return $this->hasMany(PurchaseItem::class); }
}
