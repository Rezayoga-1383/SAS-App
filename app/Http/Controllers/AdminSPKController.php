<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessSPKImages;
use App\Jobs\ProcessUpdateSPKImages;
// use App\Models\AcHistoryImage;
use App\Models\Departement;
use App\Models\DetailAC;
use App\Models\LogService;
use App\Models\LogServiceDetail;
use App\Models\LogServiceImage;
use App\Models\LogServiceUnit;
use App\Models\Pengguna;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
// use App\Models\HppDetail;

class AdminSPKController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // public function pdftest()
    // {
    //     return view ('admin.spkpdf');
    // }
    public function index(Request $request)
    {
        return view('admin.spk');
    }

    public function exportPdf(Request $request)
    {
        $start  = $request->start_date;
        $end    = $request->end_date;
        $jenis  = $request->jenis_service;

        $query = LogServiceDetail::with([
            'logService.teknisi',
            'acdetail.ruangan.departement'
        ]);

        // ================= FILTER TANGGAL =================
        if ($start && $end) {
            $query->whereHas('logService', function ($q) use ($start, $end) {
                $q->whereBetween('tanggal', [$start, $end]);
            });
        }

        // ================= FILTER KATEGORI PEKERJAAN =================
        if (!empty($jenis)) {
            $query->where('kategori_pekerjaan', $jenis);
        }

        // ================= ORDER BERDASARKAN TANGGAL SPK =================
        $query->join('log_service', 'log_service.id', '=', 'log_service_detail.log_service_id')
            ->orderBy('log_service.tanggal', 'asc')
            ->select('log_service_detail.*');

        $data = $query->get();

        return Pdf::loadView('admin.spkpdf', [
                'data' => $data,
                'start_date' => $start,
                'end_date' => $end,
                'jenis_service' => $jenis,
            ])
            ->setPaper('a4', 'landscape')
            ->download('Data-SPK.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $acdetail = DetailAC::all();
        $departement = Departement::all();
        $pengguna = Pengguna::all();
        $teknisi = Pengguna::where('role', 'Teknisi')->get();
        $admin = Pengguna::whereIn('nama',['Siti Aliyatur Rofi Ah','Nurul'])->get();
        $kategoriPekerjaan = [
            'Cuci AC',
            'Perbaikan',
            'Cek AC',
            'Ganti Unit'
        ];

        return view('admin.formtambahspk', compact('acdetail','departement','pengguna', 'teknisi', 'admin', 'kategoriPekerjaan'));
    }

    public function getData(Request $request)
    {
        $query = LogService::with(['units.acdetail', 'details'])
                    ->select('log_service.*')
                    ->withCount('hppDetail')
                    ->where('status', LogService::STATUS_SELESAI);

        // FILTER TANGGAL (sesuai blade: start_date & end_date)
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        } elseif ($request->start_date) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        } elseif ($request->end_date) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // FILTER JENIS SERVICE (baru: jenis_service)
        if ($request->jenis_service) {
            $query->whereHas('details', function($q) use ($request) {
                $q->where('kategori_pekerjaan', $request->jenis_service);
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('no_ac', function ($row) {
                if ($row->units->count()) {
                    return $row->units->map(function ($unit) {
                        return $unit->acdetail->no_ac ?? '-';
                    })->filter()->join(', ');
                }
                return '-';
            })

            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex align-items-center justify-content-center gap-1 flex-wrap flex-md-nowrap">';

                $btn .= '<a href="/admin/spk/'.$row->id.'/edit" class="btn btn-md btn-success">
                            <i data-feather="edit"></i> Edit
                        </a>';
                
                $btn .= '<form action="/admin/spk/'.$row->id.'" method="POST" class="form-delete m-0">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-md btn-danger">
                                <i data-feather="trash-2"></i> Hapus
                            </button>
                        </form>';
                
                $btn .= '<a href="/admin/spk/detail/'.$row->id.'?from=spk" class="btn btn-md btn-secondary">
                            <i data-feather="eye"></i> Detail
                        </a>';
                
                if ($row->status == LogService::STATUS_SELESAI) {
                    $mode = $row->hpp_detail_count > 0 ? 'edit' : 'create';
                    $label = $row->hpp_detail_count > 0 ? 'Edit HPP' : 'Input HPP';
                    $btnClass = $row->hpp_detail_count > 0 ? 'btn-warning' : 'btn-primary';
                    $btn .= '<button class="btn btn-md '.$btnClass.' btn-hpp"
                                data-id="'.$row->id.'"
                                data-nospk="'.$row->no_spk.'"
                                data-mode="'.$mode.'">
                                <i data-feather="dollar-sign"></i> '.$label.'
                            </button>';
                }

                $btn .= '</div>';

                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);     
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'acdetail_ids'              => 'required|array|min:1',
            'acdetail_ids.*'            => 'required|exists:acdetail,id',

            'kategori_pekerjaan.*'        => 'required|in:Cuci AC,Perbaikan,Cek AC,Ganti Unit',

            'no_spk'                    => 'required|digits:4|unique:log_service,no_spk',
            'tanggal'                   => 'required|date',
            'waktu_mulai'               => 'required|date_format:H:i',
            'waktu_selesai'             => 'required|after:waktu_mulai|date_format:H:i',

            'jumlah_orang'              => 'required|integer|min:1',

            'teknisi'                   => 'required|array|min:1',
            'teknisi.*'                 => 'required|exists:pengguna,id',

            'jumlah_ac_input'           => 'required|integer|min:1',

            'keluhan'                   => 'required|array|min:1',
            'keluhan.*'                 => 'required|string',

            'jenis_pekerjaan'           => 'required|array|min:1',
            'jenis_pekerjaan.*'         => 'required|string',

            // ===== Image History =====
            'history_image'             => 'required|array',
            'history_image.*'           => 'required|image|mimes:jpg,jpeg|max:2048',

            // ===== Foto Kolase =====
            'images'                    => 'required|array|min:1',
            'images.*'                  => 'required|array',
            'images.*.foto_kolase'      => 'required|file|image|mimes:jpg,jpeg|max:2048',


            'kepada'                    => 'required|string|max:100',
            'mengetahui'                => 'required|string|max:100',
            'hormat_kami'               => 'required|exists:pengguna,id',
            'pelaksana_ttd'             => 'required|exists:pengguna,id',

            'file_spk'                  => 'required|file|mimes:pdf,jpg,jpeg|max:2048',
        ], [

            // ===== AC =====
            'acdetail_ids.required'     => 'Jumlah AC wajib diisi.',
            'acdetail_ids.*.required'   => 'Nomor AC wajib dipilih.',
            'acdetail_ids.*.exists'     => 'Nomor AC tidak valid.',

            'kategori_pekerjaan.*.required' => 'Kategori Pekerjaan wajib dipilih',

            // ===== NOMOR SPK =====
            'no_spk.required'           => 'Nomor SPK wajib diisi.',
            'no_spk.unique'             => 'Nomor SPK sudah digunakan.',
            'no_spk.digits'             => 'Nomor SPK harus terdiri dari 4 digit angka.',

            // ===== TANGGAL & WAKTU =====
            'tanggal.required'          => 'Tanggal SPK wajib diisi.',
            'waktu_mulai.required'      => 'Waktu mulai wajib diisi.',
            'waktu_selesai.required'    => 'Waktu selesai wajib diisi.',
            'waktu_selesai.after'       => 'Waktu selesai harus setelah waktu mulai.',

            // ===== TEKNISI =====
            'jumlah_orang.required'     => 'Jumlah teknisi wajib diisi.',
            'teknisi.required'          => 'Teknisi wajib dipilih.',
            'teknisi.*.required'        => 'Teknisi wajib dipilih.',
            'teknisi.*.exists'          => 'Teknisi tidak valid.',

            // ==== JUMLAH AC =====
            'jumlah_ac_input.required'  => 'Jumlah AC wajib diisi.',

            // ===== KELUHAN =====
            'keluhan.required'          => 'Keluhan wajib diisi.',
            'keluhan.*.required'        => 'Keluhan wajib diisi.',

            // ===== JENIS PEKERJAAN =====
            'jenis_pekerjaan.required'  => 'Jenis pekerjaan wajib diisi.',
            'jenis_pekerjaan.*.required'=> 'Jenis pekerjaan wajib diisi.',

            // =====Image History =====
            'history_image.required'     => 'Kartu history AC wajib diunggah.',
            'history_image.array'        => 'Format kartu history tidak valid.',
            'history_image.*.required'   => 'Kartu history AC wajib diunggah.',
            'history_image.*.image'      => 'File kartu history harus berupa gambar.',
            'history_image.*.mimes'      => 'Kartu history harus JPG, JPEG.',
            'history_image.*.max'        => 'Ukuran kartu history maksimal 2 MB.',

            // ===== IMAGE BEFORE & AFTER =====
            'images.*.foto_kolase.required'         => 'Foto Kolase wajib diunggah.',
            'images.*.foto_kolase.image'         => 'Foto Kolase harus berupa gambar.',
            'images.*.foto_kolase.mimes'         => 'Foto Kolase harus JPG, JPEG.',
            'images.*.foto_kolase.max'           => 'Ukuran Foto Kolase maksimal 2 MB.',

            // ===== BAGIAN ADMIN =====
            'kepada.required'           => 'Bagian “Kepada” wajib diisi.',
            'mengetahui.required'       => 'Bagian “Mengetahui” wajib diisi.',
            'hormat_kami.required'      => 'Hormat Kami wajib dipilih.',
            'pelaksana_ttd.required'    => 'Pelaksana SPK wajib dipilih.',

            // ===== FILE SPK =====
            'file_spk.required'         => 'File SPK wajib diunggah.',
            'file_spk.mimes'            => 'Tipe file harus berupa JPG, JPEG.',
            'file_spk.max'              => 'Ukuran file SPK maksimal adalah 2 MB.',
        ]);

        DB::beginTransaction();

        try {
            // ================= SPK UTAMA =================
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
                'file_spk'      => $fileSpkTemp,
            ]);

            // ================= LOOP SETIAP AC =================
            $detailInsert = [];
            $unitInsert = [];
            foreach ($validated['acdetail_ids'] as $i => $acdetailId) {
                $detailInsert[] = [
                    'log_service_id' => $spk->id,
                    'acdetail_id'    => $acdetailId,
                    'kategori_pekerjaan' => $validated['kategori_pekerjaan'][$i] ?? null,
                    'keluhan'            => $validated['keluhan'][$i] ?? null,
                    'jenis_pekerjaan'    => $validated['jenis_pekerjaan'][$i] ?? null,
                    'created_at'         => now(),
                    'updated_at'         => now(),
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

            $historyPaths = [];
            foreach ($request->file('history_image') as $file) {
                $historyPaths[] = $file->store('temp/history', 'public');
            }

            $kolasePaths = [];
            foreach (collect($request->file('images'))->pluck('foto_kolase') as $file) {
                $kolasePaths[] = $file->store('temp/kolase', 'public');
            }

            // Dispatch Job
            ProcessSPKImages::dispatch(
                $spk->id,
                $historyPaths,
                $kolasePaths,
                $fileSpkTemp
            );

            // ================= TEKNISI =================
            $spk->teknisi()->sync($validated['teknisi']);

            DB::commit();

            return redirect()
                ->route('admin.spk')
                ->with('success', 'Data SPK berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $spk = LogService::with([
            'units.acdetail',
            'units.images',
            'units.historyImages',
            'details', // 🔥 ini yang benar
            'teknisi',
        ])->findOrFail($id);

        $acdetail = DetailAC::all();
        $departement = Departement::all();
        $pengguna = Pengguna::all();
        $admin = Pengguna::whereIn('nama', ['Siti Aliyatur Rofi Ah', 'Nurul'])->get();
        $teknisi = Pengguna::where('role', 'Teknisi')->get();
        $kategoriPekerjaan = ['Cuci AC', 'Perbaikan', 'Cek AC', 'Ganti Unit'];

        // ==== Persiapkan existing data untuk JS ====
        $existingAcData = $spk->units->map(function($unit) use ($spk){
            $detail = $spk->details
                ->where('acdetail_id', $unit->acdetail_id)
                ->first();
            
        $fotokolase = $unit->images->first()?->image_path ?? '';

            return [
                'unit_id'         => $unit->id, 
                'acdetail_id'     => $unit->acdetail_id, 
                'no_ac'           => $unit->acdetail->no_ac ?? '',
                'kategori_pekerjaan' => $detail->kategori_pekerjaan ?? '',
                'keluhan'         => $detail->keluhan ?? '',
                'jenis_pekerjaan' => $detail->jenis_pekerjaan ?? '',
                'history_image'   => $unit->historyImages->first()?->image_path ?? '',
                'foto_kolase'     => $fotokolase,
            ];
        });
        // dd($existingAcData->toArray());

        return view('admin.formeditspk', compact(
            'spk', 'acdetail', 'departement', 'pengguna', 'admin', 'teknisi', 'existingAcData', 'kategoriPekerjaan'
        ));
    }


    /**
     * Update the specified resource in storage.
     */
    // ...existing code...
    public function update(Request $request, string $id)
    {
        $spk = LogService::with(['units.images'])->findOrFail($id);

        $validated = $request->validate([
            'acdetail_ids'       => 'required|array|min:1',
            'acdetail_ids.*'     => 'required|exists:acdetail,id',

            'unit_ids'   => 'nullable|array',
            'unit_ids.*' => 'nullable|exists:log_service_unit,id',

            'no_spk' => [
                'required',
                'digits:4',
                Rule::unique('log_service', 'no_spk')->ignore($id),
            ],

            'tanggal'            => 'required|date',
            'waktu_mulai'        => 'required',
            'waktu_selesai'      => 'required|after:waktu_mulai',

            'jumlah_orang'       => 'required|integer|min:1',

            'teknisi'            => 'required|array|min:1',
            'teknisi.*'          => 'required|exists:pengguna,id',

            'keluhan'            => 'required|array|min:1',
            'keluhan.*'          => 'required|string',

            'jenis_pekerjaan'    => 'required|array|min:1',
            'jenis_pekerjaan.*'  => 'required|string',

            'kategori_pekerjaan' => 'required|array|min:1',
            'kategori_pekerjaan.*' => 'required|in:Cuci AC,Perbaikan,Cek AC,Ganti Unit',

            'history_image'      => 'nullable|array',
            'history_image.*'    => 'nullable|image|mimes:jpg,jpeg|max:10240',

            'foto_kolase'        => 'nullable|array',
            'foto_kolase.*'      => 'nullable|image|mimes:jpg,jpeg|max:10240',

            'kepada'             => 'required|string|max:100',
            'mengetahui'         => 'required|string|max:100',
            'hormat_kami'        => 'required|exists:pengguna,id',
            'pelaksana_ttd'      => 'required|exists:pengguna,id',

            'file_spk'           => 'nullable|file|mimes:jpg,jpeg|max:10240',
        ]);

        DB::beginTransaction();

        try {

            // Create array temp
            $historyPathsToProcess = [];
            $kolasePathsToProcess = [];
            $fileSpkTemp = null;

            /* ================= UPDATE FILE SPK ================= */
            if ($request->hasFile('file_spk')) {
                // Di controller jangan hapus existing file spk dulu agar tetep tampil sampai job kelar
                $fileSpkTemp = $request->file('file_spk')->store('temp_spk_images', 'public');
            }

            /* ================= FORMAT WAKTU ================= */
            $waktuMulai = Carbon::parse($validated['waktu_mulai'])->format('H:i:s');
            $waktuSelesai = Carbon::parse($validated['waktu_selesai'])->format('H:i:s');

            /* ================= UPDATE DATA SPK ================= */
            $spk->update([
                'no_spk'        => $validated['no_spk'],
                'tanggal'       => $validated['tanggal'],
                'waktu_mulai'   => $waktuMulai,
                'waktu_selesai' => $waktuSelesai,
                'jumlah_orang'  => $validated['jumlah_orang'],
                'kepada'        => $validated['kepada'],
                'mengetahui'    => $validated['mengetahui'],
                'hormat_kami'   => $validated['hormat_kami'],
                'pelaksana_ttd' => $validated['pelaksana_ttd'],
            ]);

           /* ================= AMBIL DATA LAMA ================= */
            $oldUnits = $spk->units->keyBy('id');

            $usedUnitIds = [];
            $usedAcdetailIds = [];

            foreach ($validated['acdetail_ids'] as $i => $acdetailId) {

                $usedAcdetailIds[] = $acdetailId;
                /* ===== DETAIL ===== */
                $unitId = $request->unit_ids[$i] ?? null;

                if ($unitId) {

                    $detail = LogServiceDetail::where('log_service_id', $spk->id)
                        ->where('acdetail_id', $oldUnits[$unitId]->acdetail_id)
                        ->first();

                    if ($detail) {

                        $detail->update([
                            'acdetail_id'        => $acdetailId,
                            'kategori_pekerjaan' => $validated['kategori_pekerjaan'][$i] ?? null,
                            'keluhan'            => $validated['keluhan'][$i] ?? null,
                            'jenis_pekerjaan'    => $validated['jenis_pekerjaan'][$i] ?? null,
                        ]);
                    }

                } else {

                    LogServiceDetail::create([
                        'log_service_id'     => $spk->id,
                        'acdetail_id'        => $acdetailId,
                        'kategori_pekerjaan' => $validated['kategori_pekerjaan'][$i] ?? null,
                        'keluhan'            => $validated['keluhan'][$i] ?? null,
                        'jenis_pekerjaan'    => $validated['jenis_pekerjaan'][$i] ?? null,
                    ]);
                }

                /* ===== UNIT ===== */
                $unitId = $request->unit_ids[$i] ?? null;

                if ($unitId && isset($oldUnits[$unitId])) {

                    // pakai unit lama
                    $unit = $oldUnits[$unitId];

                    $unit->update([
                        'acdetail_id' => $acdetailId
                    ]);

                } else {

                    // unit baru
                    $unit = LogServiceUnit::create([
                        'log_service_id' => $spk->id,
                        'acdetail_id' => $acdetailId,
                    ]);
                }

                $usedUnitIds[] = $unit->id;

                /* ================= HISTORY IMAGE ================= */
                if ($request->hasFile("history_image.$i")) {
                    $path = $request->file("history_image.$i")
                        ->store('temp_spk_images', 'public');
                    $historyPathsToProcess[$unit->id] = $path;
                }

                /* ================= HAPUS FOTO KOLOSA (TOMBOL HAPUS) ================= */
                if (isset($request->hapus_foto_kolase[$i]) && $request->hapus_foto_kolase[$i] == 1) {

                    $existingImage = LogServiceImage::where('log_service_unit_id', $unit->id)
                        ->first();

                    if ($existingImage) {

                        if (Storage::disk('public')->exists($existingImage->image_path)) {
                            Storage::disk('public')->delete($existingImage->image_path);
                        }

                        $existingImage->delete();
                    }
                }

                /* ================= FOTO KOLOSA ================= */
                if ($request->hasFile("foto_kolase.$i")) {
                    $path = $request->file("foto_kolase.$i")->store('temp_spk_images', 'public');
                    $kolasePathsToProcess[$unit->id] = $path;
                }
            }

            // ================= HAPUS UNIT YANG DIHAPUS DARI FORM =================
            $unitsToDelete = LogServiceUnit::where('log_service_id', $spk->id)
                ->whereNotIn('id', $usedUnitIds)
                ->get();

            foreach ($unitsToDelete as $unit) {

                foreach ($unit->images as $image) {

                    if (Storage::disk('public')->exists($image->image_path)) {
                        Storage::disk('public')->delete($image->image_path);
                    }

                    $image->delete();
                }

                $unit->delete();
            }

            LogServiceDetail::where('log_service_id', $spk->id)
                ->whereNotIn('acdetail_id', $usedAcdetailIds)
                ->delete();
 

            /* ================= SYNC TEKNISI ================= */
            $spk->teknisi()->sync($validated['teknisi']);

            DB::commit();

            // Dispatch job jika ada gambar/file yang diupload
            if (!empty($historyPathsToProcess) || !empty($kolasePathsToProcess) || $fileSpkTemp) {
                ProcessUpdateSPKImages::dispatch(
                    $spk->id,
                    $historyPathsToProcess,
                    $kolasePathsToProcess,
                    $fileSpkTemp
                );
            }

            return redirect()
                ->route('admin.spk')
                ->with('success', 'Data SPK berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {

            $spk = LogService::with([
                'units.images',
                'units.historyImages',
                'units.acdetail',
            ])->findOrFail($id);

            /* ================= DELETE FILE SPK ================= */
            if ($spk->file_spk && Storage::disk('public')->exists($spk->file_spk)) {
                Storage::disk('public')->delete($spk->file_spk);
            }

            /* ================= DELETE UNIT IMAGES ================= */
            foreach ($spk->units as $unit) {

                // Delete History Images
                foreach ($unit->historyImages as $history) {
                    if (Storage::disk('public')->exists($history->image_path)) {
                        Storage::disk('public')->delete($history->image_path);
                    }
                    $history->delete();
                }

                // Delete Kolase Images
                foreach ($unit->images as $image) {
                    if (Storage::disk('public')->exists($image->image_path)) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                    $image->delete();
                }

                $unit->delete();
            }

            /* ================= DELETE DETAIL ================= */
            LogServiceDetail::where('log_service_id', $spk->id)->delete();

            /* ================= DETACH TEKNISI ================= */
            $spk->teknisi()->detach();

            /* ================= DELETE SPK ================= */
            $spk->delete();

            DB::commit();

            return redirect()
                ->route('admin.spk')
                ->with('success', 'Data SPK berhasil dihapus!');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->route('admin.spk')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function detail(Request $request, $id)
    {
        $spk = LogService::with([
            'units.acdetail.ruangan.departement', 
            'units.images',
            'units.historyImages',
            'details',
            'hppDetail'
        ])->findOrFail($id);

        $from = $request->query('from');

        return view('admin.detailspk', compact('spk', 'from'));
    }

    public function downloadpdf($id)
    {
        $spk = LogService::with([
            'units.acdetail.ruangan.departement',
            'details',
            'teknisi',
            'pelaksana',
            'hormatKamiUser'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('admin.spkdetaildownload', [
            'spk' => $spk
        ])->setPaper('A4', 'portrait');

        return $pdf->download('SPK-'.$spk->no_spk.'.pdf');
    }

    public function storeHpp(Request $request, $id)
    {
        $request->validate([
            'hpp' => 'required|array',
            'hpp.*.keterangan' => 'required|string',
            'hpp.*.nominal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $spk = LogService::findOrFail($id);

            foreach ($request->hpp as $item) {
                $spk->hppDetail()->create([
                    'keterangan' => $item['keterangan'],
                    'nominal' => $item['nominal'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'HPP berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal simpan HPP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateHpp(Request $request, $id)
    {
        $request->validate([
            'hpp' => 'required|array',
            'hpp.*.keterangan' => 'required|string',
            'hpp.*.nominal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try{
            $spk = LogService::findOrFail($id);

            $spk->hppDetail()->delete();

            foreach ($request->hpp as $item) {
                $spk->hppDetail()->create([
                    'keterangan' => $item['keterangan'],
                    'nominal' => $item['nominal'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'HPP berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'Error',
                'message' => 'Gagal update HPP',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getHpp($id)
    {
        try {
            $spk = LogService::with('hppDetail')->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $spk->hppDetail
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data HPP'
            ], 500);
        }
    }
}