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
                <div class="aksi-btn">
                    <a href="/admin/spk/'.$row->id.'/edit" class="btn btn-md btn-success"><i class="align-middle" data-feather="edit"></i><strong> Edit</strong></a>
                    <form action="/admin/spk/'.$row->id.'" method="POST" class="d-inline form-delete">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn btn-md btn-danger">
                            <i class="align-middle" data-feather="trash-2"></i><strong> Hapus</strong>
                        </button>
                    </form>
                    <a href="/admin/spk/detail/'.$row->id.'" class="btn btn-md btn-secondary"><i class="align-middle" data-feather="eye"></i><strong> Detail</strong></a>
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
            'acdetail_ids'       => 'required|array|min:1',
            'acdetail_ids.*'     => 'required|exists:acdetail,id',

            'no_spk'             => 'required|digits:5|unique:log_service,no_spk',
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
            
            'before_image'       => 'required|image|mimes:jpg,jpeg,png|max:10240',
            'after_image'        => 'required|image|mimes:jpg,jpeg,png|max:10240',

        ], [

            // ===== AC =====
            'acdetail_ids.required'     => 'Jumlah AC wajib diisi.',
            'acdetail_ids.*.required'   => 'Nomor AC wajib dipilih.',
            'acdetail_ids.*.exists'     => 'Nomor AC tidak valid.',

            // ===== NOMOR SPK =====
            'no_spk.required'           => 'Nomor SPK wajib diisi.',
            'no_spk.unique'             => 'Nomor SPK sudah digunakan.',
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

            // ===== Image Before & After =====
            'before_image.required'     => 'Gambar wajib diunggah.',
            'before_image.mimes'        => 'Tipe gambar sebelum aksi harus berupa JPG, JPEG, atau PNG.',
            'before_image.max'          => 'Ukuran gambar sebelum aksi maksimal adalah 10MB.',

            'after_image.required'      => 'Gambar wajib diunggah.',
            'after_image.mimes'         => 'Tipe gambar sesudah aksi harus berupa JPG, JPEG, atau PNG.',
            'after_image.max'           => 'Ukuran gambar sesudah aksi maksimal adalah 10MB.',
        ]);

        $filePath       = null;
        $beforeImagePath = null;
        $afterImagePath  = null;

        DB::beginTransaction();

        try {
            if ($request->hasFile('file_spk')) {
                $filePath = $request->file('file_spk')->store('spk_files', 'public');
            }

            if ($request->hasFile('before_image')) {
                $beforeImagePath = $request->file('before_image')
                    ->store('spk_images/before', 'public');
            }

            if ($request->hasFile('after_image')) {
                $afterImagePath = $request->file('after_image')
                    ->store('spk_images/after', 'public');
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
                'before_image'      => $beforeImagePath,
                'after_image'       => $afterImagePath,
            ]);

            // ================= SYNC AC DETAIL =================
            $acData = [];
            foreach ($validated['acdetail_ids'] as $index => $acdetailId) {
                $acData[$acdetailId] = [
                    'keluhan'         => $validated['keluhan'][$index],
                    'jenis_pekerjaan' => $validated['jenis_pekerjaan'][$index],
                ];
            }

            $spk->acdetail()->sync($acData);
            $spk->teknisi()->sync($validated['teknisi']);

            DB::commit();

            return redirect()
                ->route('admin.spk')
                ->with('success', 'Data SPK berhasil disimpan');

        } catch (\Exception $e) {

            DB::rollBack();

            // ================= CLEANUP FILE =================
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }

            if ($beforeImagePath) {
                Storage::disk('public')->delete($beforeImagePath);
            }

            if ($afterImagePath) {
                Storage::disk('public')->delete($afterImagePath);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
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
            'before_image'       => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'after_image'        => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
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
            /** ================= FILE SPK ================= */
            $fileSpkPath = $spk->file_spk;
            if ($request->hasFile('file_spk')) {
                if ($fileSpkPath && Storage::disk('public')->exists($fileSpkPath)) {
                    Storage::disk('public')->delete($fileSpkPath);
                }
                $fileSpkPath = $request->file('file_spk')->store('spk_files', 'public');
            }

            /** ================= BEFORE IMAGE ================= */
            $beforeImagePath = $spk->before_image;
            if ($request->hasFile('before_image')) {
                if ($beforeImagePath && Storage::disk('public')->exists($beforeImagePath)) {
                    Storage::disk('public')->delete($beforeImagePath);
                }
                $beforeImagePath = $request->file('before_image')->store('spk_images/before', 'public');
            }

            /** ================= AFTER IMAGE ================= */
            $afterImagePath = $spk->after_image;
            if ($request->hasFile('after_image')) {
                if ($afterImagePath && Storage::disk('public')->exists($afterImagePath)) {
                    Storage::disk('public')->delete($afterImagePath);
                }
                $afterImagePath = $request->file('after_image')->store('spk_images/after', 'public');
            }

            /** ================= UPDATE SPK ================= */
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
                'file_spk'      => $fileSpkPath,
                'before_image'  => $beforeImagePath,
                'after_image'   => $afterImagePath,
            ]);

            /** ================= SYNC AC + PIVOT ================= */
            $acPivot = [];
            foreach ($validated['acdetail_ids'] as $index => $acId) {
                $acPivot[$acId] = [
                    'keluhan'         => $validated['keluhan'][$index],
                    'jenis_pekerjaan' => $validated['jenis_pekerjaan'][$index],
                ];
            }
            $spk->acdetail()->sync($acPivot);

            /** ================= SYNC TEKNISI ================= */
            $spk->teknisi()->sync($validated['teknisi']);

            DB::commit();
            return redirect()->route('admin.spk')->with('success', 'Data SPK berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $spk = LogService::findOrFail($id);

            /** ================= DELETE FILE SPK ================= */
            if ($spk->file_spk && Storage::disk('public')->exists($spk->file_spk)) {
                Storage::disk('public')->delete($spk->file_spk);
            }

            /** ================= DELETE BEFORE IMAGE ================= */
            if ($spk->before_image && Storage::disk('public')->exists($spk->before_image)) {
                Storage::disk('public')->delete($spk->before_image);
            }

            /** ================= DELETE AFTER IMAGE ================= */
            if ($spk->after_image && Storage::disk('public')->exists($spk->after_image)) {
                Storage::disk('public')->delete($spk->after_image);
            }

            /** ================= DETACH RELATION ================= */
            $spk->acdetail()->detach();
            $spk->teknisi()->detach();

            /** ================= DELETE SPK ================= */
            $spk->delete();

            DB::commit();
            return redirect()->route('admin.spk')->with('success', 'Data SPK berhasil dihapus!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.spk')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function detail($id)
    {
        $spk = LogService::with(['acdetail','acdetail.ruangan.departement','teknisi', 'pelaksana', 'hormatKamiUser'])->findOrFail($id);
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
