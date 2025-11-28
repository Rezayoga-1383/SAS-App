<?php

namespace App\Http\Controllers;

use App\Models\MerkAC;
use App\Models\JenisAC;
use App\Models\Ruangan;
use App\Models\DetailAC;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function autentikasi(Request $request)
    {
    // Validasi input
    $request->validate([
        'email'     => 'required|email',
        'password'  => 'required|min:8',
    ], [
        'email.required'    => 'Email wajib diisi',
        'email.email'       => 'Format email tidak valid',
        'password.required' => 'Password wajib diisi',
        'password.min'      => 'Password minimal 8 karakter'
    ]);

    $credentials = $request->only('email', 'password');
    $remember    = $request->has('remember-me');

    // Attempt login
    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();

        $user = Auth::user();

        if($user->role === 'Admin') {
            return redirect()->intended('/admin/dashboard');
        }elseif ($user->role === "Teknisi") {
            return redirect()->intended('/sas/input-data-ac');
        }else {
            Auth::logout();
            return back()->withErrors(['email' => 'Akses ditolak. Role tidak dikenali!']);
        }
    }

    return back()->withErrors(['email' => 'Email atau Password salah!']);

}
    public function admin()
    {
        // Data Jenis AC
        $datajenis = DetailAC::join('jenisac', 'acdetail.id_jenisac', '=', 'jenisac.id')
            ->select('jenisac.nama_jenis')
            ->selectRaw('COUNT(acdetail.id) as total')
            ->groupBy('jenisac.nama_jenis')
            ->get();

        // Data Merk AC
        $datamerk = DetailAC::join('merkac', 'acdetail.id_merkac', '=', 'merkac.id')
            ->select('merkac.nama_merk')
            ->selectRaw('COUNT(acdetail.id) as total')
            ->groupBy('merkac.nama_merk')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $jumlahjenis                = JenisAC::count();
        $jumlahmerk                 = MerkAC::count();
        $jumlahdepartement          = Departement::count();
        $jumlahruangan              = Ruangan::count();

        return view('admin.dashboard', compact('datajenis','datamerk', 'jumlahjenis', 'jumlahmerk', 'jumlahdepartement', 'jumlahruangan'));
    }
}
