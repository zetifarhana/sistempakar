<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aturan;

class AturanController extends Controller
{
    public function index()
    {
        $aturan = Aturan::all();
        return view('dataaturan', compact('aturan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_aturan' => 'required|unique:tbl_aturan,kode_aturan',
            'kode_gangguan' => 'required|array',
            'kode_penyebab' => 'required',
        ]);

        Aturan::create([
            'kode_aturan' => $request->kode_aturan,
            'kode_gangguan' => json_encode($request->kode_gangguan), // ENCODE ke JSON string
            'kode_penyebab' => $request->kode_penyebab,
        ]);

        return redirect()->route('aturan.index')->with('success', 'Data aturan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_gangguan' => 'required|array',
            'kode_penyebab' => 'required',
        ]);

        $aturan = Aturan::findOrFail($id);
        $aturan->update([
            'kode_gangguan' => json_encode($request->kode_gangguan), // ENCODE ke JSON string
            'kode_penyebab' => $request->kode_penyebab,
        ]);

        return redirect()->route('aturan.index')->with('success', 'Data aturan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $aturan = Aturan::findOrFail($id);
        $aturan->delete();

        return redirect()->route('aturan.index')->with('success', 'Data aturan berhasil dihapus.');
    }
}