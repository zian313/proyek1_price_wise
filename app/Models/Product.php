<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
use HasFactory;

    // Kolom yang diizinkan diisi secara massal
    protected $fillable = [
        'user_id', 
        'category_id', 
        'nama_produk', 
        'deskripsi', 
        'harga', 
        'stok', 
        'foto',
        'bank_name',
        'no_rekening',
        'atas_nama',
    ];

    // Relasi ke Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke Seller (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Order Details
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
