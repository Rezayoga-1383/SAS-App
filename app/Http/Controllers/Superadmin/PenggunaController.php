<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PenggunaController extends Controller
{
    public function index()
    {
        return view('superadmin.pengguna');
    }

    public function getData(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $data = Pengguna::select('id', 'email', 'nama', 'role');
        return DataTables::of($data)->make(true);
    }

    public function create()
    {
        $role = ['Superadmin', 'Admin', 'Teknisi'];
        return view('superadmin.formaddpengguna', compact('role'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:pengguna,email',
            'nama' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'password' => 'required|string|min:8',
            'role' => 'required',
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
            'role.required' => 'Role wajib diisi.',
        ]);

        Pengguna::create([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'],
        ]);

        return redirect()->route('superadmin.pengguna')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $role = ['Superadmin', 'Admin', 'Teknisi'];
        return view('superadmin.formeditpengguna', compact('pengguna', 'role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|string|min:8',
            'role' => 'required',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.min' => 'Password minimal 8 karakter.',
            'role.required' => 'Role wajib diisi.',
        ]);

        $pengguna = Pengguna::findOrFail($id);

        $pengguna->nama = $request->input('nama');
        $pengguna->email = $request->input('email');
        $pengguna->role = $request->input('role');

        if ($request->filled('password')) {
            $pengguna->password = bcrypt($request->password);
        }
        
        $pengguna->save();

        return redirect()->route('superadmin.pengguna')->with('success', 'Data pengguna berhasil di update.');
    }

    public function destroy($id)
    {
        $pengguna = Pengguna::findOrFail($id);

        if (Auth::check() && Auth::id() == $pengguna->id) {
            return redirect()->route('superadmin.pengguna')->with('error', 'Anda sedang Login data tidak dapat dihapus!');
        }

        $pengguna->delete();

        return redirect()->route('superadmin.pengguna')->with('success', 'Data pengguna berhasil dihapus.');
    }
}
