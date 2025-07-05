<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solusi extends Model
{
    protected $table = 'tbl_solusi';
    protected $primaryKey = 'kode_solusi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_solusi',
        'kode_kategori',
        'deskripsi_solusi'
    ];

    public function kategorisolusi()
    {
        return $this->belongsTo(KategoriSolusi::class, 'kode_kategori', 'kode_kategori');
    }
}
