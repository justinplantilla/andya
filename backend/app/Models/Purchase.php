<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['purchase_number', 'supplier_id', 'total_cost', 'status', 'notes', 'purchased_at'];

    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function purchaseItems() { return $this->hasMany(PurchaseItem::class); }
}
