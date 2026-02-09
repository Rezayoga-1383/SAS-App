<?php

namespace App\Http\Controllers;

use App\Models\LogService;
use App\Models\DetailAC;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HistoryController extends Controller
{
    public function index()
    {
        return view('admin.history');
    }

    public function search(Request $request)
    {
        $noAc = $request->get('no_ac');

        if (!$noAc) {
            return response()->json(['data' => []]);
        }

        // Cari AC berdasarkan no_ac
        $detailAc = DetailAC::where('no_ac', $noAc)->first();

        if (!$detailAc) {
            return response()->json(['data' => []]);
        }

        // Ambil semua SPK yang punya unit dengan acdetail ini
        $histories = LogService::whereHas('units', function ($query) use ($detailAc) {
            $query->where('acdetail_id', $detailAc->id);
        })
        ->with([
            'pelaksana',
            'units' => function ($query) use ($detailAc) {
                $query->where('acdetail_id', $detailAc->id)
                    ->with('detail');
            }
        ])
        ->orderByDesc('tanggal')
        ->get();

        $data = $histories->map(function ($history) {

            $unit = $history->units->first();
            $detail = $unit?->detail;

            return [
                'id'              => $history->id,
                'no_ac'           => $unit?->acdetail?->no_ac ?? '-',
                'no_spk'          => $history->no_spk,
                'tanggal'         => Carbon::parse($history->tanggal)->format('d-m-Y'),
                'waktu_mulai'     => $history->waktu_mulai,
                'waktu_selesai'   => $history->waktu_selesai,
                'keluhan'         => $detail?->keluhan ?? '-',
                'jenis_pekerjaan' => $detail?->jenis_pekerjaan ?? '-',
                'pelaksana_nama'  => $history->pelaksana?->nama ?? '-',
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }
}
