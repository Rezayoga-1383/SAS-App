<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AbsensiKaryawanController extends Controller
{
    public function index()
    {
        return view ('superadmin.daftarabsensi');
    }

    public function data(Request $request): JsonResponse
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
        ]);

        $absensi = Absensi::with('pengguna')
            ->tanggal($request->tanggal)
            ->orderBy('waktu', 'asc')
            ->get()
            ->append([
                'tanggal_format',
                'waktu_format',
                'status_badge',
                'jenis_badge',
                'telat_format',
                'foto_url',
            ]);

        return response()->json($absensi);
    }
}
