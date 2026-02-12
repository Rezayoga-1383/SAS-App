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

        $detailAc = DetailAC::where('no_ac', $noAc)->first();

        if (!$detailAc) {
            return response()->json(['data' => []]);
        }

        $histories = LogService::whereHas('details', function ($query) use ($detailAc) {
                $query->where('acdetail_id', $detailAc->id);
            })
            ->with([
                'details' => function ($query) use ($detailAc) {
                    $query->where('acdetail_id', $detailAc->id)
                        ->with('acdetail');
                },
                'pelaksana'
            ])
            ->orderByDesc('tanggal')
            ->get();

        $data = $histories->map(function ($history) {

            $detail = $history->details->first();

            return [
                'id'              => $history->id,
                'no_ac'           => $detail?->acdetail?->no_ac ?? '-',
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
