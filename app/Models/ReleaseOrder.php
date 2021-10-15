<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReleaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'released_to',
        'created_by',
        'approved_by',
        'released_by',
        'ro_num',
        'memo',
        'status_id'
    ];

    public function ro_details()
    {
        return $this->hasMany(ReleaseOrderDetail::class, 'ro_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function releaser()
    {
        return $this->belongsTo(User::class, 'released_by');
    }
}
