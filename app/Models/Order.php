<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'buyer_id',
        'guest_buyer_id',
        'quantity',
        'total_price',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    public function guestBuyer()
    {
        return $this->belongsTo(GuestBuyer::class);
    }

    // Getter pour obtenir les informations de l'acheteur (connecté ou invité)
    public function getBuyerInfoAttribute()
    {
        if ($this->buyer) {
            return [
                'name' => $this->buyer->name,
                'email' => $this->buyer->email,
                'type' => 'registered'
            ];
        } elseif ($this->guestBuyer) {
            return [
                'name' => $this->guestBuyer->full_name,
                'email' => $this->guestBuyer->email,
                'phone' => $this->guestBuyer->phone,
                'address' => $this->guestBuyer->address,
                'wilaya' => $this->guestBuyer->wilaya,
                'type' => 'guest'
            ];
        }
        return null;
    }
}
