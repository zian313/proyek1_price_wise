<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 
        'total_harga', 
        'status', 
        'bukti_transfer',
        'nama',
        'email',
        'alamat',
        'ekspedisi',
        'metode_pembayaran'
    ];

    // Relasi ke User (Buyer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Detail Order
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
