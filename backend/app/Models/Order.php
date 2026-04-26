<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['order_number', 'user_id', 'total_amount', 'status', 'payment_method', 'gcash_number', 'shipping_address', 'notes'];

    public function user() { return $this->belongsTo(User::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function invoice() { return $this->hasOne(Invoice::class); }
}
