<?php

namespace App\Http\Controllers;

use App\Models\DetailAC;
use App\Models\Pengguna;
use App\Models\LogService;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\AcHistoryImage;
use App\Models\LogServiceUnit;
use Illuminate\Support\Carbon;
use App\Models\LogServiceImage;
use Illuminate\Validation\Rule;
use App\Models\LogServiceDetail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminSPKController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.spk');
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
        $admin = Pengguna::where('role', 'Admin')->get();
        return view('admin.formtambahspk', compact('acdetail','departement','pengguna', 'teknisi', 'admin'));
    }
    public function getData()
    {
        // Ambil SPK beserta unit dan detail AC
        $data = LogService::with(['units.acdetail'])->select('log_service.*');

        return DataTables::of($data)
            ->addIndexColumn()

            // Kolom No AC, gabungkan semua AC terkait SPK
            ->addColumn('no_ac', function ($row) {
                // Pastikan units ada
                if ($row->units->count()) {
                    // Ambil nomor AC tiap unit
                    return $row->units->map(function ($unit) {
                        return $unit->acdetail->no_ac ?? '-';
                    })->filter()->join(', ');
                }
                return '-';
            })

            // Kolom aksi (edit, delete, detail)
            ->addColumn('aksi', function ($row) {
                return '
                <div class="aksi-btn">
                    <a href="/admin/spk/'.$row->id.'/edit" class="btn btn-md btn-success">
                        <i data-feather="edit"></i> <strong>Edit</strong>
                    </a>

                    <form action="/admin/spk/'.$row->id.'" method="POST" class="d-inline form-delete">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn btn-md btn-danger">
                            <i data-feather="trash-2"></i> <strong>Hapus</strong>
                        </button>
                    </form>

                    <a href="/admin/spk/detail/'.$row->id.'" class="btn btn-md btn-secondary">
                        <i data-feather="eye"></i> <strong>Detail</strong>
                    </a>
                </div>';
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
            'history_image.*'           => 'required|image|mimes:jpg,jpeg,png|max:10240',

            // ===== Image Before & After =====
            'images' => 'nullable|array',

            'images.*.foto_kolase'      => 'nullable|image|mimes:jpg,jpeg,png|max:10240',

            'kepada'                    => 'required|string|max:100',
            'mengetahui'                => 'required|string|max:100',
            'hormat_kami'               => 'required|exists:pengguna,id',
            'pelaksana_ttd'             => 'required|exists:pengguna,id',

            'file_spk'                  => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480',
        ], [

            // ===== AC =====
            'acdetail_ids.required'     => 'Jumlah AC wajib diisi.',
            'acdetail_ids.*.required'   => 'Nomor AC wajib dipilih.',
            'acdetail_ids.*.exists'     => 'Nomor AC tidak valid.',

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
            'history_image.*.mimes'      => 'Kartu history harus JPG, JPEG, atau PNG.',
            'history_image.*.max'        => 'Ukuran kartu history maksimal 10MB.',

            // ===== IMAGE BEFORE & AFTER =====
            'foto_kolase.image'         => 'Foto Kolase harus berupa gambar.',
            'foto_kolase.max'           => 'Ukuran Foto Kolase maksimal 10 MB.',

            // ===== BAGIAN ADMIN =====
            'kepada.required'           => 'Bagian “Kepada” wajib diisi.',
            'mengetahui.required'       => 'Bagian “Mengetahui” wajib diisi.',
            'hormat_kami.required'      => 'Hormat Kami wajib dipilih.',
            'pelaksana_ttd.required'    => 'Pelaksana SPK wajib dipilih.',

            // ===== FILE SPK =====
            'file_spk.required'         => 'File SPK wajib diunggah.',
            'file_spk.mimes'            => 'Tipe file harus berupa PDF, JPG, JPEG, atau PNG.',
            'file_spk.max'              => 'Ukuran file SPK maksimal adalah 20MB.',
        ]);

        DB::beginTransaction();

        try {
            // ================= SPK UTAMA =================
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
                'file_spk'      => $request->file('file_spk')->store('spk_files', 'public'),
            ]);

            // ================= LOOP SETIAP AC =================
            foreach ($validated['acdetail_ids'] as $i => $acdetailId) {

                // ---- 1. LOG SERVICE DETAIL (keluhan & jenis pekerjaan) ----
                LogServiceDetail::create([
                    'log_service_id' => $spk->id,
                    'acdetail_id'    => $acdetailId,
                    'keluhan'        => $validated['keluhan'][$i] ?? null,
                    'jenis_pekerjaan'=> $validated['jenis_pekerjaan'][$i] ?? null,
                ]);

                // ---- 2. LOG SERVICE UNIT (untuk relasi gambar) ----
                $unit = LogServiceUnit::create([
                    'log_service_id' => $spk->id,
                    'acdetail_id'    => $acdetailId,
                ]);

                // ---- 3. AC HISTORY IMAGE ----
                if ($request->hasFile("history_image.$i")) {
                    $path = $request->file("history_image.$i")
                        ->store('spk_images/kartu_history', 'public');

                    AcHistoryImage::create([
                        'log_service_id' => $spk->id,
                        'acdetail_id'    => $acdetailId,
                        'image_path'     => $path,
                    ]);
                }

                // ---- 4. BEFORE & AFTER IMAGES ----
                if ($request->hasFile("foto_kolase.$i")) {
                    $path = $request->file("foto_kolase.$i")
                        ->store('spk_images/kolase', 'public');

                        LogServiceImage::create([
                            'log_service_unit_id' => $unit->id,
                            'image_path'          => $path,
                        ]);
                    }
            }

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
            'units.acdetail',       // nomor AC
            'units.detail',         // keluhan & jenis_pekerjaan
            'units.images',         // foto before/after
            'units.historyImages',  // kartu history
            'teknisi',              // teknisi
        ])->findOrFail($id);

        $acdetail = DetailAC::all();
        $departement = Departement::all();
        $pengguna = Pengguna::all();
        $admin = Pengguna::where('role', 'Admin')->get();
        $teknisi = Pengguna::where('role', 'Teknisi')->get();

        // ==== Persiapkan existing data untuk JS ====
        $existingAcData = $spk->units->map(function($unit){
            
        $fotokolase = $unit->images->first()?->image_path ?? '';

            return [
                'unit_id'         => $unit->id, // kalau mau simpan
                'acdetail_id'     => $unit->acdetail_id, // 🔥 INI YANG PENTING
                'no_ac'           => $unit->acdetail->no_ac ?? '',
                'keluhan'         => $unit->detail->keluhan ?? '',
                'jenis_pekerjaan' => $unit->detail->jenis_pekerjaan ?? '',
                'history_image'   => $unit->historyImages->first()?->image_path ?? '',
                'foto_kolase'     => $fotokolase,
            ];
        });
        // dd($existingAcData->toArray());

        return view('admin.formeditspk', compact(
            'spk', 'acdetail', 'departement', 'pengguna', 'admin', 'teknisi', 'existingAcData'
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

            'history_image'      => 'nullable|array',
            'history_image.*'    => 'nullable|image|mimes:jpg,jpeg,png|max:10240',

            'foto_kolase'        => 'nullable|array',
            'foto_kolase.*'        => 'nullable|image|mimes:jpg,jpeg,png|max:10240',

            'kepada'             => 'required|string|max:100',
            'mengetahui'         => 'required|string|max:100',
            'hormat_kami'        => 'required|exists:pengguna,id',
            'pelaksana_ttd'      => 'required|exists:pengguna,id',

            'file_spk'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:20480',
        ]);

        DB::beginTransaction();

        try {

            /* ================= UPDATE FILE SPK ================= */
            if ($request->hasFile('file_spk')) {

                if ($spk->file_spk && Storage::disk('public')->exists($spk->file_spk)) {
                    Storage::disk('public')->delete($spk->file_spk);
                }

                $spk->file_spk = $request->file('file_spk')
                    ->store('spk_files', 'public');
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
            $oldUnits = $spk->units->keyBy('acdetail_id');

            $usedUnitIds = [];

            foreach ($validated['acdetail_ids'] as $i => $acdetailId) {

                /* ===== DETAIL ===== */
                LogServiceDetail::updateOrCreate(
                    [
                        'log_service_id' => $spk->id,
                        'acdetail_id'    => $acdetailId,
                    ],
                    [
                        'keluhan'         => $validated['keluhan'][$i] ?? null,
                        'jenis_pekerjaan' => $validated['jenis_pekerjaan'][$i] ?? null,
                    ]
                );

                /* ===== UNIT ===== */
                if (isset($oldUnits[$acdetailId])) {
                    $unit = $oldUnits[$acdetailId]; // pakai lama
                } else {
                    $unit = LogServiceUnit::updateOrCreate([
                        'log_service_id' => $spk->id,
                        'acdetail_id'    => $acdetailId,
                    ]);
                }

                $usedUnitIds[] = $unit->id;

                /* ================= HISTORY IMAGE ================= */
                if ($request->hasFile("history_image.$i")) {

                    $path = $request->file("history_image.$i")
                        ->store('spk_images/kartu_history', 'public');

                    $history = AcHistoryImage::where('log_service_id', $spk->id)
                        ->where('acdetail_id', $acdetailId)
                        ->first();

                    if ($history) {

                        // Hapus file lama saja
                        if (Storage::disk('public')->exists($history->image_path)) {
                            Storage::disk('public')->delete($history->image_path);
                        }

                        // UPDATE record (ID tetap)
                        $history->update([
                            'image_path' => $path,
                        ]);

                    } else {

                        // Kalau belum ada baru create
                        AcHistoryImage::create([
                            'log_service_id' => $spk->id,
                            'acdetail_id'    => $acdetailId,
                            'image_path'     => $path,
                        ]);
                    }
                }

                /* ================= FOTO KOLOSA ================= */
                if ($request->hasFile("foto_kolase.$i")) {

                    // Cek apakah sudah ada foto lama
                    $existingImage = LogServiceImage::where('log_service_unit_id', $unit->id)
                        ->first();

                    if ($existingImage) {

                        // Hapus file lama dari storage
                        if (Storage::disk('public')->exists($existingImage->image_path)) {
                            Storage::disk('public')->delete($existingImage->image_path);
                        }

                        // Hapus record lama
                        $existingImage->delete();
                    }

                    // Simpan file baru
                    $path = $request->file("foto_kolase.$i")
                        ->store("spk_images/kolase", 'public');

                    LogServiceImage::updateOrCreate(
                    [
                        'log_service_unit_id' => $unit->id,
                    ], 
                    [
                        'image_path'          => $path,
                    ]);
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
 

            /* ================= SYNC TEKNISI ================= */
            $spk->teknisi()->sync($validated['teknisi']);

            DB::commit();

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
                'units.acdetail',
            ])->findOrFail($id);

            /* ================= DELETE FILE SPK ================= */
            if ($spk->file_spk && Storage::disk('public')->exists($spk->file_spk)) {
                Storage::disk('public')->delete($spk->file_spk);
            }

            /* ================= DELETE HISTORY IMAGE ================= */
            $histories = AcHistoryImage::where('log_service_id', $spk->id)->get();

            foreach ($histories as $history) {
                if (Storage::disk('public')->exists($history->image_path)) {
                    Storage::disk('public')->delete($history->image_path);
                }
                $history->delete();
            }

            /* ================= DELETE UNIT IMAGES (KOLOSA) ================= */
            foreach ($spk->units as $unit) {

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

    public function detail($id)
    {
        $spk = LogService::with([
            'units.acdetail.ruangan.departement', // untuk menampilkan info AC dan ruangan
            'details.acdetail',
            'units.images',
            'units.historyImages'
        ])->findOrFail($id);

        return view('admin.detailspk', compact('spk'));
    }

    // public function generatePdf($id)
    // {
    //     $spk = LogService::with(['pelaksana','hormatKamiUser'])->findOrFail($id);

    //     $pdf = \PDF::loadView('admin.spkprint', compact('spk'))
    //             ->setPaper('A4', 'portrait');
        
    //     // jika link pakai ?download=1 maka download
    //     if (request()->has('download')) {
    //         return $pdf->download('SPK-'.$spk->no_spk.'.pdf');
    //     }

    //     return $pdf->download('SPK-'.$spk->no_spk.'.pdf');
    // }
}
