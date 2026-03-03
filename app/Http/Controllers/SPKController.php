<?php

namespace App\Http\Controllers;

use App\Models\AcHistoryImage;
use App\Models\Departement;
use App\Models\DetailAC;
use App\Models\LogService;
use App\Models\LogServiceDetail;
use App\Models\LogServiceImage;
use App\Models\LogServiceUnit;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use App\Jobs\ProcessSPKImages;

class SPKController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $acdetail   = DetailAC::All();
        $departement= Departement::All();
        $teknisi    = Pengguna::where('role', 'Teknisi')->get();
        $admin      = Pengguna::whereIn('nama', ['Siti Aliyatur Rofi Ah', 'Nurul'])->get();
        return view ('user.FormInputSPK', compact('acdetail', 'departement', 'teknisi', 'admin'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([

            // =============================
            // UNIT AC
            // =============================
            'acdetail_ids'       => 'required|array|min:1',
            'acdetail_ids.*'     => 'required|exists:acdetail,id',

            // =============================
            // DATA SPK
            // =============================
            'no_spk'             => 'required|digits:4|unique:log_service,no_spk',
            'tanggal'            => 'required|date',
            'waktu_mulai'        => 'required|date_format:H:i',
            'waktu_selesai'      => 'required|date_format:H:i|after:waktu_mulai',
            'jumlah_orang'       => 'required|integer|min:1',

            // =============================
            // TEKNISI
            // =============================
            'teknisi'            => 'required|array|min:1',
            'teknisi.*'          => 'required|exists:pengguna,id',

            // =============================
            // DETAIL PEKERJAAN (WAJIB SESUAI JUMLAH UNIT)
            // =============================
            'keluhan'            => 'required|array|min:1',
            'keluhan.*'          => 'required|string',

            'jenis_pekerjaan'    => 'required|array|min:1',
            'jenis_pekerjaan.*'  => 'required|string',

            // =============================
            // HISTORY IMAGE (WAJIB JPG/JPEG)
            // =============================
            'history_image'      => 'required|array|min:1',
            'history_image.*'    => 'required|file|image|mimes:jpg,jpeg|mimetypes:image/jpeg|max:10240',

            // =============================
            // FOTO KOLOASE (WAJIB JPG/JPEG)
            // =============================
            'images'                     => 'required|array|min:1',
            'images.*.foto_kolase'       => 'required|file|image|mimes:jpg,jpeg|mimetypes:image/jpeg|max:10240',

            // =============================
            // FILE SPK (WAJIB JPG/JPEG)
            // =============================
            'file_spk'           => 'required|file|image|mimes:jpg,jpeg|mimetypes:image/jpeg|max:10240',

            // =============================
            // TANDA TANGAN
            // =============================
            'kepada'             => 'required|string|max:100',
            'mengetahui'         => 'required|string|max:100',
            'hormat_kami'        => 'required|exists:pengguna,id',
            'pelaksana_ttd'      => 'required|exists:pengguna,id',
        ]);

    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | 1. SIMPAN SPK UTAMA
        |--------------------------------------------------------------------------
        */

        $fileSpkPath = $this->storeCompressedImage(
            $request->file('file_spk'),
            'spk_files'
        );

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
            'file_spk'      => $fileSpkPath,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2. BULK INSERT DETAIL & UNIT
        |--------------------------------------------------------------------------
        */

        $detailInsert = [];
        $unitInsert   = [];

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

        /*
        |--------------------------------------------------------------------------
        | 3. Dispatch Image Processing (queue)
        |--------------------------------------------------------------------------
        */

        $historyPaths = [];
        foreach ($request->file('history_image') as $file) {
            $historyPaths[] = $file->store('temp/history', 'public');
        }

        $kolasePaths = [];
        foreach (collect($request->file('images'))->pluck('foto_kolase') as $file) {
            $kolasePaths[] = $file->store('temp/kolase', 'public');
        }

        // Dispatch hanya kirim PATH
        ProcessSPKImages::dispatch(
            $spk->id,
            $historyPaths,
            $kolasePaths
        );

        /*
        |--------------------------------------------------------------------------
        | 4. TEKNISI SYNC
        |--------------------------------------------------------------------------
        */
        $spk->teknisi()->sync($validated['teknisi']);
        
        // dd('masuk sebelum commit');

        DB::commit();

        return redirect()
            ->route('formcreatespk')
            ->with('success', 'Data SPK berhasil disimpan');

    } catch (\Throwable $e) {

        DB::rollBack();

        dd($e->getMessage());
    }
}

    private function storeCompressedImage($file, $folder)
    {
        $manager = new ImageManager(new Driver());

        $image = $manager->read($file)->orient();

        // Resize jika lebih dari 1600px (hemat storage & RAM)
        if ($image->width() > 1600) {
            $image = $image->scale(width: 1600);
        }

        $filename = uniqid('', true) . '.webp';
        $path = $folder . '/' . $filename;

        // makeDirectory tidak perlu exists() dulu
        Storage::disk('public')->makeDirectory($folder);

        Storage::disk('public')->put(
            $path,
            $image->toWebp(75)->toString()
        );

        return $path;
    }
}