<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Kolom yang diizinkan diisi secara massal (mass assignment)
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'bank_name',
        'no_rekening',
        'atas_nama',
        'saldo',
    ];

    // Kolom yang disembunyikan ketika model dikonversi ke array/JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Mendefinisikan tipe casting otomatis untuk kolom tertentu
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
