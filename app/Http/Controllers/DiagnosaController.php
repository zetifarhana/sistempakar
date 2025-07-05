<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gangguan;

class DiagnosaController extends Controller
{
    public function index()
    {
        $gangguan = Gangguan::all();
        return view('diagnosa', compact('gangguan'));
    }

    public function proses(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'id_pelanggan' => 'required|string|max:50',
            'gangguan' => 'required|array',
            'gangguan.*' => 'in:0,1'
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan harus diisi',
            'id_pelanggan.required' => 'ID pelanggan harus diisi',
            'gangguan.required' => 'Pilih minimal satu gangguan'
        ]);

        $inputGangguan = $request->input('gangguan', []);

        $kodeGangguanTerpilih = array_keys(array_filter($inputGangguan, function ($value) {
            return $value == 1;
        }));

        // Validasi apakah ada gangguan yang dipilih
        if (empty($kodeGangguanTerpilih)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Silakan pilih minimal satu gangguan yang dialami.');
        }

        // Simpan data pelanggan dan gangguan yang dipilih ke session
        $request->session()->put('data_pelanggan', [
            'nama_pelanggan' => $request->input('nama_pelanggan'),
            'id_pelanggan' => $request->input('id_pelanggan')
        ]);
        
        $request->session()->put('gangguan_terpilih', $kodeGangguanTerpilih);

        return redirect()->route('diagnosa.hasil');
    }
}