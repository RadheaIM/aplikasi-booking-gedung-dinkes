<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    
    // Daftar kolom yang diizinkan untuk diisi massal (mass assignment)
    protected $fillable = [
        'user_id', 
        'gedung_id', 
        'nama_kegiatan', 
        'waktu_mulai', // <-- GANTI INI
        'waktu_selesai', // <-- TAMBAH INI
        'status'
    ];
    
    // Relasi: Booking dimiliki oleh User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relasi: Booking dimiliki oleh Gedung
    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }
}