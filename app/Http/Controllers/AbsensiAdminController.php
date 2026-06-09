<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AbsensiAdminController extends Controller
{
    public function index()
    {
        return view('admin.absensi');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'jenis'     => 'required|in:Hadir,Pulang,Izin,Sakit',
                'foto'      => 'required|string',
                'latitude'  => 'required|numeric',
                'longitude' => 'required|numeric',
                'alamat'    => 'required|string',
                'waktu'     => 'required|date_format:H:i:s',
            ]);

            $user  = auth()->user();
            $today = now()->toDateString();

            // ============================
            // CEK DUPLIKAT
            // ============================
            $already = Absensi::where('pengguna_id', $user->id)
                ->where('tanggal', $today)
                ->where('jenis', $request->jenis)
                ->exists();

            if ($already) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melakukan absensi ' . $request->jenis . ' hari ini.'
                ], 200); // ⬅️ UBAH dari 422 jadi 200
            }

            // Validasi Radius Kantor
            $kantorLat = config('absensi.kantor.latitude');
            $kantorLng = config('absensi.kantor.longitude');
            $radiusMax = config('absensi.kantor.radius');

            $jarak = $this->hitungJarak(
                $request->latitude,
                $request->longitude,
                $kantorLat,
                $kantorLng
            );

            if ($jarak > $radiusMax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda berada di luar radius kantor (' . round($jarak) . ' meter)'
                ], 200);
            }

            // ============================
            // HITUNG TERLAMBAT
            // ============================
            $status = 'Tepat Waktu';
            $menitTerlambat = 0;

            $role = $user->role;
            $jamMasukConfig = config('absensi.jam_masuk')[$role] ?? null;

            if ($request->jenis === 'Hadir' && $jamMasukConfig) {

                $toleransi = (int) config('absensi.toleransi_menit', 0);

                $waktuAbsensi = Carbon::createFromFormat('H:i:s', $request->waktu);
                $waktuBatas   = Carbon::createFromFormat('H:i', $jamMasukConfig)
                                    ->addMinutes($toleransi);

                // samakan tanggal biar bisa dibandingkan
                $waktuAbsensi->setDate(2000, 1, 1);
                $waktuBatas->setDate(2000, 1, 1);

                if ($waktuAbsensi->gt($waktuBatas)) {
                    $status = 'Terlambat';
                    $menitTerlambat = $waktuBatas->diffInMinutes($waktuAbsensi);
                }
            }

            // ============================
            // PROSES FOTO BASE64
            // ============================
            if (!preg_match('/^data:image\/(\w+);base64,/', $request->foto)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format foto tidak valid'
                ], 200);
            }

            $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $request->foto);
            $image  = base64_decode($base64);

            if ($image === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal decode gambar'
                ], 200);
            }

            $filename = 'absensi/' . $user->id . '_' . time() . '.jpg';
            Storage::disk('public')->put($filename, $image);

            // ============================
            // SIMPAN DATABASE
            // ============================
            Absensi::create([
                'pengguna_id'     => $user->id,
                'jenis'           => $request->jenis,
                'waktu'           => $request->waktu,
                'tanggal'         => $today,
                'latitude'        => $request->latitude,
                'longitude'       => $request->longitude,
                'alamat'          => $request->alamat,
                'foto'            => $filename,
                'catatan'         => $request->catatan,
                'status'          => $status,
                'menit_terlambat' => $menitTerlambat,
            ]);

            // ============================
            // RESPONSE SUKSES
            // ============================
            return response()->json([
                'success'          => true,
                'status'           => $status,
                'menit_terlambat'  => $menitTerlambat,
                'jam_masuk'        => $jamMasukConfig,
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