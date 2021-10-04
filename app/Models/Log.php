<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'user_id',
        'info',
    ];

    public function logged_by()
    {
        return $this->belongsTo(User::class);
    }
}
