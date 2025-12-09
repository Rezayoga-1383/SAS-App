<?php

namespace App\Http\Controllers;

use App\Models\DetailAC;
use App\Models\Pengguna;
use App\Models\LogService;
use App\Models\Departement;
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
        $departement = Departement::all();
        $pengguna = Pengguna::all();
        $teknisi = Pengguna::where('role', 'Teknisi')->get();
        $admin = Pengguna::where('role', 'Admin')->get();
        return view('admin.formtambahspk', compact('acdetail','departement','pengguna', 'teknisi', 'admin'));
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
                return $row->acdetail->pluck('no_ac')->join (', ');
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

            return redirect(route('admin.spk'))->with('success', 'Data SPK Berhasil Dikirim!, Terima Kasih.');
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
        $departement = Departement::all();
        $pengguna = Pengguna::all();
        $admin = Pengguna::where('role', 'Admin')->get();
        $teknisi = Pengguna::where('role', 'Teknisi')->get();

        return view('admin.formeditspk', compact('spk', 'acdetail', 'departement', 'pengguna', 'admin', 'teknisi'));

    }

    /**
     * Update the specified resource in storage.
     */
    // ...existing code...
    public function update(Request $request, string $id)
    {
        $spk = LogService::findOrFail($id);

        $validated = $request->validate([
            'acdetail_ids'        => 'required|array|min:1',
            'acdetail_ids.*'      => 'exists:acdetail,id',
            'no_spk'              => 'required|string|max:10',
            'tanggal'             => 'required|date',
            'waktu_mulai'         => 'required',
            'waktu_selesai'       => 'required|after:waktu_mulai',
            'jumlah_orang'        => 'required|integer|min:1',
            'teknisi'             => 'required|array|min:1',
            'teknisi.*'           => 'exists:pengguna,id',
            'keluhan'             => 'required|array|min:1',
            'keluhan.*'           => 'required|string',
            'jenis_pekerjaan'     => 'required|array|min:1',
            'jenis_pekerjaan.*'   => 'required|string',
            'kepada'              => 'required|string|max:100',
            'mengetahui'          => 'required|string|max:100',
            'hormat_kami'         => 'required|string|max:100',
            'pelaksana_ttd'       => 'required|exists:pengguna,id',
            'file_spk'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'acdetail_ids.required'    => 'Nomor AC wajib dipilih',
            'no_spk.required'          => 'Nomor SPK wajib diisi',
            'tanggal.required'         => 'Tanggal SPK wajib diisi',
            'waktu_mulai.required'     => 'Waktu mulai wajib diisi',
            'waktu_selesai.required'   => 'Waktu selesai wajib diisi',
            'waktu_selesai.after'      => 'Waktu selesai harus setelah waktu mulai',
            'jumlah_orang.required'    => 'Jumlah teknisi wajib diisi',
            'teknisi.required'         => 'Teknisi wajib dipilih',
            'keluhan.required'         => 'Keluhan wajib diisi untuk setiap AC',
            'jenis_pekerjaan.required' => 'Jenis pekerjaan wajib diisi untuk setiap AC',
            'kepada.required'          => 'Kepada wajib diisi',
            'mengetahui.required'      => 'Mengetahui wajib diisi',
            'hormat_kami.required'     => 'Hormat kami wajib diisi',
            'pelaksana_ttd.required'   => 'Pelaksana SPK wajib dipilih',
        ]);

        DB::beginTransaction();

        try {
            // handle file update
            if ($request->hasFile('file_spk')) {
                if ($spk->file_spk && \Storage::disk('public')->exists($spk->file_spk)) {
                    \Storage::disk('public')->delete($spk->file_spk);
                }
                $filePath = $request->file('file_spk')->store('spk_files', 'public');
            } else {
                $filePath = $spk->file_spk;
            }

            // update main spk (keluhan/jenis_pekerjaan moved to pivot)
            $spk->update([
                'no_spk'        => $validated['no_spk'],
                'tanggal'       => $validated['tanggal'],
                'waktu_mulai'   => $validated['waktu_mulai'],
                'waktu_selesai' => $validated['waktu_selesai'],
                'jumlah_orang'  => $validated['jumlah_orang'],
                'kepada'        => $validated['kepada'],
                'mengetahui'    => $validated['mengetahui'],
                'hormat_kami'   => $validated['hormat_kami'],
                'pelaksana_ttd' => $validated['pelaksana_ttd'],
                'file_spk'      => $filePath,
            ]);

            // sync ACs with pivot data (keluhan, jenis_pekerjaan)
            $acPivot = [];
            foreach ($validated['acdetail_ids'] as $index => $acId) {
                $acPivot[$acId] = [
                    'keluhan'         => $validated['keluhan'][$index] ?? '',
                    'jenis_pekerjaan' => $validated['jenis_pekerjaan'][$index] ?? '',
                    'updated_at'      => now(),
                    'created_at'      => now(),
                ];
            }
            $spk->acdetail()->sync($acPivot);

            // sync teknisi pivot
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
        try {
            $spk = LogService::findOrFail($id);

            // Delete file if exists
            if ($spk->file_spk && Storage::disk('public')->exists($spk->file_spk)) {
                Storage::disk('public')->delete($spk->file_spk);
            }

            // Detach relationships sebelum delete (untuk many-to-many)
            $spk->acdetail()->detach();
            $spk->teknisi()->detach();

            // Delete SPK record
            $spk->delete();

            return redirect()->route('admin.spk')->with('success', 'Data SPK berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.spk')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function detail($id)
    {
        $spk = LogService::with(['acdetail','acdetail.ruangan.departement','teknisi', 'pelaksana', 'hormatKamiUser'])->findOrFail($id);
        return view('admin.detailspk', compact('spk'));
    }

    public function generatePdf($id)
    {
        $spk = LogService::with(['pelaksana','hormatKamiUser'])->findOrFail($id);

        $pdf = \PDF::loadView('admin.spkprint', compact('spk'))
                ->setPaper('A4', 'portrait');
        
        // jika link pakai ?download=1 maka download
        if (request()->has('download')) {
            return $pdf->download('SPK-'.$spk->no_spk.'.pdf');
        }

        return $pdf->download('SPK-'.$spk->no_spk.'.pdf');
    }
}
