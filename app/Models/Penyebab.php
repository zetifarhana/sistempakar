<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyebab extends Model
{
    use HasFactory;
    
    protected $table = 'tbl_penyebab';
    protected $fillable = [
        'kode_penyebab',
        'nama_penyebab',
        'kode_kategori',
    ];
    protected $primaryKey = 'kode_penyebab';
    protected $keyType = 'string';
    public $incrementing = false;

    // Relasi ke aturan - satu penyebab bisa punya banyak aturan
    public function aturans() 
    {
        return $this->hasMany(Aturan::class, 'kode_penyebab', 'kode_penyebab');
    }

    // Relasi ke kategori solusi
    public function kategorisolusi()
    {
        return $this->belongsTo(KategoriSolusi::class, 'kode_kategori', 'kode_kategori');
    }
}