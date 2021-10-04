<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReleaseOrderDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ro_id',
        'item_id',
        'quantity',
        'unit_price'
    ];

    public function releaseOrder()
    {
        return $this->belongsTo(ReleaseOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
