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
        'no_resi',
        'metode_pembayaran',
        'barang_dikirim',
        'tanggal_dikirim',
        'alasan_komplain',
        'video_unboxing',
        'waktu_sampai',
        'admin_note',
        'estimasi_tiba',
        'admin_note_at',
        'bank_refund',
        'norek_refund',
        'namarek_refund',
        'ekspedisi_retur',
        'no_resi_retur',
        'tanggal_retur',
        'retur_diterima_seller',
    ];

    protected $casts = [
        'barang_dikirim'        => 'boolean',
        'tanggal_dikirim'       => 'datetime',
        'waktu_sampai'          => 'datetime',
        'estimasi_tiba'         => 'date',
        'admin_note_at'         => 'datetime',
        'tanggal_retur'         => 'datetime',
        'retur_diterima_seller' => 'boolean',
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
