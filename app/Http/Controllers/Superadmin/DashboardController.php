<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use App\Models\DetailAC;
use App\Models\JenisAC;
use App\Models\LogServiceDetail;
use App\Models\MerkAC;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function superadmin()
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

        return view('superadmin.dashboard', compact('datajenis','datamerk', 'jumlahjenis', 'jumlahmerk', 'jumlahdepartement', 'jumlahruangan'));
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
            ->where('kategori_pekerjaan','Cuci AC')
            ->count();

        $totalPerbaikanSemua = (clone $base)
            ->where('kategori_pekerjaan','Perbaikan')
            ->count();

        $totalCekSemua = (clone $base)
            ->where('kategori_pekerjaan','Cek AC')
            ->count();
        
        $totalGantiSemua = (clone $base)
            ->where('kategori_pekerjaan', 'Ganti Unit')
            ->count();

        // ===== TOTAL BULAN =====
        $bulanCuci = 0;
        $bulanPerbaikan = 0;
        $bulanCek = 0;
        $bulanGanti = 0;

        if ($bulan) {
            $bulanBase = (clone $base)->whereMonth('log_service.tanggal', $bulan);

            $bulanCuci = (clone $bulanBase)
                ->where('kategori_pekerjaan', 'Cuci AC')
                ->count();

            $bulanPerbaikan = (clone $bulanBase)
                ->where('kategori_pekerjaan', 'Perbaikan')
                ->count();
            
            $bulanCek = (clone $bulanBase)
                ->where('kategori_pekerjaan', 'Cek AC')
                ->count();

            $bulanGanti = (clone $bulanBase)
                ->where('kategori_pekerjaan', 'Ganti Unit')
                ->count();
        }

        return response()->json([
            'semua' => [
                'cuci'=>$totalCuciSemua,
                'perbaikan'=>$totalPerbaikanSemua,
                'cek' =>$totalCekSemua,
                'ganti'=>$totalGantiSemua,
            ],
            'bulan' => [
                'cuci'=>$bulanCuci,
                'perbaikan'=>$bulanPerbaikan,
                'cek'=>$bulanCek,
                'ganti'=>$bulanGanti,
            ],
            'bulan_label' => $bulan ? Carbon::create()->month($bulan)->translatedFormat('F') : null
        ]);
    }
}
