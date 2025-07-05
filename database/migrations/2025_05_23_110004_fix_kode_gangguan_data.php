<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ambil semua data aturan
        $aturans = DB::table('tbl_aturan')->get();
        
        foreach ($aturans as $aturan) {
            $kodeGangguan = $aturan->kode_gangguan;
            
            // Jika kode_gangguan bukan JSON string, convert ke JSON
            if (!empty($kodeGangguan)) {
                // Cek apakah sudah JSON
                $decoded = json_decode($kodeGangguan, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Bukan JSON, mungkin single value atau comma separated
                    if (strpos($kodeGangguan, ',') !== false) {
                        // Comma separated
                        $array = array_map('trim', explode(',', $kodeGangguan));
                    } else {
                        // Single value
                        $array = [$kodeGangguan];
                    }
                    
                    // Update ke JSON
                    DB::table('tbl_aturan')
                        ->where('kode_aturan', $aturan->kode_aturan)
                        ->update(['kode_gangguan' => json_encode($array)]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan semua JSON ke string biasa
        $aturans = DB::table('tbl_aturan')->get();
        
        foreach ($aturans as $aturan) {
            $kodeGangguan = $aturan->kode_gangguan;
            
            if (!empty($kodeGangguan)) {
                $decoded = json_decode($kodeGangguan, true);
                
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    // Convert array back to comma separated string
                    $string = implode(', ', $decoded);
                    
                    DB::table('tbl_aturan')
                        ->where('kode_aturan', $aturan->kode_aturan)
                        ->update(['kode_gangguan' => $string]);
                }
            }
        }
    }
};