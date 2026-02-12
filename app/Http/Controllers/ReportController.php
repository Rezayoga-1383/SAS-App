<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\PDF;
use App\Models\LogService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.report');
    }

    public function getDokumentasi(Request $request)
    {
        // WAJIB isi tanggal saja
        if (!$request->start_date || !$request->end_date) {
            return response()->json([]);
        }

        $query = LogService::with([
            'units.acdetail.ruangan.departement',
            'units.images',
            'units.historyImages',
            'details'
        ]);

        // FILTER TANGGAL
        $query->whereBetween('tanggal', [
            $request->start_date,
            $request->end_date
        ]);

        // FILTER JENIS SERVICE (opsional)
        if (!empty($request->jenis_service)) {
            $query->whereHas('details', function($q) use ($request) {
                $q->whereRaw('LOWER(jenis_pekerjaan) LIKE ?', [
                    '%' . strtolower($request->jenis_service) . '%'
                ]);
            });
        }

        $data = $query->orderBy('tanggal', 'asc')->get();

        $result = [];

        foreach ($data as $spk) {
            foreach ($spk->units as $unit) {

                $detail = $spk->details
                    ->where('acdetail_id', $unit->acdetail_id)
                    ->first();

                $result[] = [
                    'no_ac' => $unit->acdetail->no_ac ?? '-',
                    'tanggal' => $spk->tanggal,
                    'ruangan' => $unit->acdetail->ruangan->nama_ruangan ?? '-',
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
        // 🚫 WAJIB ISI FILTER
        if (!$request->start_date || !$request->end_date || !$request->jenis_service) {
            return redirect()->back()->with('error', 'Filter harus diisi lengkap.');
        }

        $query = LogService::with([
            'units.acdetail.ruangan.departement',
            'units.images',
            'units.historyImages',
            'details'
        ]);

        // FILTER TANGGAL
        $query->whereBetween('tanggal', [
            $request->start_date,
            $request->end_date
        ]);

        // FILTER JENIS SERVICE (opsional)
        if (!empty($request->jenis_service)) {
            $query->whereHas('details', function($q) use ($request) {
                $q->whereRaw('LOWER(jenis_pekerjaan) LIKE ?', [
                    '%' . strtolower($request->jenis_service) . '%'
                ]);
            });
        }

        // URUTKAN DARI TANGGAL TERKECIL KE TERBESAR
        $spkList = $query->orderBy('tanggal', 'asc')->get();

        $data = [];

        foreach ($spkList as $spk) {
            foreach ($spk->units as $unit) {

                $data[] = [
                    'tanggal' => $spk->tanggal,
                    'no_ac' => $unit->acdetail->no_ac ?? '-',
                    'ruangan' => $unit->acdetail->ruangan->nama_ruangan ?? '-',
                    'departemen' => optional($unit->acdetail->ruangan->departement)->nama_departement ?? '-',
                    'foto_history' => optional($unit->historyImages->first())->image_path,
                    'foto_kolase' => optional($unit->images->first())->image_path,
                ];
            }
        }

        $pdf = \PDF::loadView('admin.reportpdf', [
            'data' => $data,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'jenis_service' => $request->jenis_service
        ])->setPaper('a4', 'landscape');

        return $pdf->download('report-dokumentasi.pdf');
    }
}
