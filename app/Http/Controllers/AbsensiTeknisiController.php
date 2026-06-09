<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AbsensiTeknisiController extends Controller
{
    public function index()
    {
        return view('user.Absensi');
    }

    public function store(Request $request)
    {
        try {

            if ($request->waktu && strlen($request->waktu) === 5) {
                $request->merge(['waktu' => $request->waktu . ':00']);
            }

            if ($request->waktu) {
                $request->merge(['waktu' => trim($request->waktu)]);
            }

            $request->validate([
                'jenis'     => 'required|in:Hadir,Pulang,Izin,Sakit',
                'foto'      => 'required|string',
                'latitude'  => 'required|numeric',
                'longitude' => 'required|numeric',
                'alamat'    => 'required|string',
                'waktu'     => 'required|date_format:H:i:s,H:i',
            ]);

            $user  = auth()->user();
            $today = now()->toDateString();

            // cek duplikat
            $already = Absensi::where('pengguna_id', $user->id)
                ->where('tanggal', $today)
                ->where('jenis', $request->jenis)
                ->exists();
            
            if ($already) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melakukan absensi ' . $request->jenis
                ], 200);
            }

            // Validasi Radius Kantor 
            $kantorList = config('absensi.kantor');
            $jarakTerdekat = PHP_INT_MAX;
            $kantorTerdekat = null;

            foreach ($kantorList as $kantor) {
                $jarak = $this->hitungJarak(
                    $request->latitude,
                    $request->longitude,
                    $kantor['latitude'],
                    $kantor['longitude']
                );

                if ($jarak < $jarakTerdekat) {
                    $jarakTerdekat = $jarak;
                    $kantorTerdekat = $kantor;
                }
            }

            if ($jarakTerdekat > $kantorTerdekat['radius']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda berada di luar radius kantor (' . round($jarakTerdekat) . ' meter dari ' . $kantorTerdekat['nama'] . ')'
                ], 200);
            }

            // Hitung Terlambat
            $status = 'Tepat Waktu';
            $menitTerlambat = 0;

            $role = $user->role;
            $jamMasukConfig = config('absensi.jam_masuk')[$role] ?? null;

            if ($request->jenis === 'Hadir' && $jamMasukConfig) {
                
                $toleransi = (int) config('absensi.toleransi_menit', 0);

                try {
                    $waktuAbsensi = Carbon::createFromFormat('H:i:s', $request->waktu);
                } catch (\Exception $e) {
                    $waktuAbsensi = Carbon::parser($request->waktu);
                }
                
                try {
                    $waktuBatas = Carbon::createFromFormat('H:i:s', $jamMasukConfig)
                                    ->addMinutes($toleransi);
                } catch (\Exception $e) {
                    $waktuBatas = Carbon::parse($jamMasukConfig)
                                    ->addMinutes($toleransi);
                }

                // samakan tanggal biar bisa dibandingkan
                $waktuAbsensi->setDate(2000, 1, 1);
                $waktuBatas->setDate(2000, 1, 1);

                if ($waktuAbsensi->gt($waktuBatas)) {
                    $status = 'Terlambat';
                    $menitTerlambat = $waktuBatas->diffInMinutes($waktuAbsensi);
                }
            }

            // Proses Foto Base64
            if (!preg_match('/^data:image\/(\w+);base64,/', $request->foto)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format foto tidak valid'
                ], 200);
            }

            $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $request->foto);
            $image = base64_decode($base64);

            if ($image === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal decode gambar'
                ], 200);
            }

            $filename = 'absensi/' . $user->id . '_' . time() . '.jpg';
            Storage::disk('public')->put($filename, $image);

            // Simpan Database
            Absensi::create([
                'pengguna_id'   => $user->id,
                'jenis'         => $request->jenis,
                'waktu'         => $request->waktu,
                'tanggal'       => $today,
                'latitude'      => $request->latitude,
                'longitude'     => $request->longitude,
                'alamat'        => $request->alamat,
                'foto'          => $filename,
                'catatan'       => $request->catatan,
                'status'        => $status,
                'menit_terlambat' => $menitTerlambat,
            ]);

            // Response Sukses
            return response()->json([
                'success' => true,
                'status'  => $status,
                'menit_terlambat' => $menitTerlambat,
                'jam_masuk' => $jamMasukConfig,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }
}
