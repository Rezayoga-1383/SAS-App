<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\DetailAC;
use App\Models\LogService;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        return view('superadmin.history');
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
            'details.acdetail.merkac',
            'pelaksana'
        ])
        ->orderByDesc('tanggal')->get();

        $data = $histories->map(function ($history) use ($detailAc) {
            $detail = $history->details->where('acdetail_id', $detailAc->id)->first();

            $ac = $detail?->acdetail;

            return [
                'id'                => $history->id,
                'no_ac'             => $ac?->no_ac ?? '-',
                'merk_ac'           => $ac?->merkac?->nama_merk ?? '-',
                'pk_ac'             => $ac?->pk_ac ?? '-',
                'no_spk'            => $history->no_spk,
                'tanggal'           => \Carbon\Carbon::parse($history->tanggal)->format('d-m-Y'),
                'waktu_mulai'       => $history->waktu_mulai ?? '-',
                'waktu_selesai'     => $history->waktu_selesai ?? '-',
                'keluhan'           => $detail?->keluhan ?? '-',
                'jenis_pekerjaan'   => $detail?->jenis_pekerjaan ?? '-',
                'pelaksana_nama'    => $history->pelaksana?->nama ?? '-',
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }
}
