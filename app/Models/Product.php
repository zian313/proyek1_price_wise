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

    /**
     * Mutator: Store uploaded photos as JSON or fallback string.
     */
    public function setFotoAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['foto'] = json_encode($value);
        } else {
            $this->attributes['foto'] = $value; // legacy single string support
        }
    }

    /**
     * Accessor: Return ONLY the first image. 
     * Extremely important to prevent breaking hundreds of existing legacy Blade files!
     */
    public function getFotoAttribute($value)
    {
        if (!$value)
            return null;

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded[0] ?? null;
        }

        return $value; // Legacy simple string backward-compatibility
    }

    /**
     * Accessor: Read ALL images mapped to this product.
     * Use `$product->fotos` for the new gallery loop.
     */
    public function getFotosAttribute()
    {
        $value = $this->attributes['foto'] ?? null;
        if (!$value)
            return [];

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return [$value]; // Legacy simple string wrapped inside standard array
    }
}
