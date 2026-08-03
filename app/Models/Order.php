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
        'bukti_transfer_refund',
        'refund_dikonfirmasi_buyer',
        'bukti_resi',
        'bukti_resi_retur',
        'seller_dispute_reason',
        'seller_dispute_video',
    ];

    // Casting otomatis kolom ke tipe data yang sesuai
    protected $casts = [
        'barang_dikirim' => 'boolean',
        'tanggal_dikirim' => 'datetime',
        'waktu_sampai' => 'datetime',
        'estimasi_tiba' => 'date',
        'admin_note_at' => 'datetime',
        'tanggal_retur' => 'datetime',
        'retur_diterima_seller' => 'boolean',
        'refund_dikonfirmasi_buyer' => 'boolean',
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
