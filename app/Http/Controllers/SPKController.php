<?php

namespace App\Http\Controllers;

use App\Models\DetailAC;
use App\Models\Pengguna;
use App\Models\LogService;
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
        $teknisi    = Pengguna::where('role', 'Teknisi')->get();
        $admin      = Pengguna::where('role', 'Admin')->get();
        return view ('user.FormInputSPK', compact('acdetail', 'teknisi', 'admin'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_acdetail'       => 'required|exists:acdetail,id',
            'no_spk'            => 'required|string|max:10',
            'tanggal'           => 'required|date',
            'waktu_mulai'       => 'required',
            'waktu_selesai'     => 'required|after:waktu_mulai',
            'jumlah_orang'      => 'required|integer|min:1',
            'teknisi'           => 'required|array|min:1',
            'teknisi.*'         => 'exists:pengguna,id',
            'keluhan'           => 'required|string',
            'jenis_pekerjaan'   => 'required|string', 
            'kepada'            => 'required|string|max:100',
            'mengetahui'        => 'required|string|max:100',
            'hormat_kami'       => 'required|exists:pengguna,id',
            'pelaksana_ttd'     => 'required|exists:pengguna,id',
            'file_spk'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480',
        ], [
            'id_acdetail.required'      => 'Nomor AC wajib dipilih',
            'no_spk.required'           => 'Nomor SPK wajib diisi',
            'tanggal.required'          => 'Tanggal SPK wajib diisi',
            'waktu_mulai.required'      => 'Waktu mulai wajib diisi',
            'waktu_selesai.required'    => 'Waktu selesai wajib diisi',
            'waktu_selesai.after'       => 'Waktu selesai harus setelah waktu mulai',
            'jumlah_orang.required'     => 'Jumlah teknisi wajib diisi',
            'teknisi.required'          => 'Teknisi wajib dipilih',
            'keluhan.required'          => 'Keluhan wajib diisi',
            'jenis_pekerjaan.required'  => 'Jenis pekerjaan wajib diisi',
            'kepada.required'           => 'Kepada wajib diisi',
            'mengetahui.required'       => 'Mengetahui wajib diisi',
            'hormat_kami.required'      => 'Hormat kami wajib dipilih',
            'pelaksana_ttd.required'    => 'Pelaksana SPK wajib dipilih',
            'file_spk.required'         => 'File SPK wajib diunggah',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('file_spk')){
                $filePath = $request->file('file_spk')->store('spk_files', 'public');
            } else {
                $filePath = null;
            }

            $spk = LogService::create([
                'id_acdetail'       => $validated['id_acdetail'],
                'no_spk'            => $validated['no_spk'],
                'tanggal'           => $validated['tanggal'],
                'waktu_mulai'       => $validated['waktu_mulai'],
                'waktu_selesai'     => $validated['waktu_selesai'],
                'jumlah_orang'      => $validated['jumlah_orang'],
                'keluhan'           => $validated['keluhan'],
                'jenis_pekerjaan'   => $validated['jenis_pekerjaan'],
                'kepada'            => $validated['kepada'],
                'mengetahui'        => $validated['mengetahui'],
                'hormat_kami'       => $validated['hormat_kami'],
                'pelaksana_ttd'     => $validated['pelaksana_ttd'],
                'file_spk'          => $filePath,
            ]);

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
