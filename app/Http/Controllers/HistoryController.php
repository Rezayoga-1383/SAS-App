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

        // Ambil history berdasarkan relasi many-to-many
        $histories = LogService::whereHas('acdetail', function ($query) use ($detailAc) {
            $query->where('acdetail.id', $detailAc->id);
        })
            ->with(['pelaksana', 'acdetail'])
            ->orderByDesc('tanggal')
            ->get();

        $data = $histories->map(function ($history) {
            $acDetails = $history->acdetail->first();
            $noAc = $acDetails?->no_ac ?? '-';
            $keluhan = $acDetails?->pivot?->keluhan ?? '-';
            $jenisPekerjaan = $acDetails?->pivot?->jenis_pekerjaan ?? '-';
            
            return [
                'id'              => $history->id,
                'no_ac'           => $noAc,
                'no_spk'          => $history->no_spk,
                'tanggal'         => Carbon::parse($history->tanggal)->format('d-m-Y'),
                'waktu_mulai'     => $history->waktu_mulai,
                'waktu_selesai'   => $history->waktu_selesai,
                'keluhan'         => $keluhan,
                'jenis_pekerjaan' => $jenisPekerjaan,
                'pelaksana_nama'  => $history->pelaksana?->nama ?? '-',
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }
}
