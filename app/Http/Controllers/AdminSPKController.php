<?php

namespace App\Http\Controllers;

use App\Models\DetailAC;
use App\Models\Pengguna;
use App\Models\LogService;
use Illuminate\Http\Request;
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
        $pengguna = Pengguna::all();
        return view('admin.formtambahspk', compact('acdetail','pengguna'));
    }
    public function getData()
    {
        // if (! $request->ajax()) {
        //     abort(404);
        // }

        $data = LogService::with(['acdetail'])->select('log_service.*');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('no_ac', function($row) {
                return $row->acdetail ? $row->acdetail->no_ac : '-';
            })
            ->addColumn('aksi', function($row){
                return '
                    <a href="/admin/spk/'.$row->id.'/edit" class="btn btn-md btn-success"><i class="align-middle" data-feather="edit"></i><strong> Edit</strong></a>
                    <form action="/admin/spk/'.$row->id.'" method="POST" class="d-inline form-delete">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn btn-md btn-danger">
                            <i class="align-middle" data-feather="trash-2"></i><strong> Hapus</strong>
                        </button>
                    </form>
                    <a href="/admin/spk/detail/'.$row->id.'" class="btn btn-md btn-secondary"><i class="align-middle" data-feather="eye"></i><strong> Detail</strong></a>
                    ';
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
            'hormat_kami'       => 'required|string|max:100',
            'pelaksana_ttd'     => 'required|exists:pengguna,id',
            'file_spk'          => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
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

            return redirect()->route('admin.spk')->with('success', 'Data SPK Berhasil Disimpan');
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
        $spk = LogService::with(['acdetail', 'teknisi'])->findOrFail($id);
        $acdetail = DetailAC::all();
        $pengguna = Pengguna::all();

        return view('admin.formeditspk', compact('spk', 'acdetail', 'pengguna'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $spk = LogService::findOrFail($id);

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
            'hormat_kami'       => 'required|string|max:100',
            'pelaksana_ttd'     => 'required|exists:pengguna,id',
            'file_spk'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Handle file update
            if ($request->hasFile('file_spk')) {

                // Hapus file lama jika ada
                if ($spk->file_spk && \Storage::disk('public')->exists($spk->file_spk)) {
                    \Storage::disk('public')->delete($spk->file_spk);
                }

                // Upload file baru
                $filePath = $request->file('file_spk')->store('spk_files', 'public');
            } else {
                $filePath = $spk->file_spk; // Gunakan file lama
            }

            // Update data
            $spk->update([
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

            // Update teknisi pivot
            $spk->teknisi()->sync($validated['teknisi']);

            DB::commit();

            return redirect()->route('admin.spk')->with('success', 'Data SPK berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi Kesalahan : ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $spk = LogService::findOrFail($id);

        if ($spk->file_spk) {
            Storage::delete(['public/spk_files/' . $spk->file_spk]);
        }

        $spk->delete();

        return redirect()->route('admin.spk')->with('success', 'Data SPK berhasil dihapus!');
    }

    public function detail($id)
    {
        $spk = LogService::with(['acdetail', 'teknisi', 'pengguna'])->findOrFail($id);
        return view('admin.detailspk', compact('spk'));
    }
}
