<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'created_by',
        'approved_by',
        'received_by',
        'po_num',
        'memo',
        'status_id'
    ];

    public function po_details()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class)->select(['status']);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by')->select(['name']);
    }

    public function approved_by()
    {
        return $this->belongsTo(User::class, 'approved_by')->select(['name']);
    }

    public function received_by()
    {
        return $this->belongsTo(User::class, 'received_by')->select(['name']);
    }
}
