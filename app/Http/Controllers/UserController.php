<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index()
    {
        $users = User::all();
        return view('datauser', compact('users'));
    }

    // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:tbl_user,username',
            'password' => 'required|string|min:6',
            'level'    => 'required|in:superadmin,admin'
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'level'    => $request->level
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');

    }
    // Mengupdate data user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:50|unique:tbl_user,username,' . $id,
            'level' => 'required|in:superadmin,admin',
            'password' => 'nullable|string|min:6'
        ]);

        $user->username = $request->username;
        $user->level = $request->level;

        // Jika password diisi, update
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'User berhasil diupdate.');
    }

    // Menghapus data user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index', $user->id)->with('success', 'User berhasil dihapus.');
    }

    }

