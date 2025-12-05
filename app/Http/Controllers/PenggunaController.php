<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PenggunaController extends Controller
{
    public function index()
    {
        return view('admin.pengguna');
    }

    public function getData(Request $request)
    {
        if (! $request->ajax()) {
        abort(404); // tampilkan halaman not found
        }
        
        $data = Pengguna::select('id', 'email', 'nama', 'role');
        return DataTables::of($data)->make(true);
    }

    public function create()
    {
        $role = ['Admin', 'Teknisi'];
        return view('admin.formtambahpengguna', compact('role'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'email' => 'required|email|unique:pengguna,email',
            'nama' => ['required','string','max:255','regex:/^[a-zA-Z\s]+$/'],
            'password' => 'required|string|min:8',
            'role'      => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'nama.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'role.required'      => 'Role wajib diisi',
        ]);

        // Simpan data pengguna baru
        Pengguna::create([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role'      => $validatedData['role'],
        ]);

        // Redirect ke halaman pengguna dengan pesan sukses
        return redirect()->route('pengguna')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $role = ['Admin', 'Teknisi'];
        return view('admin.formeditpengguna', compact('pengguna','role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email',
        'password' => 'required|string|min:8',
        'role'      => 'required',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'role'         => 'Role wajib diisi',
        ]);

        $pengguna = Pengguna::findOrFail($id);
        $pengguna->nama = $request->input('nama');
        $pengguna->email = $request->input('email');
        $pengguna->password = bcrypt($request->input('password'));
        $pengguna->role = $request->input('role');
        $pengguna->save();

        return redirect()->route('pengguna')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengguna = Pengguna::findOrFail($id);

        // Cek apakah pengguna yang akan dihapus adalah pengguna yang sedang login
        if (Auth::check() && Auth::id() == $pengguna->id) {
            return redirect()->route('pengguna')
                ->with('error', 'Anda sedang Login, data tidak dapat dihapus.');
        }
        
        $pengguna->delete();

        return redirect()->route('pengguna')->with('success', 'Data berhasil dihapus.');
    }
}
