<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['type', 'title', 'message', 'reference_id', 'is_read'];

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
