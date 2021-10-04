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
        return $this->belongsTo(Status::class)->get()->status;
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class)->get()->status;
    }

    public function created_by()
    {
        return $this->belongsTo(User::class)->where('id', $this->created_by)->get()->name;
    }

    public function approved_by()
    {
        return $this->belongsTo(User::class)->where('id', $this->approved_by)->get()->name;
    }

    public function received_by()
    {
        return $this->belongsTo(User::class)->where('id', $this->received_by)->get()->name;
    }
}
