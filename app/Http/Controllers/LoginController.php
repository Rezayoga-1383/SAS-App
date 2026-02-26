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
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function autentikasi(Request $request)
{
    $request->validate([
        'email'     => 'required|email',
        'password'  => 'required|min:8',
    ]);

    // key unik per email + ip
    $key = Str::lower($request->email).'|'.$request->ip();

    // cek apakah kena limit
    if (RateLimiter::tooManyAttempts($key, 3)) {

        $seconds = RateLimiter::availableIn($key);

        return back()->withErrors([
            'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik."
        ])->with('lock_seconds', $seconds);
    }

    $credentials = $request->only('email', 'password');
    $remember = $request->has('remember-me');

    if (Auth::attempt($credentials, $remember)) {

        RateLimiter::clear($key); // reset counter
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role === 'Admin') {
            return redirect()->intended('/admin/dashboard');
        } elseif ($user->role === 'Teknisi') {
            return redirect()->intended('/data-ac-rsal');
        }

        Auth::logout();
        return back()->withErrors(['email' => 'Akses ditolak.']);
    }

    // gagal login → tambah counter
    RateLimiter::hit($key, 60); // lock 60 detik setelah limit

    return back()->withErrors([
        'email' => 'Email atau Password salah!'
    ]);
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
        $bulan = $request->filled('bulan') ? (int)$request->bulan : null;

        // 🔥 base semua (join ke log_service untuk tanggal)
        $base = LogServiceDetail::query()
            ->join('log_service', 'log_service.id', '=', 'log_service_detail.log_service_id')
            ->whereYear('log_service.tanggal', now()->year);

        // ===== TOTAL SEMUA =====
        $totalCuciSemua = (clone $base)
            ->where('jenis_pekerjaan','like','%cuci ac%')
            ->count();

        $totalPerbaikanSemua = (clone $base)
            ->where('jenis_pekerjaan','like','%perbaikan%')
            ->count();

        $totalGantiSemua = (clone $base)
            ->where('jenis_pekerjaan','like','%ganti unit%')
            ->count();

        // ===== TOTAL BULAN =====
        $bulanCuci = 0;
        $bulanPerbaikan = 0;
        $bulanGanti = 0;

        if ($bulan) {
            $bulanBase = (clone $base)->whereMonth('log_service.tanggal', $bulan);

            $bulanCuci = (clone $bulanBase)
                ->where('jenis_pekerjaan','like','%cuci ac%')
                ->count();

            $bulanPerbaikan = (clone $bulanBase)
                ->where('jenis_pekerjaan','like','%perbaikan%')
                ->count();

            $bulanGanti = (clone $bulanBase)
                ->where('jenis_pekerjaan','like','%ganti unit%')
                ->count();
        }

        return response()->json([
            'semua' => [
                'cuci'=>$totalCuciSemua,
                'perbaikan'=>$totalPerbaikanSemua,
                'ganti'=>$totalGantiSemua,
            ],
            'bulan' => [
                'cuci'=>$bulanCuci,
                'perbaikan'=>$bulanPerbaikan,
                'ganti'=>$bulanGanti,
            ],
            'bulan_label' => $bulan ? Carbon::create()->month($bulan)->translatedFormat('F') : null
        ]);
    }
}