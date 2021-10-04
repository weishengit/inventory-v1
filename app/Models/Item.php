<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'type_id',
        'sku',
        'name',
        'unit_price',
        'quantity',
        'critical_level'
    ];

    public function category()
    {
        return $this->belongsTo(Type::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    public function releaseOrders()
    {
        return $this->hasMany(ReleaseOrderDetail::class);
    }
}
