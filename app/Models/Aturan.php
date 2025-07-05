<?php
// App\Models\Aturan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aturan extends Model
{
    use HasFactory;

    protected $table = 'tbl_aturan';
    protected $primaryKey = 'kode_aturan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_aturan',
        'kode_gangguan',
        'kode_penyebab',
    ];

    // Method helper untuk mendecode kode_gangguan dengan lebih robust
    public function getKodeGangguanArrayAttribute()
    {
        return $this->decodeKodeGangguan($this->kode_gangguan);
    }

    public function decodeKodeGangguan($raw)
    {
        // Jika null atau empty, return empty array
        if (empty($raw)) {
            return [];
        }
        
        // Jika sudah array, return langsung
        if (is_array($raw)) {
            return $raw;
        }
        
        // Jika string, coba decode JSON
        if (is_string($raw)) {
            // Coba decode pertama
            $decoded = json_decode($raw, true);
            
            // Jika berhasil dan hasilnya array
            if (is_array($decoded)) {
                // Cek apakah perlu decode lagi (double encoding)
                if (count($decoded) === 1 && is_string($decoded[0])) {
                    $decoded2 = json_decode($decoded[0], true);
                    if (is_array($decoded2)) {
                        return $decoded2;
                    }
                }
                return $decoded;
            }
        }
        
        return [];
    }

    // Relasi ke gangguan - perbaiki relasi
    public function gangguans()
    {
        // Karena kode_gangguan berisi array, kita tidak bisa pakai relasi Eloquent standar
        // Kita akan handle ini di controller
        return collect();
    }
    public function gejalas() {
        return $this->hasMany(Gangguan::class, 'kode_gangguan', 'kode_gangguan');
    }
    public function penyebab()
    {
        return $this->belongsTo(Penyebab::class, 'kode_penyebab', 'kode_penyebab');
    }
}
