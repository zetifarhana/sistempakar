<?php

namespace App\Http\Controllers;

use App\Models\Gangguan;
use Illuminate\Http\Request;

class GangguanController extends Controller
{
    public function index()
    {
        $gangguan = Gangguan::all();
        return view('datagangguan', compact('gangguan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_gangguan' => 'required|unique:tbl_gangguan,kode_gangguan|max:4',
            'nama_gangguan' => 'required|max:50',
        ]);

        Gangguan::create($request->only('kode_gangguan', 'nama_gangguan'));

        return redirect()->route('gangguan.index')->with('success', 'Data gangguan berhasil ditambahkan.');
    }

    public function update(Request $request, $kode_gangguan)
    {
        $gangguan = Gangguan::where('kode_gangguan', $kode_gangguan)->firstOrFail();

        $request->validate([
            'nama_gangguan' => 'required|max:50',
        ]);

        $gangguan->update($request->only('nama_gangguan'));

        return redirect()->route('gangguan.index')->with('success', 'Data gangguan berhasil diperbarui.');
    }

    public function destroy($kode_gangguan)
    {
        $gangguan = Gangguan::where('kode_gangguan', $kode_gangguan)->firstOrFail();
        $gangguan->delete();

        return redirect()->route('gangguan.index')->with('success', 'Data gangguan berhasil dihapus.');
    }
}
