<?php

namespace App\Http\Controllers;

use App\Models\DetailAC;
use App\Models\Pengguna;
use App\Models\LogService;
use App\Models\Departement;
use Illuminate\Http\Request;
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
            'acdetail_ids'       => 'required|array|min:1',
            'acdetail_ids.*'     => 'required|exists:acdetail,id',

            'no_spk'             => 'required|string|max:10|digits:5',
            'tanggal'            => 'required|date',
            'waktu_mulai'        => 'required|date_format:H:i',
            'waktu_selesai'      => 'required|after:waktu_mulai|date_format:H:i',

            'jumlah_orang'       => 'required|integer|min:1',

            'teknisi'            => 'required|array|min:1',
            'teknisi.*'          => 'required|exists:pengguna,id',

            'keluhan'            => 'required|array|min:1',
            'keluhan.*'          => 'required|string',

            'jenis_pekerjaan'    => 'required|array|min:1',
            'jenis_pekerjaan.*'  => 'required|string',

            'kepada'             => 'required|string|max:100',
            'mengetahui'         => 'required|string|max:100',
            'hormat_kami'        => 'required|exists:pengguna,id',
            'pelaksana_ttd'      => 'required|exists:pengguna,id',

            'file_spk'           => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480',
        ], [

            // ===== AC =====
            'acdetail_ids.required'     => 'Jumlah AC wajib diisi.',
            'acdetail_ids.*.required'   => 'Nomor AC wajib dipilih.',
            'acdetail_ids.*.exists'     => 'Nomor AC tidak valid.',

            // ===== NOMOR SPK =====
            'no_spk.required'           => 'Nomor SPK wajib diisi.',
            'no_spk.digits'             => 'Nomor SPK harus terdiri dari 5 digit angka.',

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

            // ===== KELUHAN =====
            'keluhan.required'          => 'Keluhan wajib diisi.',
            'keluhan.*.required'        => 'Keluhan wajib diisi.',

            // ===== JENIS PEKERJAAN =====
            'jenis_pekerjaan.required'  => 'Jenis pekerjaan wajib diisi.',
            'jenis_pekerjaan.*.required'=> 'Jenis pekerjaan wajib diisi.',

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
            if ($request->hasFile('file_spk')){
                $filePath = $request->file('file_spk')->store('spk_files', 'public');
            } else {
                $filePath = null;
            }

            $spk = LogService::create([
                'no_spk'            => $validated['no_spk'],
                'tanggal'           => $validated['tanggal'],
                'waktu_mulai'       => $validated['waktu_mulai'],
                'waktu_selesai'     => $validated['waktu_selesai'],
                'jumlah_orang'      => $validated['jumlah_orang'],
                'kepada'            => $validated['kepada'],
                'mengetahui'        => $validated['mengetahui'],
                'hormat_kami'       => $validated['hormat_kami'],
                'pelaksana_ttd'     => $validated['pelaksana_ttd'],
                'file_spk'          => $filePath,
            ]);

            // Menyimpan detail AC beserta keluhan dan jenis pekerjaan
            $acData = [];
            foreach ($validated['acdetail_ids'] as $index => $acdetailId) {
                $acData[$acdetailId] = [
                    'keluhan' => $validated['keluhan'][$index],
                    'jenis_pekerjaan' => $validated['jenis_pekerjaan'][$index],
                ];
            }
            $spk->acdetail()->sync($acData);
            $spk->teknisi()->sync($validated['teknisi']);

            DB::commit();

            return redirect()->back()->with('success', 'Data SPK Berhasil Dikirim!, Terima Kasih.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi Kesalahan : ' . $e->getMessage()]);
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
