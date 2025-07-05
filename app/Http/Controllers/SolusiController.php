<?php

namespace App\Http\Controllers;

use App\Models\Solusi;
use Illuminate\Http\Request;

class SolusiController extends Controller
{
    // Menampilkan semua data solusi
    public function index()
    {
        $solusi = Solusi::all();
        return view('datasolusi', compact('solusi'));
    }

    // Menyimpan data solusi baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_solusi' => 'required|unique:tbl_solusi,kode_solusi|max:4',
            'deskripsi_solusi' => 'required',
        ]);

        Solusi::create([
            'kode_solusi' => $request->kode_solusi,
            'deskripsi_solusi' => $request->deskripsi_solusi,
        ]);

        return redirect()->route('solusi.index')->with('success', 'Data solusi berhasil ditambahkan.');
    }

    // Update data solusi berdasarkan kode_solusi
    public function update(Request $request, $kode_solusi)
    {
        $request->validate([
            'kode_solusi' => 'required|max:4|unique:tbl_solusi,kode_solusi,' . $kode_solusi . ',kode_solusi',
            'deskripsi_solusi' => 'required',
        ]);

        $solusi = Solusi::where('kode_solusi', $kode_solusi)->firstOrFail();

        $solusi->update([
            'kode_solusi' => $request->kode_solusi, // <-- sekarang bisa update kode_solusi juga
            'deskripsi_solusi' => $request->deskripsi_solusi,
        ]);

        return redirect()->route('solusi.index')->with('success', 'Data solusi berhasil diperbarui.');
    }

    // Hapus data solusi berdasarkan kode_solusi
    public function destroy($kode_solusi)
    {
        $solusi = Solusi::where('kode_solusi', $kode_solusi)->firstOrFail();
        $solusi->delete();

        return redirect()->route('solusi.index')->with('success', 'Data solusi berhasil dihapus.');
    }
}
