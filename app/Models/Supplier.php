<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tin',
        'bir',
        'vat',
        'company_name',
        'contact_person',
        'address',
        'contact',
        'email'
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
