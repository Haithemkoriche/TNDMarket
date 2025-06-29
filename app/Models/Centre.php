<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centre extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    public function hasActiveSubscription()
    {
        return $this->subscription && $this->subscription->status === 'active' && $this->subscription->end_date >= now();
    }
}
