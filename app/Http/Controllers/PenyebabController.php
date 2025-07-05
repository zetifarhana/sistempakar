<?php

namespace App\Http\Controllers;

use App\Models\Penyebab;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenyebabController extends Controller
{
    // Menampilkan daftar penyebab
    public function index()
    {
        $penyebab = Penyebab::all();
        return view('datapenyebab', compact('penyebab'));
    }

    // Menyimpan penyebab baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_penyebab' => 'required|string|max:255|unique:tbl_penyebab,kode_penyebab',
            'nama_penyebab' => 'required|string|max:255',
            'kode_kategori' => 'required|string|max:255',
        ]);

        Penyebab::create([
            'kode_penyebab' => $request->kode_penyebab,
            'nama_penyebab' => $request->nama_penyebab,
            'kode_kategori' => $request->kode_kategori,
        ]);

        return redirect()->route('penyebab.index')->with('success', 'Penyebab berhasil ditambahkan.');
    }

    // Menampilkan form edit penyebab (kalau perlu form edit terpisah)
    public function edit($kode_penyebab)
    {
        $penyebab = Penyebab::findOrFail($kode_penyebab);
        return view('penyebab.edit', compact('penyebab')); // View edit harus beda (penyebab/edit.blade.php)
    }

    // Memperbarui data penyebab
    public function update(Request $request, $kode_penyebab)
    {
        $request->validate([
            'kode_penyebab' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tbl_penyebab', 'kode_penyebab')->ignore($kode_penyebab, 'kode_penyebab'),
            ],
            'nama_penyebab' => 'required|string|max:255',
            'kode_kategori' => 'required|string|max:255',
        ]);

        $penyebab = Penyebab::findOrFail($kode_penyebab);
        $penyebab->update([
            'kode_penyebab' => $request->kode_penyebab,
            'nama_penyebab' => $request->nama_penyebab,
            'kode_kategori' => $request->kode_kategori,
        ]);

        return redirect()->route('penyebab.index')->with('success', 'Penyebab berhasil diperbarui.');
    }

    // Menghapus data penyebab
    public function destroy($kode_penyebab)
    {
        $penyebab = Penyebab::findOrFail($kode_penyebab);
        $penyebab->delete();

        return redirect()->route('penyebab.index')->with('success', 'Penyebab berhasil dihapus.');
    }
    
}
