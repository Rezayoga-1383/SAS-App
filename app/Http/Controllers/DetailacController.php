<?php

namespace App\Http\Controllers;

use App\Models\MerkAC;
use App\Models\JenisAC;
use App\Models\Ruangan;
use App\Models\DetailAC;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

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

        $data = DetailAC::query()
            ->leftJoin('ruangan', 'ruangan.id', '=', 'acdetail.id_ruangan')
            ->leftJoin('departement', 'departement.id', '=', 'ruangan.id_departement')
            ->select('acdetail.*', 'departement.nama_departement as nama_departement')
            ->with(['merkac', 'jenisac', 'ruangan.departement'])
            ->orderBy('departement.nama_departement', 'asc')
            ->orderBy('ruangan.nama_ruangan', 'asc');

        return DataTables::of($data)
            ->addIndexColumn() // kolom No otomatis
            ->addColumn('nama_merkac', function($row) {
                return $row->merkac ? $row->merkac->nama_merk : '-';
            })
            ->addColumn('nama_jenisac', function($row) {
                return $row->jenisac ? $row->jenisac->nama_jenis : '-';
            })

            ->addColumn('nama_departement', fn($row) => $row->ruangan?->departement?->nama_departement ?? '-')
            
            ->addColumn('nama_ruangan', function($row) {
                return $row->ruangan?->nama_ruangan ?? '-';
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
        $angka = str_pad($angka, 4, '0', STR_PAD_LEFT);
        $no_ac = 'I-' . $angka;

        // Validator manual
        $validator = Validator::make($request->all(), [
            'id_merkac' => 'required|exists:merkac,id',
            'id_jenisac' => 'required|exists:jenisac,id',
            'id_ruangan' => 'required|exists:ruangan,id',
            'no_ac' => 'required|string|max:100|digits:4',
            'no_seri_indoor' => 'required|string|max:100|unique:acdetail,no_seri_indoor',
            'no_seri_outdoor' => 'required|string|max:100|unique:acdetail,no_seri_outdoor',
            'pk_ac' => 'required|numeric',
            'jumlah_ac' => 'required|integer|min:1',
            'tahun_ac' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'tanggal_pemasangan' => 'required|date',
            'tanggal_habis_garansi' => 'required|date|after_or_equal:tanggal_pemasangan',
        ], [
            'id_merkac.required' => 'Merk AC wajib dipilih.',
            'id_merkac.exists' => 'Merk AC tidak valid.',
            'id_jenisac.required' => 'Jenis AC wajib dipilih.',
            'id_jenisac.exists' => 'Jenis AC tidak valid.',
            'id_ruangan.required' => 'Ruangan wajib dipilih.',
            'id_ruangan.exists' => 'Ruangan tidak valid.',
            'no_ac.required' => 'Nomor AC wajib diisi.',
            'no_ac.string' => 'Nomor AC harus berupa string.',
            'no_ac.max' => 'Nomor AC maksimal 100 karakter.',
            'nomor_ac.digits' => 'Nomor AC harus terdiri dari 4 digit.',
            'no_seri_indoor.required' => 'Nomor Seri Indoor wajib diisi.',
            'no_seri_indoor.string' => 'Nomor Seri Indoor harus berupa string.',
            'no_seri_indoor.max' => 'Nomor Seri Indoor maksimal 100 karakter.',
            'no_seri_indoor.unique' => 'Nomor Seri Indoor sudah ada di database.',
            'no_seri_outdoor.required' => 'Nomor Seri Outdoor wajib diisi.',
            'no_seri_outdoor.string' => 'Nomor Seri Outdoor harus berupa string.',
            'no_seri_outdoor.max' => 'Nomor Seri Outdoor maksimal 100 karakter.',
            'no_seri_outdoor.unique' => 'Nomor Seri Outdoor sudah ada di database.',
            'pk_ac.required' => 'PK AC wajib diisi.',
            'pk_ac.numeric' => 'PK AC harus berupa angka.',
            'jumlah_ac.required' => 'Jumlah AC wajib diisi.',
            'jumlah_ac.integer' => 'Jumlah AC harus berupa bilangan bulat.',
            'jumlah_ac.min' => 'Jumlah AC minimal 1.',
            'tahun_ac.required' => 'Tahun AC wajib diisi.',
            'tahun_ac.digits' => 'Tahun AC harus terdiri dari 4 digit.',
            'tahun_ac.integer' => 'Tahun AC harus berupa bilangan bulat.',
            'tahun_ac.min' => 'Tahun AC minimal tahun 1900.',
            'tahun_ac.max' => 'Tahun AC tidak boleh lebih dari tahun saat ini.',
            'tanggal_pemasangan.required' => 'Tanggal Pemasangan wajib diisi.',
            'tanggal_pemasangan.date' => 'Tanggal Pemasangan tidak valid.',
            'tanggal_habis_garansi.required' => 'Tanggal Habis Garansi wajib diisi.',
            'tanggal_habis_garansi.date' => 'Tanggal Habis Garansi tidak valid.',
            'tanggal_habis_garansi.after_or_equal' => 'Tanggal Habis Garansi harus sama atau setelah Tanggal Pemasangan.',
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
        $validatedData = $request->validate([
            'id_merkac' => 'required|exists:merkac,id',
            'id_jenisac' => 'required|exists:jenisac,id',
            'id_ruangan' => 'required|exists:ruangan,id',

            // validasi awal input user
            'no_ac' => [
                'required',
                'digits:4',
                Rule::unique('acdetail', 'no_ac')->ignore($id),
            ],

            'no_seri_indoor' => [
                'required',
                'string',
                'max:100',
                Rule::unique('acdetail', 'no_seri_indoor')->ignore($id),
            ],

            'no_seri_outdoor' => [
                'required',
                'string',
                'max:100',
                Rule::unique('acdetail', 'no_seri_outdoor')->ignore($id),
            ],

            'pk_ac' => 'required|numeric',
            'jumlah_ac' => 'required|integer|min:1',
            'tahun_ac' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'tanggal_pemasangan' => 'required|date',
            'tanggal_habis_garansi' => 'required|date|after_or_equal:tanggal_pemasangan',
        ], [
            'id_merkac.required' => 'Merk AC wajib dipilih.',
            'id_merkac.exists' => 'Merk AC tidak valid.',
            'id_jenisac.required' => 'Jenis AC wajib dipilih.',
            'id_jenisac.exists' => 'Jenis AC tidak valid.',
            'id_ruangan.required' => 'Ruangan wajib dipilih.',
            'id_ruangan.exists' => 'Ruangan tidak valid.',
            'no_ac.required' => 'Nomor AC wajib diisi.',
            'no_ac.digits' => 'Nomor AC harus terdiri dari 4 digit.',
            'no_ac.unique' => 'Nomor AC sudah ada di database.',
            'no_seri_indoor.required' => 'Nomor Seri Indoor wajib diisi.',
            'no_seri_indoor.string' => 'Nomor Seri Indoor harus berupa string.',
            'no_seri_indoor.max' => 'Nomor Seri Indoor maksimal 100 karakter.',
            'no_seri_indoor.unique' => 'Nomor Seri Indoor sudah ada di database.',
            'no_seri_outdoor.required' => 'Nomor Seri Outdoor wajib diisi.',
            'no_seri_outdoor.string' => 'Nomor Seri Outdoor harus berupa string.',
            'no_seri_outdoor.max' => 'Nomor Seri Outdoor maksimal 100 karakter.',
            'no_seri_outdoor.unique' => 'Nomor Seri Outdoor sudah ada di database.',
            'pk_ac.required' => 'PK AC wajib diisi.',
            'pk_ac.numeric' => 'PK AC harus berupa angka.',
            'jumlah_ac.required' => 'Jumlah AC wajib diisi.',
            'jumlah_ac.integer' => 'Jumlah AC harus berupa bilangan bulat.',
            'jumlah_ac.min' => 'Jumlah AC minimal 1.',
            'tahun_ac.required' => 'Tahun AC wajib diisi.',
            'tahun_ac.digits' => 'Tahun AC harus terdiri dari 4 digit.',
            'tahun_ac.integer' => 'Tahun AC harus berupa bilangan bulat.',
            'tahun_ac.min' => 'Tahun AC minimal tahun 1900.',
            'tahun_ac.max' => 'Tahun AC tidak boleh lebih dari tahun saat ini.',
            'tanggal_pemasangan.required' => 'Tanggal Pemasangan wajib diisi.',
            'tanggal_pemasangan.date' => 'Tanggal Pemasangan tidak valid.',
            'tanggal_habis_garansi.required' => 'Tanggal Habis Garansi wajib diisi.',
            'tanggal_habis_garansi.date' => 'Tanggal Habis Garansi tidak valid.',
            'tanggal_habis_garansi.after_or_equal' => 'Tanggal Habis Garansi harus sama atau setelah Tanggal Pemasangan.',
        ]);

        // Format ulang no_ac menjadi I-XXXX
        $angka = preg_replace('/[^0-9]/', '', $validatedData['no_ac']);
        $angka = str_pad($angka, 4, '0', STR_PAD_LEFT);
        $validatedData['no_ac'] = 'I-' . $angka;

        // Simpan update
        $acdetail = DetailAC::findOrFail($id);
        $acdetail->update($validatedData);

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
