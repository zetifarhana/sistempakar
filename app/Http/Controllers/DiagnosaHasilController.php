<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gangguan;
use App\Models\Penyebab;
use App\Models\Aturan;
use App\Models\KategoriSolusi;
use App\Models\Solusi;

class DiagnosaHasilController extends Controller
{
    public function hasil(Request $request)
    {
        // Ambil data pelanggan dari session
        $dataPelanggan = $request->session()->get('data_pelanggan');
        // Ambil gangguan yang dipilih dari session
        $kodeGangguanDipilih = $request->session()->get('gangguan_terpilih', []);
        // Jika tidak ada data pelanggan atau gangguan yang dipilih, redirect
        if (!$dataPelanggan || empty($kodeGangguanDipilih)) {
            return redirect()->route('diagnosa.index')->with('error', 'Data tidak lengkap. Silakan isi form diagnosa kembali.');
        }
        // Ambil data gangguan yang dipilih
        $gangguan = Gangguan::whereIn('kode_gangguan', $kodeGangguanDipilih)->get();
        // Ambil semua aturan dengan relasi penyebab
        $semuaAturan = Aturan::with(['penyebab'])->get();
        // Jika tidak ada aturan sama sekali
        if ($semuaAturan->isEmpty()) {
            return view('diagnosahasil', [
                'dataPelanggan' => $dataPelanggan,
                'gangguan' => $gangguan, 
                'hasil' => [], 
                'kerusakanKuat' => false
            ])->with('error', 'Tidak ada data aturan dalam database');
        }
        $penyebabTerhitung = [];
        // Cek aturan yang cocok dengan gangguan yang dipilih
        foreach ($semuaAturan as $aturan) {
            // Skip jika tidak ada kode_penyebab
            if (empty($aturan->kode_penyebab)) {
                continue;
            }
            // Decode gangguan dalam aturan
            $gangguanDalamAturan = $this->safeDecodeGangguan($aturan->kode_gangguan);
            if (!is_array($gangguanDalamAturan) || empty($gangguanDalamAturan)) {
                continue;
            }
            // Cek apakah ada irisan antara gangguan dalam aturan dengan gangguan yang dipilih
            $gangguanCocok = array_intersect($gangguanDalamAturan, $kodeGangguanDipilih);
            if (!empty($gangguanCocok)) {
                $kodePenyebab = $aturan->kode_penyebab;
                // Inisialisasi jika belum ada
                if (!isset($penyebabTerhitung[$kodePenyebab])) {
                    $penyebabTerhitung[$kodePenyebab] = [
                        'penyebab' => $aturan->penyebab,
                        'gangguan_kodes' => [],
                        'total' => 0
                    ];
                }
                // Tambahkan SEMUA kode gangguan dari aturan ini
                foreach ($gangguanDalamAturan as $kode) {
                    if (!in_array($kode, $penyebabTerhitung[$kodePenyebab]['gangguan_kodes'])) {
                        $penyebabTerhitung[$kodePenyebab]['gangguan_kodes'][] = $kode;
                    }
                }
                $penyebabTerhitung[$kodePenyebab]['total']++;
            }
        }
        $hasil = [];
        foreach ($penyebabTerhitung as $kodePenyebab => $item) {
            // Ambil data gangguan berdasarkan kode yang tersimpan dari aturan
            $gangguanData = Gangguan::whereIn('kode_gangguan', $item['gangguan_kodes'])->get();
            // Hitung total aturan untuk penyebab ini
            $totalAturanPenyebab = Aturan::where('kode_penyebab', $kodePenyebab)->count();
            $persentase = $totalAturanPenyebab > 0 ? round(($item['total'] / $totalAturanPenyebab) * 100) : 0;
            // Ambil solusi berdasarkan penyebab → kategori → solusi
            $solusis = $this->getSolusisByPenyebab($kodePenyebab);
            $hasil[] = [
                'kode_penyebab' => $kodePenyebab,
                'penyebab' => $item['penyebab'],
                'gangguan' => $gangguanData,
                'solusis' => $solusis,
                'persentase' => $persentase
            ];
        }
       
        $hasil = collect($hasil)->sortByDesc('persentase')->values()->toArray();
        $kerusakanKuat = collect($hasil)->where('persentase', '>=', 100)->isNotEmpty();
        return view('diagnosahasil', compact('dataPelanggan', 'gangguan', 'hasil', 'kerusakanKuat'));
    }

    /**
     * Decode JSON string gangguan dengan aman
     * 
     * @param string $kodeGangguan
     * @return array
     */
    private function safeDecodeGangguan($kodeGangguan)
    {
        // Jika sudah berupa array, return langsung
        if (is_array($kodeGangguan)) {
            return $kodeGangguan;
        }

        // Jika string kosong atau null
        if (empty($kodeGangguan)) {
            return [];
        }

        // Decode JSON string
        $decoded = json_decode($kodeGangguan, true);
        
        // Jika decode gagal atau hasilnya bukan array
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            // Coba sebagai string biasa (misal: "G1,G2,G3")
            if (is_string($kodeGangguan) && strpos($kodeGangguan, ',') !== false) {
                return array_map('trim', explode(',', $kodeGangguan));
            }
            // Atau sebagai single value
            if (is_string($kodeGangguan)) {
                return [trim($kodeGangguan)];
            }
            return [];
        }

        return $decoded;
    }

    /**
     * Ambil solusi berdasarkan penyebab
     * Alur: Penyebab (P1) → Kategori (K1) → Solusi (S1)
     * 
     * @param string $kodePenyebab
     * @return \Illuminate\Support\Collection
     */
    private function getSolusisByPenyebab($kodePenyebab)
    {
        try {
            // Ambil data penyebab berdasarkan kode
            $penyebab = Penyebab::where('kode_penyebab', $kodePenyebab)->first();
            
            if (!$penyebab) {
                return collect([]);
            }

            // Ambil kode_kategori dari tabel penyebab
            $kodeKategori = $penyebab->kode_kategori;
            
            if (!$kodeKategori) {
                return collect([]);
            }

            // Ambil kategori solusi berdasarkan kode_kategori
            $kategoriSolusi = KategoriSolusi::where('kode_kategori', $kodeKategori)->first();
            
            if (!$kategoriSolusi) {
                return collect([]);
            }

            // Ambil semua solusi berdasarkan kode_kategori
            // Asumsi: tabel solusi memiliki field kode_kategori atau relasi ke kategori
            $solusis = Solusi::where('kode_kategori', $kodeKategori)->get();

            // Jika tidak ada relasi langsung, coba dengan kode_solusi yang sesuai dengan kategori
            if ($solusis->isEmpty()) {
                // Ambil berdasarkan pattern kode_solusi (misal: K1 → S1, S2, S3)
                $kodeSolusiPattern = str_replace('K', 'S', $kodeKategori);
                $solusis = Solusi::where('kode_solusi', 'LIKE', $kodeSolusiPattern . '%')->get();
            }

            // Format data solusi sesuai kebutuhan
            $hasilSolusis = collect([]);
            
            foreach ($solusis as $solusi) {
                $hasilSolusis->push([
                    'kode_solusi' => $solusi->kode_solusi,
                    'deskripsi_solusi' => $solusi->deskripsi_solusi,
                    'nama_solusi' => $solusi->nama_solusi ?? $solusi->deskripsi_solusi
                ]);
            }

            // Jika tidak ada solusi spesifik, ambil dari kategori solusi
            if ($hasilSolusis->isEmpty() && $kategoriSolusi->solusi) {
                $hasilSolusis->push([
                    'kode_solusi' => $kodeKategori,
                    'deskripsi_solusi' => $kategoriSolusi->solusi,
                    'nama_solusi' => $kategoriSolusi->solusi
                ]);
            }

            return $hasilSolusis;

        } catch (\Exception $e) {
            // Log error untuk debugging
            return collect([]);
        }
    }

    /**
     * Alternative method untuk mengambil solusi dari kategori solusi langsung
     * 
     * @param string $kodeKategori
     * @return \Illuminate\Support\Collection
     */
    private function getSolusisByKategori($kodeKategori)
    {
        try {
            // Ambil kategori solusi
            $kategoriSolusi = KategoriSolusi::where('kode_kategori', $kodeKategori)->first();
            
            if (!$kategoriSolusi) {
                return collect([]);
            }

            // Jika field 'solusi' di tabel kategorisolusi berisi deskripsi solusi
            if ($kategoriSolusi->solusi) {
                return collect([[
                    'kode_solusi' => $kodeKategori,
                    'deskripsi_solusi' => $kategoriSolusi->solusi,
                    'nama_solusi' => $kategoriSolusi->solusi
                ]]);
            }

            return collect([]);

        } catch (\Exception $e) {
           
            return collect([]);
        }
    }

    /**
     * Clear session data diagnosa
     */
    public function clearSession(Request $request)
    {
        $request->session()->forget(['data_pelanggan', 'gangguan_terpilih']);
        return redirect()->route('diagnosa.index');
    }
}