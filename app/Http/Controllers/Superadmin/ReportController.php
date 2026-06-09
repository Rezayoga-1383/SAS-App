<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateReportDokumentasi;
use App\Models\LogService;
use App\Models\Report;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('superadmin.report');
    }

    public function getDokumentasi(Request $request)
    {
        if (!$request->start_date || !$request->end_date) {
            return response()->json([]);
        }

        $query = LogService::query()
            ->with([
                'units.acdetail.ruangan.departement',
                'units.images',
                'units.historyImages',
                'details' => function ($q) use ($request) {
                    if (!empty($request->jenis_service)) {
                        $q->where('kategori_pekerjaan', $request->jenis_service);
                    }
                }
            ])
            ->whereBetween('tanggal', [$request->start_date, $request->end_date])
            ->orderBy('tanggal', 'asc');

        // FILTER KATEGORI
        if (!empty($request->jenis_service)) {
            $query->whereHas('details', function($q) use ($request) {
                $q->where('kategori_pekerjaan', $request->jenis_service);
            });
        }

        $data = $query->limit(500)->get();

        $result = [];

        foreach ($data as $spk) {
            foreach ($spk->units as $unit) {

                // 🔥 Penting: hanya ambil detail yang cocok dengan unit ini
                $detail = $spk->details
                    ->firstWhere('acdetail_id', $unit->acdetail_id);

                if (!$detail) continue;

                $result[] = [
                    'no_ac' => optional($unit->acdetail)->no_ac ?? '-',
                    'tanggal' => $spk->tanggal,
                    'ruangan' => optional($unit->acdetail->ruangan)->nama_ruangan ?? '-',
                    'departemen' => optional($unit->acdetail->ruangan->departement)->nama_departement ?? '-',
                    'keluhan' => $detail->keluhan ?? '-',
                    'foto_kolase' => optional($unit->images->first())->image_path,
                    'foto_history' => optional($unit->historyImages->first())->image_path,
                ];
            }
        }

        return response()->json($result);
    }

    public function exportPdf(Request $request)
    {
        if (!$request->start_date || !$request->end_date) {
            return response()->json([
                'status' => 'error',
                'message' => 'Filter Harus diisi lengkap!'
            ]);
        }

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        if ($start->gt($end)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tanggal awal tidak boleh lebih besar dari tanggal akhir'
            ]);
        }

        if ($end->gt($start->copy()->addMonth())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maksimal 1 bulan dari tanggal awal'
            ]);
        }

        $report = Report::create([
            'user_id' => auth()->id(),
            'file' => null,
            'status' => 'processing'
        ]);

        GenerateReportDokumentasi::dispatch(
            $request->start_date,
            $request->end_date,
            $request->jenis_service,
            auth()->id(),
            $report->id
        );

        return response()->json([
            'status' => 'processing'
        ]);
    }

    public function checkStatus()
    {
        $report = Report::where('user_id', auth()->id())
            ->latest()
            ->first();
        
        if (!$report) {
            return response()->json([
                'status' => 'none'
            ]);
        }

        return response()->json([
            'status' => $report->status,
            'file' => $report->file
        ]);
    }
}
