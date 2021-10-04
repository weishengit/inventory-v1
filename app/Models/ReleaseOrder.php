<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReleaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'created_by',
        'approved_by',
        'released_by',
        'ro_num',
        'memo',
        'status_id'
    ];

    public function ro_details()
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
        return $this->belongsTo(User::class)->select(['name'])->where('id', $this->created_by);
    }

    public function approved_by()
    {
        return $this->belongsTo(User::class)->select(['name'])->where('id', $this->approved_by);
    }

    public function released_by()
    {
        return $this->belongsTo(User::class)->select(['name'])->where('id', $this->released_by);
    }
}
