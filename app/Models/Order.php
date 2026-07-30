<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Kolom yang diizinkan diisi secara massal (mass assignment)
    protected $fillable = [
        'user_id',
        'total_harga',
        'status',
        'bukti_transfer',
        'nama',
        'email',
        'alamat',
        'ekspedisi',
        'metode_pembayaran',
        'barang_dikirim',
        'tanggal_dikirim'
    ];

    // Casting otomatis kolom ke tipe data yang sesuai
    protected $casts = [
        'barang_dikirim' => 'boolean',
        'tanggal_dikirim' => 'datetime',
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
