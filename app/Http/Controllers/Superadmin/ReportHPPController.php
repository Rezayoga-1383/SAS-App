<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\LogService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportHPPController extends Controller
{
    public function index()
    {
        return view('superadmin.hppreport');
    }

    public function getData(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;
        $jenis = $request->jenis_service;

        $query = LogService::with([
            'teknisi',
            'hppDetail',
            'details.acdetail.ruangan.departement'
        ]);

        // FILTER TANGGAL
        if ($start && $end) {
            $query->whereBetween('tanggal', [$start, $end]);
        } elseif ($start) {
            $query->whereDate('tanggal', '>=', $start);
        } elseif ($end) {
            $query->whereDate('tanggal', '<=', $end);
        }

        // FILTER JENIS
        if ($jenis) {
            $query->whereHas('details', function ($q) use ($jenis) {
                $q->where('kategori_pekerjaan', $jenis);
            });
        }

        $data = $query->get();

        $result = [];
        $no = 1;

        foreach ($data as $log) {

            $totalHpp = optional($log->hppDetail)->sum('nominal');

            // ✅ FIX: loop semua detail
            $departement = '-';
            $ruangan = '-';

            foreach ($log->details as $detail) {
                if (
                    $detail->acdetail &&
                    $detail->acdetail->ruangan &&
                    $detail->acdetail->ruangan->departement
                ) {
                    $ruangan = $detail->acdetail->ruangan->nama_ruangan ?? '-';
                    $departement = $detail->acdetail->ruangan->departement->nama_departement ?? '-';
                    break;
                }
            }

            // teknisi
            $teknisi = $log->teknisi->pluck('nama')->implode(', ') ?: '-';

            $result[] = [
                'no' => $no++,
                'no_spk' => $log->no_spk ?? '-',
                'tanggal' => $log->tanggal ?? '-',
                'teknisi' => $teknisi,
                'departement' => $departement,
                'ruangan' => $ruangan,
                'pekerjaan' => $log->details->pluck('kategori_pekerjaan')->unique()->implode(', '),
                'total_hpp' => number_format($totalHpp, 0, ',', '.')
            ];
        }

        return response()->json($result);
    }

    public function exportPdf(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;
        $jenis = $request->jenis_service;

        $query = LogService::with([
            'teknisi',
            'hppDetail',
            'details.acdetail.ruangan.departement'
        ]);

        // FILTER TANGGAL
        if ($start && $end) {
            $query->whereBetween('tanggal', [$start, $end]);
        } elseif ($start) {
            $query->whereDate('tanggal', '>=', $start);
        } elseif ($end) {
            $query->whereDate('tanggal', '<=', $end);
        }

        // FILTER JENIS
        if ($jenis) {
            $query->whereHas('details', function ($q) use ($jenis) {
                $q->where('kategori_pekerjaan', $jenis);
            });
        }

        $data = $query->get();

        $result = [];
        $grandTotal = 0;

        foreach ($data as $log) {

            $totalHpp = optional($log->hppDetail)->sum('nominal');
            $grandTotal += $totalHpp;

            // 🔥 FIX: ambil relasi aman
            $departement = '-';
            $ruangan = '-';

            foreach ($log->details as $detail) {
                if ($detail->acdetail && $detail->acdetail->ruangan) {

                    $ruanganModel = $detail->acdetail->ruangan;

                    $ruangan = $ruanganModel->nama_ruangan ?? '-';

                    if ($ruanganModel->departement) {
                        $departement = $ruanganModel->departement->nama_departement ?? '-';
                    }

                    break;
                }
            }

            $result[] = [
                'no_spk' => $log->no_spk,
                'tanggal' => $log->tanggal,
                'teknisi' => $log->teknisi->pluck('nama')->implode(', '),
                'departement' => $departement,
                'ruangan' => $ruangan,
                'pekerjaan' => $log->details->pluck('kategori_pekerjaan')->unique()->implode(', '),
                'total_hpp' => $totalHpp
            ];
        }

        $pdf = Pdf::loadView('superadmin.hpppdf', [
            'data' => $result,
            'totalSpk' => count($result),
            'grandTotal' => $grandTotal,
            'start' => $start,
            'end' => $end
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-hpp.pdf');
    }
}
