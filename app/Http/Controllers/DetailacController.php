<?php

namespace App\Http\Controllers;

use App\Models\MerkAC;
use App\Models\JenisAC;
use App\Models\Ruangan;
use App\Models\DetailAC;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DetailacController extends Controller
{
    public function index()
    {
        return view('admin.detail-ac');
    }

    public function getData(Request $request)
    {
        if (! $request->ajax()) {
        abort(404); // tampilkan halaman not found
        }

        $data = DetailAC::with(['merkac', 'jenisac', 'ruangan'])->select('acdetail.*');

        return DataTables::of($data)
            ->addIndexColumn() // kolom No otomatis
            ->addColumn('nama_merkac', function($row) {
                return $row->merkac ? $row->merkac->nama_merk : '-';
            })
            ->addColumn('nama_jenisac', function($row) {
                return $row->jenisac ? $row->jenisac->nama_jenis : '-';
            })
            ->addColumn('nama_ruangan', function($row) {
                $ruangan = $row->ruangan ? $row->ruangan->nama_ruangan : '-';
                $departement = $row->ruangan && $row->ruangan->departement ? $row->ruangan->departement->nama_departement : '-';
                return $ruangan . ' (' . $departement . ')';
            })
            ->addColumn('aksi', function($row){
                return '
                    <a href="/detail-ac/'.$row->id.'/edit" class="btn btn-md btn-success"><i class="align-middle" data-feather="edit"></i><strong> Edit</strong></a>
                    <form action="/detail-ac/'.$row->id.'" method="POST" class="d-inline form-delete">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn btn-md btn-danger">
                            <i class="align-middle" data-feather="trash-2"></i><strong> Hapus</strong>
                        </button>
                    </form>
                    <button class="btn btn-md btn-secondary btn-detail" data-id="'.$row->id.'"><i class="align-middle" data-feather="eye"></i><strong> Detail</strong></button>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $merkac = MerkAC::all(); // ambil semua data merk AC
        $jenisac = JenisAC::all(); // ambil semua data jenis AC
        $ruangan = Ruangan::with('departement')->get(); // ambil semua data ruangan
        return view('admin.formtambahacdetail', compact('merkac', 'jenisac', 'ruangan'));
    }
    
    public function store(Request $request)
    {
        // Format otomatis: hapus non-angka, pad 3 digit, tambahkan prefix I-
        $angka = preg_replace('/[^0-9]/', '', $request->no_ac);
        $angka = str_pad($angka, 3, '0', STR_PAD_LEFT);
        $no_ac = 'I-' . $angka;

        // Validator manual
        $validator = Validator::make($request->all(), [
            'id_merkac' => 'required|exists:merkac,id',
            'id_jenisac' => 'required|exists:jenisac,id',
            'id_ruangan' => 'required|exists:ruangan,id',
            'no_ac' => 'required|string|max:100',
            'no_seri_indoor' => 'required|string|max:100|unique:acdetail,no_seri_indoor',
            'no_seri_outdoor' => 'required|string|max:100|unique:acdetail,no_seri_outdoor',
            'pk_ac' => 'required|numeric',
            'jumlah_ac' => 'required|integer|min:1',
            'tahun_ac' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'tanggal_pemasangan' => 'required|date',
            'tanggal_habis_garansi' => 'required|date|after_or_equal:tanggal_pemasangan',
        ]);

        // Tambahkan pengecekan unique untuk no_ac yang sudah diformat
        $validator->after(function ($validator) use ($no_ac) {
            if (DetailAC::where('no_ac', $no_ac)->exists()) {
                $validator->errors()->add('no_ac', "Nomor AC $no_ac sudah ada di database.");
            }
        });

        // Jika validasi gagal, redirect dengan input lama dan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simpan data
        DetailAC::create([
            'id_merkac' => $request->id_merkac,
            'id_jenisac' => $request->id_jenisac,
            'id_ruangan' => $request->id_ruangan,
            'no_ac' => $no_ac,
            'no_seri_indoor' => $request->no_seri_indoor,
            'no_seri_outdoor' => $request->no_seri_outdoor,
            'pk_ac' => $request->pk_ac,
            'jumlah_ac' => $request->jumlah_ac,
            'tahun_ac' => $request->tahun_ac,
            'tanggal_pemasangan' => $request->tanggal_pemasangan,
            'tanggal_habis_garansi' => $request->tanggal_habis_garansi,
        ]);

        return redirect()->route('detail-ac')->with('success', 'Detail AC baru berhasil ditambahkan.');
    }

    public function show($id)
    {
        $data = DetailAC::with(['merkac', 'jenisac', 'ruangan.departement'])->findOrFail($id);

        return response()->json([
            'nama_merk' => $data->merkac->nama_merk ?? '-',
            'nama_jenis' => $data->jenisac->nama_jenis ?? '-',
            'nama_ruangan' => $data->ruangan->nama_ruangan ?? '-',
            'nama_departement' => $data->ruangan->departement->nama_departement ?? '-',
            'no_ac' => $data->no_ac,
            'no_seri_indoor' => $data->no_seri_indoor,
            'no_seri_outdoor' => $data->no_seri_outdoor,
            'pk_ac' => $data->pk_ac,
            'jumlah_ac' => $data->jumlah_ac,
            'tahun_ac' => $data->tahun_ac,
            'tanggal_pemasangan' => $data->tanggal_pemasangan,
            'tanggal_habis_garansi' => $data->tanggal_habis_garansi,
        ]);
    }

    public function edit($id)
    {
        $acdetail = DetailAC::findOrFail($id);
        $merkac = MerkAC::all(); // ambil semua data merk AC
        $jenisac = JenisAC::all(); // ambil semua data jenis AC
        $ruangan = Ruangan::with('departement')->get(); // ambil semua data ruangan
        return view('admin.formeditdetailac', compact('acdetail', 'merkac', 'jenisac', 'ruangan'));
    }
    
    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'id_merkac' => 'required|exists:merkac,id',
            'id_jenisac' => 'required|exists:jenisac,id',
            'id_ruangan' => 'required|exists:ruangan,id',
            'no_ac' => 'required|string|max:100|unique:acdetail,no_ac,' . $id,
            'no_seri_indoor' => 'required|string|max:100|unique:acdetail,no_seri_indoor,' . $id,
            'no_seri_outdoor' => 'required|string|max:100|unique:acdetail,no_seri_outdoor,' . $id,
            'pk_ac' => 'required|numeric',
            'jumlah_ac' => 'required|integer|min:1',
            'tahun_ac' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'tanggal_pemasangan' => 'required|date',
            'tanggal_habis_garansi' => 'required|date|after_or_equal:tanggal_pemasangan',
        ]);

        // --- Format otomatis untuk no_ac ---
        $inputNoAc = trim($validatedData['no_ac']);
        $angka = preg_replace('/[^0-9]/', '', $inputNoAc); // hapus non-digit
        $angka = str_pad($angka, 3, '0', STR_PAD_LEFT); // pastikan 3 digit
        $validatedData['no_ac'] = 'I-' . $angka; // overwrite no_ac dengan format I-XXX

        // Perbarui data detail AC
        $acdetail = DetailAC::findOrFail($id);
        $acdetail->update($validatedData);

        // Redirect ke halaman detail AC dengan pesan sukses
        return redirect()->route('detail-ac')->with('success', 'Detail AC berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $detailAC = DetailAC::findOrFail($id);

        try {
            $detailAC->delete();
            return redirect()->route('detail-ac')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('detail-ac')->with('error', 'Data gagal dihapus. Mungkin masih terhubung dengan tabel lain.');
        }
    }
}
