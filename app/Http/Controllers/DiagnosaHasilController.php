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
        $dataPelanggan = $request->session()->get('data_pelanggan');
        $kodeGangguanDipilih = $request->session()->get('gangguan_terpilih', []);

        if (!$dataPelanggan || empty($kodeGangguanDipilih)) {
            return redirect()->route('diagnosa.index')->with('error', 'Data tidak lengkap. Silakan isi form diagnosa kembali.');
        }

        $gangguan = Gangguan::whereIn('kode_gangguan', $kodeGangguanDipilih)->get();
        $semuaAturan = Aturan::with(['penyebab'])->get();

        if ($semuaAturan->isEmpty()) {
            return view('diagnosahasil', [
                'dataPelanggan' => $dataPelanggan,
                'gangguan' => $gangguan, 
                'hasil' => [], 
                'kerusakanKuat' => false
            ])->with('error', 'Tidak ada data aturan dalam database');
        }

        $penyebabTerhitung = [];

        foreach ($semuaAturan as $aturan) {
            if (empty($aturan->kode_penyebab)) {
                continue;
            }

            $gangguanDalamAturan = $this->safeDecodeGangguan($aturan->kode_gangguan);
            if (!is_array($gangguanDalamAturan) || empty($gangguanDalamAturan)) {
                continue;
            }

            $gangguanCocok = array_intersect($gangguanDalamAturan, $kodeGangguanDipilih);
            if (!empty($gangguanCocok)) {
                $kodePenyebab = $aturan->kode_penyebab;

                if (!isset($penyebabTerhitung[$kodePenyebab])) {
                    $penyebabTerhitung[$kodePenyebab] = [
                        'penyebab' => $aturan->penyebab,
                        'gangguan_kodes' => $gangguanDalamAturan,
                        'total_cocok' => 0,
                        'jumlah_cocok' => 0
                    ];
                }

                $penyebabTerhitung[$kodePenyebab]['jumlah_cocok'] = count(array_intersect($kodeGangguanDipilih, $gangguanDalamAturan));
                $penyebabTerhitung[$kodePenyebab]['total_cocok'] = count($gangguanDalamAturan);
            }
        }

        $hasil = [];

        foreach ($penyebabTerhitung as $kodePenyebab => $item) {
            $gangguanData = Gangguan::whereIn('kode_gangguan', $item['gangguan_kodes'])->get();
            $jumlahGangguanDalamAturan = $item['total_cocok'];
            $jumlahGangguanCocok = $item['jumlah_cocok'];

            $persentase = $jumlahGangguanDalamAturan > 0
                ? round(($jumlahGangguanCocok / $jumlahGangguanDalamAturan) * 100)
                : 0;

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
        $kerusakanKuat = !empty($hasil) && $hasil[0]['persentase'] == 100;

        return view('diagnosahasil', compact('dataPelanggan', 'gangguan', 'hasil', 'kerusakanKuat'));
    }

    private function safeDecodeGangguan($kodeGangguan)
    {
        if (is_array($kodeGangguan)) {
            return $kodeGangguan;
        }

        if (empty($kodeGangguan)) {
            return [];
        }

        $decoded = json_decode($kodeGangguan, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            if (is_string($kodeGangguan) && strpos($kodeGangguan, ',') !== false) {
                return array_map('trim', explode(',', $kodeGangguan));
            }

            if (is_string($kodeGangguan)) {
                return [trim($kodeGangguan)];
            }

            return [];
        }

        return $decoded;
    }

    private function getSolusisByPenyebab($kodePenyebab)
    {
        try {
            $penyebab = Penyebab::where('kode_penyebab', $kodePenyebab)->first();
            if (!$penyebab) return collect([]);

            $kodeKategori = $penyebab->kode_kategori;
            if (!$kodeKategori) return collect([]);

            $kategoriSolusi = KategoriSolusi::where('kode_kategori', $kodeKategori)->first();
            if (!$kategoriSolusi) return collect([]);

            $solusis = Solusi::where('kode_kategori', $kodeKategori)->get();

            if ($solusis->isEmpty()) {
                $kodeSolusiPattern = str_replace('K', 'S', $kodeKategori);
                $solusis = Solusi::where('kode_solusi', 'LIKE', $kodeSolusiPattern . '%')->get();
            }

            $hasilSolusis = collect([]);
            foreach ($solusis as $solusi) {
                $hasilSolusis->push([
                    'kode_solusi' => $solusi->kode_solusi,
                    'deskripsi_solusi' => $solusi->deskripsi_solusi,
                    'nama_solusi' => $solusi->nama_solusi ?? $solusi->deskripsi_solusi
                ]);
            }

            if ($hasilSolusis->isEmpty() && $kategoriSolusi->solusi) {
                $hasilSolusis->push([
                    'kode_solusi' => $kodeKategori,
                    'deskripsi_solusi' => $kategoriSolusi->solusi,
                    'nama_solusi' => $kategoriSolusi->solusi
                ]);
            }

            return $hasilSolusis;
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function clearSession(Request $request)
    {
        $request->session()->forget(['data_pelanggan', 'gangguan_terpilih']);
        return redirect()->route('diagnosa.index');
    }
}
