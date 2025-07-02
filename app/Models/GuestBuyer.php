<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestBuyer extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'address',
        'wilaya',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
