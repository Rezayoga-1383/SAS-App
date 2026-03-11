<?php

namespace App\Http\Controllers;

use App\Models\LogServiceDetail;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class ReportPerbaikanController extends Controller
{
    public function index()
    {
        return view('admin.reportperbaikan');
    }

    public function getReport(Request $request)
    {
        if (!$request->start_date || !$request->end_date) {
            return response()->json([
                'data'  => [],
                'totalac' => 0,
                'totalperbaikan' => 0
            ]);
        }

        $start = $request->start_date;
        $end   = $request->end_date;

        $logs = LogServiceDetail::with([
            'acdetail.ruangan.departement',
            'logService.teknisi'
        ])
        ->where('kategori_pekerjaan', 'perbaikan')
        ->whereHas('logService', function ($q) use ($start, $end) {
            $q->whereBetween('tanggal', [$start, $end]);
        })
        ->orderBy('acdetail_id')
        ->orderBy('log_service_id')
        ->get()
        ->groupBy('acdetail_id');

        $result = [];
        $acPerbaikanUlang = [];

        foreach ($logs as $acId => $items) {

            if ($items->count() > 1) {

                $acPerbaikanUlang[] = $acId; 

                // buang pengerjaan terakhir
                $items = $items->slice(0, $items->count() - 1);

                foreach ($items as $item) {

                    // ambil nama teknisi
                    $teknisi = $item->logService->teknisi
                        ->pluck('nama')
                        ->implode(', ');

                    $result[] = [
                        'tanggal' => $item->logService->tanggal,
                        'no_ac' => $item->acdetail->no_ac,
                        'ruangan' => $item->acdetail->ruangan->nama_ruangan ?? '-',
                        'departement' => $item->acdetail->ruangan->departement->nama_departement ?? '-',
                        'keluhan' => $item->keluhan ?? '-',
                        'jenis_pekerjaan' => $item->jenis_pekerjaan ?? '-',
                        'teknisi' => $teknisi ?: '-',
                    ];
                }
            }
        }

        return response()->json([
            'data'  => $result,
            'totalperbaikan' => count($result),
            'totalac' => count($acPerbaikanUlang)
        ]);
    }

    public function exportPdf(Request $request)
    {
        if (!$request->start_date || !$request->end_date) {
            return redirect()->back()->with('error', 'Filter harus diisi terlebih dahulu');
        }

        $start  = $request->start_date;
        $end    = $request->end_date;

        $logs   = LogServiceDetail::with([
            'acdetail.ruangan.departement',
            'logService.teknisi'
        ])
        ->where('kategori_pekerjaan', 'perbaikan')
        ->whereHas('logService', function ($q) use ($start, $end) {
            $q->whereBetween('tanggal', [$start, $end]);
        })
        ->orderBy('acdetail_id')
        ->orderBy('log_service_id')
        ->get()
        ->groupBy('acdetail_id');

        $result = [];
        $acPerbaikanUlang = [];

        foreach ($logs as $acId => $items) {
            if($items->count() > 1) {
                $acPerbaikanUlang[] = $acId;
                $items = $items->slice(0, $items->count() - 1);

                foreach ($items as $item) {
                    $teknisi = $item->logService->teknisi
                        ->pluck('nama')
                        ->implode(', ');
                    
                    $result[] = [
                        'tanggal' => $item->logService->tanggal,
                        'no_ac'   => $item->acdetail->no_ac,
                        'ruangan' => optional($item->acdetail->ruangan)->nama_ruangan ?? '-',
                        'departement' => optional(optional($item->acdetail->ruangan)->departement)->nama_departement ?? '-',
                        'keluhan' => $item->keluhan ?? '-',
                        'jenis_pekerjaan' => $item->jenis_pekerjaan ?? '-',
                        'teknisi' => $teknisi ?: '-',
                    ];
                }
            }
        }

        $totalperbaikan = count($result);
        $totalac = count($acPerbaikanUlang);

        $data = [
            'start_date' => $start,
            'end_date' => $end,
            'result' => $result,
            'totalperbaikan' => $totalperbaikan,
            'totalac' => $totalac
        ];

        $pdf = \PDF::loadview('admin.reportperbaikanpdf', $data)
            ->setPaper('F4', 'landscape');
        
        return $pdf->download('report_perbaikan.pdf');
    }
}


