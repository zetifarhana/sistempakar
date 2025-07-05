<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriSolusi extends Model
{
    use HasFactory;
    
    protected $table = 'tbl_kategorisolusi';
    protected $fillable = [
        'kode_kategori',
        'solusis',
    ];
    protected $casts = [
        'solusis' => 'array',
    ];
    protected $primaryKey = 'kode_kategori';
    protected $keyType = 'string';
    public $incrementing = false;

    public function solusis() 
    {
        return $this->hasMany(Solusi::class, 'kode_kategori', 'kode_kategori');
    }
    

}