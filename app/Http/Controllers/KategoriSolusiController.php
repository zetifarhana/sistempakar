<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriSolusi;

class KategoriSolusiController extends Controller
{
    public function index()
    {
        $kategoriSolusi = KategoriSolusi::all();
        return view('datakategorisolusi', compact('kategoriSolusi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kategori' => 'required|unique:tbl_kategorisolusi,kode_kategori',
            'solusis' => 'required',
        ]);

        KategoriSolusi::create([
            'kode_kategori' => $request->kode_kategori,
            'solusis' => $request->solusis,
        ]);

        return redirect()->route('kategorisolusi.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriSolusi::where('kode_kategori', $id)->first();
        $kategori->update([
            'solusis' => $request->solusis,
        ]);

        return redirect()->route('kategorisolusi.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $kategori = KategoriSolusi::where('kode_kategori', $id)->first();
        $kategori->delete();
        return redirect()->route('kategorisolusi.index')->with('success', 'Data berhasil dihapus');
    }
}
