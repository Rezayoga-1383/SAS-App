<?php

namespace App\Http\Controllers;

use App\Models\DetailAC;
use App\Models\Pengguna;
use App\Models\LogService;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\AcHistoryImage;
use App\Models\LogServiceUnit;
use App\Models\LogServiceImage;
use App\Models\LogServiceDetail;
use Illuminate\Support\Facades\DB;

class SPKController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $acdetail   = DetailAC::All();
        $departement= Departement::All();
        $teknisi    = Pengguna::where('role', 'Teknisi')->get();
        $admin      = Pengguna::where('role', 'Admin')->get();
        return view ('user.FormInputSPK', compact('acdetail', 'departement', 'teknisi', 'admin'));
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
                        'log_service_unit_id' => $unit->id,
                        'image_path'          => $path,
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
                ->route('formcreatespk')
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
