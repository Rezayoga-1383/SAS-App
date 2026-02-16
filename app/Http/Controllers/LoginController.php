<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\DetailAC;
use App\Models\JenisAC;
use App\Models\LogServiceDetail;
use App\Models\MerkAC;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return redirect()->intended('/input-data-ac');
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

    public function chartData(Request $request)
    {
        $filter = $request->jenis_service ?? 'cuci';

        $query = LogServiceDetail::query();

        // Filter berdasarkan jenis_pekerjaan
        if ($filter == 'cuci') {
            $query->where('jenis_pekerjaan', 'like', '%cuci ac%');
        } elseif ($filter == 'perbaikan') {
            $query->where('jenis_pekerjaan', 'like', '%perbaikan%');
        } elseif ($filter == 'ganti') {
            $query->where('jenis_pekerjaan', 'like', '%ganti unit%');
        }

        // Ambil data per bulan tahun ini
        $services = $query
            ->selectRaw('MONTH(created_at) as bulan, COUNT(id) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        // Siapkan 12 bulan
        $labels = [];
        $totals = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create()->month($i)->format('M');
            $totals[] = $services[$i] ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'totals' => $totals
        ]);
    }
}
