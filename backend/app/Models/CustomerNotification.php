<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerNotification extends Model
{
    protected $fillable = ['user_id', 'order_id', 'type', 'title', 'message', 'is_read'];

    public function scopeUnread($query) { return $query->where('is_read', false); }
    public function order() { return $this->belongsTo(Order::class); }
    public function user() { return $this->belongsTo(User::class); }
}
