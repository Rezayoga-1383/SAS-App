<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\DetailAC;
use App\Models\LogService;
use App\Models\LogServiceDetail;
use App\Models\LogServiceUnit;
use App\Models\Pengguna;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessSPKImages;

class SPKController extends Controller
{
    public function create()
    {
        $acdetail   = DetailAC::all();
        $departement= Departement::all();
        $teknisi    = Pengguna::where('role', 'Teknisi')->get();
        $admin      = Pengguna::whereIn('nama', ['Siti Aliyatur Rofi Ah', 'Nurul'])->get();
        $kategoriPekerjaan = ['Cuci AC', 'Perbaikan', 'Cek AC', 'Ganti Unit'];
        return view('user.FormInputSPK', compact('acdetail', 'departement', 'teknisi', 'admin', 'kategoriPekerjaan'));
    }

    public function store(Request $request)
    {
        // $start = microtime(true);

        $validated = $request->validate([
            'acdetail_ids'       => 'required|array|min:1',
            'acdetail_ids.*'     => 'required|exists:acdetail,id',
            'kategori_pekerjaan.*' => 'required|in:Cuci AC,Perbaikan,Cek AC,Ganti Unit',
            'no_spk'             => 'required|digits:4|unique:log_service,no_spk',
            'tanggal'            => 'required|date',
            'waktu_mulai'        => 'required|date_format:H:i',
            'waktu_selesai'      => 'required|date_format:H:i|after:waktu_mulai',
            'jumlah_orang'       => 'required|integer|min:1',
            'teknisi'            => 'required|array|min:1',
            'teknisi.*'          => 'required|exists:pengguna,id',
            'status'             => 'required|in:menunggu,belum selesai,selesai',
            'keluhan'            => 'required|array|min:1',
            'keluhan.*'          => 'required|string',
            'jenis_pekerjaan'    => 'required|array|min:1',
            'jenis_pekerjaan.*'  => 'required|string',
            'history_image'      => 'required|array|min:1',
            'history_image.*'    => 'required|file|image|mimes:jpg,jpeg|max:2048',
            'images'             => 'required|array|min:1',
            'images.*.foto_kolase'=> 'required|file|image|mimes:jpg,jpeg|max:2048',
            'file_spk'           => 'required|file|image|mimes:jpg,jpeg|max:2048',
            'kepada'             => 'required|string|max:100',
            'mengetahui'         => 'required|string|max:100',
            'hormat_kami'        => 'required|exists:pengguna,id',
            'pelaksana_ttd'      => 'required|exists:pengguna,id',
        ], [
            'acdetail_ids.required'         => 'Jumlah AC wajib diisi',
            'acdetail_ids.*.required'       => 'Nomor AC wajib dipilih',
            'acdetail_ids.*.exists'         => 'Nomor AC tidak valid',

            'kategori_pekerjaan.*.required' => 'Kategori Pekerjaan wajib dipilih',

            'no_spk.required'               => 'Nomor SPK wajib diisi',
            'no_spk.unique'                 => 'Nomor SPK sudah digunakan',
            'no_spk.digits'                 => 'Nomor SPK harus terdiri dari 4 digit angka',

            'tanggal.required'              => 'Tanggal SPK wajib diisi',
            'waktu_mulai.required'          => 'Waktu mulai wajib diisi',
            'waktu_selesai.required'        => 'Waktu selesai wajib diisi',
            'waktu_selesai.after'           => 'Waktu selesai harus setelah waktu mulai',
            
            'jumlah_orang.required'         => 'Jumlah teknisi wajib diisi',
            'teknisi.required'              => 'Teknisi wajib dipilih',
            'teknisi.*.required'            => 'Teknisi wajib dipilih',
            'teknisi.*.exist'               => 'Teknisi tidak valid',

            'status.required'               => 'Status Wajib diisi',

            'jumlah_ac_input.required'      => 'Jumlah AC wajib diisi',

            'keluhan.required'              => 'Keluhan wajib diisi',
            'keluhan.*.required'            => 'Keluhan wajib diisi',

            'jenis_pekerjaan.required'      => 'Jenis Pekerjaan wajib diisi',
            'jenis_pekerjaan.*.required'    => 'Jenis Pekerjaan wajib diisi',

            'history_image.required'        => 'Kartu History AC wajib diunggah',
            'history_image.array'           => 'Format kartu history tidak valid',
            'history_image.*.required'      => 'Kartu History AC wajib diunggah',
            'history_image.*.image'         => 'File kartu history harus berupa gambar',
            'history_image.*.mimes'         => 'Kartu History harus JPG/JPEG',
            'history_image.*.max'           => 'Ukuran kartu history maksimal 2 MB',

            'images.*.foto_kolase.required' => 'Foto Kolase wajib diunggah',
            'images.*.foto_kolase.image'    => 'Foto Kolase harus berupa gambar',
            'images.*.foto_kolase.mimes'    => 'Foto Kolase harus JPG/JPEG',
            'images.*.foto_kolase.max'      => 'Ukuran foto kolase maksimal 2 MB',

            'kepada.required'               => 'Bagian "Kepada" wajib diisi',
            'mengetahui.required'           => 'Bagian "Mengetahui" wajib diisi',
            'hormat_kami.required'          => 'Hormat Kami wajib dipilih',
            'pelaksana_ttd.required'        => 'Pelaksana SPK wajib dipilih',

            'file_spk.required'             => 'File SPK wajib diunggah',
            'file_spk.mimes'                => 'Tipe file harus berupa JPG/JPEG',
            'file_spk.max'                  => 'Ukuran file SPK maksimal 2 MB'
        ]);

        DB::beginTransaction();

        try {
            // ---------------- Simpan SPK utama dulu tanpa compress ----------------
            $fileSpkTemp = $request->file('file_spk')->store('temp/spk', 'public');

            $spk = LogService::create([
                'no_spk'        => $validated['no_spk'],
                'tanggal'       => $validated['tanggal'],
                'waktu_mulai'   => $validated['waktu_mulai'],
                'waktu_selesai' => $validated['waktu_selesai'],
                'jumlah_orang'  => $validated['jumlah_orang'],
                'kepada'        => $validated['kepada'],
                'mengetahui'    => $validated['mengetahui'],
                'hormat_kami'   => $validated['hormat_kami'],
                'pelaksana_ttd' => $validated['pelaksana_ttd'],
                'file_spk'      => $fileSpkTemp, // simpan sementara
                'status'        => $validated['status'],
            ]);

            // ---------------- Bulk insert detail & unit ----------------
            $detailInsert = [];
            $unitInsert = [];
            foreach ($validated['acdetail_ids'] as $i => $acdetailId) {
                $detailInsert[] = [
                    'log_service_id' => $spk->id,
                    'acdetail_id'    => $acdetailId,
                    'kategori_pekerjaan' => $validated['kategori_pekerjaan'][$i] ?? null,
                    'keluhan'        => $validated['keluhan'][$i] ?? null,
                    'jenis_pekerjaan'=> $validated['jenis_pekerjaan'][$i] ?? null,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];

                $unitInsert[] = [
                    'log_service_id' => $spk->id,
                    'acdetail_id'    => $acdetailId,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }
            LogServiceDetail::insert($detailInsert);
            LogServiceUnit::insert($unitInsert);

            // ---------------- Simpan file temp untuk queue ----------------
            $historyPaths = [];
            foreach ($request->file('history_image') as $file) {
                $historyPaths[] = $file->store('temp/history', 'public');
            }

            $kolasePaths = [];
            foreach (collect($request->file('images'))->pluck('foto_kolase') as $file) {
                $kolasePaths[] = $file->store('temp/kolase', 'public');
            }

            // ---------------- Dispatch job async ----------------
            ProcessSPKImages::dispatch(
                $spk->id,
                $historyPaths,
                $kolasePaths,
                $fileSpkTemp
            );

            // ---------------- Sync teknisi ----------------
            $spk->teknisi()->sync($validated['teknisi']);

            DB::commit();

            // $end = microtime(true);
            // $executionTime = $end - $start;

            // Log::info('Execution time: ' . $executionTime . 'detik');

            return redirect()->route('formcreatespk')
                             ->with('success', 'Data SPK berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}