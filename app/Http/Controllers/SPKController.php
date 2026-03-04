<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\DetailAC;
use App\Models\LogService;
use App\Models\LogServiceDetail;
use App\Models\LogServiceUnit;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessSPKImages;

class SPKController extends Controller
{
    public function create()
    {
        $acdetail   = DetailAC::all();
        $departement= Departement::all();
        $teknisi    = Pengguna::where('role', 'Teknisi')->get();
        $admin      = Pengguna::whereIn('nama', ['Siti Aliyatur Rofi Ah', 'Nurul'])->get();
        return view('user.FormInputSPK', compact('acdetail', 'departement', 'teknisi', 'admin'));
    }

    public function store(Request $request)
    {
        // $start = microtime(true);

        $validated = $request->validate([
            'acdetail_ids'       => 'required|array|min:1',
            'acdetail_ids.*'     => 'required|exists:acdetail,id',
            'no_spk'             => 'required|digits:4|unique:log_service,no_spk',
            'tanggal'            => 'required|date',
            'waktu_mulai'        => 'required|date_format:H:i',
            'waktu_selesai'      => 'required|date_format:H:i|after:waktu_mulai',
            'jumlah_orang'       => 'required|integer|min:1',
            'teknisi'            => 'required|array|min:1',
            'teknisi.*'          => 'required|exists:pengguna,id',
            'keluhan'            => 'required|array|min:1',
            'keluhan.*'          => 'required|string',
            'jenis_pekerjaan'    => 'required|array|min:1',
            'jenis_pekerjaan.*'  => 'required|string',
            'history_image'      => 'required|array|min:1',
            'history_image.*'    => 'required|file|image|mimes:jpg,jpeg|max:10240',
            'images'             => 'required|array|min:1',
            'images.*.foto_kolase'=> 'required|file|image|mimes:jpg,jpeg|max:10240',
            'file_spk'           => 'required|file|image|mimes:jpg,jpeg|max:10240',
            'kepada'             => 'required|string|max:100',
            'mengetahui'         => 'required|string|max:100',
            'hormat_kami'        => 'required|exists:pengguna,id',
            'pelaksana_ttd'      => 'required|exists:pengguna,id',
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
            ]);

            // ---------------- Bulk insert detail & unit ----------------
            $detailInsert = [];
            $unitInsert = [];
            foreach ($validated['acdetail_ids'] as $i => $acdetailId) {
                $detailInsert[] = [
                    'log_service_id' => $spk->id,
                    'acdetail_id'    => $acdetailId,
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