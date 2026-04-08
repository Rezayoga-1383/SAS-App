<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\DetailAC;
use App\Models\JenisAC;
use App\Models\MerkAC;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class DetailACController extends Controller
{
    public function index()
    {
        return view('superadmin.detailac');
    }

    public function getData(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $data = DetailAC::query()
            ->leftJoin('ruangan', 'ruangan.id', '=', 'acdetail.id_ruangan')
            ->leftJoin('departement', 'departement.id', '=', 'ruangan.id_departement')
            ->select('acdetail.*', 'departement.nama_departement as nama_departement')
            ->with(['merkac', 'jenisac', 'ruangan.departement'])
            ->orderBy('departement.nama_departement', 'asc')
            ->orderBy('ruangan.nama_ruangan', 'asc');
        
        return DataTables::of($data)
            ->addIndexColumn()
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
            ->addColumn('aksi', function($row) {
                return '
                    <a href="/superadmin/detailac/'.$row->id.'/edit" class="btn btn-md btn-success"><i class="align-middle" data-feather="edit"></i><strong> Edit</strong></a>
                    <form action="/superadmin/detailac/'.$row->id.'" method="POST" class="d-inline form-delete">
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
        $merkac = MerkAC::all();
        $jenisac = JenisAC::all();
        $ruangan = Ruangan::with('departement')->get();
        return view('superadmin.formaddacdetail', compact('merkac', 'jenisac', 'ruangan'));
    }

    public function store(Request $request)
    {
        $angka = preg_replace('/[^0-9]/', '', $request->no_ac);
        $angka = str_pad($angka, 4, '0', STR_PAD_LEFT);
        $no_ac = 'I-' . $angka;

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
            'id_merkac.required' => 'Merk AC wajib dipilih',
            'id_merkac.exist' => 'Merk AC tidak valid',
            'id_jenisac.required' => 'Jenis AC wajib dipilih',
            'id_jenisac.exists' => 'Jenis AC tidak valid',
            'id_ruangan.required' => 'Ruangan wajib dipilih',
            'id_ruangan.exists' => 'Ruangan tidak valid',
            'no_ac.required' => 'Nomor AC wajib diisi',
            'no_ac.string' => 'Nomor AC harus berupa string',
            'no_ac.max' => 'Nomor AC maksimal 100 karakter',
            'nomor_ac.digits' => 'Nomor AC harus terdiri dari 4 digit',
            'no_seri_indoor.required' => 'Nomor seri indoor wajib diisi',
            'no_seri_indoor.string' => 'Nomor seri indoor harus berupa string',
            'no_seri_indoor.max' => 'Nomor seri indoor maksimal 100 karakter',
            'no_seri_indoor.unique' => 'Nomor seri indoor sudah digunakan',
            'no_seri_outdoor.required' => 'Nomor seri outdoor wajib diisi',
            'no_seri_outdoor.string' => 'Nomor seri outdoor harus berupa string',
            'no_seri_outdoor.max' => 'Nomor seri outdoor maksimal 100 karakter',
            'no_seri_outdoor.unique' => 'Nomor seri outdoor sudah digunakan',
            'pk_ac.required' => 'PK AC wajib diisi',
            'pk_ac.numeric' => 'PK AC harus berupa angka',
            'jumlah_ac.required' => 'Jumlah AC wajib diisi',
            'jumlah_ac.integer' => 'Jumlah AC harus berupa bilangan bulat',
            'jumlah_ac.min' => 'Jumlah AC minimal 1',
            'tahun_ac.required' => 'Tahun AC wajib diisi',
            'tahun_ac.digits' => 'Tahun AC harus terdiri dari 4 digit',
            'tahun_ac.integer' => 'Tahun AC harus berupa bilangan bulat',
            'tahun_ac.min' => 'Tahun AC minimal tahun 1900',
            'tahun_ac.max' => 'Tahun AC maksimal tahun saat ini',
            'tanggal_pemasangan.required' => 'Tanggal pemasangan wajib diisi',
            'tanggal_pemasangan.date' => 'Tanggal pemasangan harus berupa tanggal',
            'tanggal_habis_garansi.required' => 'Tanggal habis garansi wajib diisi',
            'tanggal_habis_garansi.date' => 'Tanggal habis garansi harus berupa tanggal',
            'tanggal_habis_garansi.after_or_equal' => 'Tanggal habis garansi harus sama atau setelah tanggal pemasangan',
        ]);

        $validator-> after(function ($validator) use ($no_ac) {
            if (DetailAC::where('no_ac', $no_ac)->exists()) {
                $validator->errors()->add('no_ac', "Nomor AC $no_ac sudah digunakan");
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DetailAC::create([
            'id_merkac' => $request->id_merkac,
            'id_jenisac' => $request->id_jenisac,
            'id_ruangan' => $request->id_ruangan,
            'no_ac' => $no_ac,
            'no_seri_indoor' => $request->no_seri_indoor,
            'no_seri_outdoor' => $request->no_seri_outdoor,
            'pk_ac'=> $request->pk_ac,
            'jumlah_ac' => $request->jumlah_ac,
            'tahun_ac' => $request->tahun_ac,
            'tanggal_pemasangan' => $request->tanggal_pemasangan,
            'tanggal_habis_garansi' => $request->tanggal_habis_garansi,
        ]);

        return redirect()->route('superadmin.detailac')->with('success', 'Detail AC Baru berhasil ditambahkan');
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
        $merkac = MerkAC::all();
        $jenisac = JenisAC::all();
        $ruangan = Ruangan::with('departement')->get();
        return view('superadmin.formeditacdetail', compact('acdetail', 'merkac', 'jenisac', 'ruangan'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'id_merkac' => 'required|exists:merkac,id',
            'id_jenisac' => 'required|exists:jenisac,id',
            'id_ruangan' => 'required|exists:ruangan,id',
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
            'id_merkac.required' => 'Merk AC wajib dipilih',
            'id_merkac.exists' => 'Merk AC tidak valid',
            'id_jenisac.required' => 'Jenis AC wajib dipilih',
            'id_jenisac.exists' => 'Jenis AC tidak valid',
            'id_ruangan.required' => 'Ruangan wajib dipilih',
            'id_ruangan.exists' => 'Ruangan tidak valid',
            'no_ac.required' => 'Nomor AC wajib diisi',
            'no_ac.digits' => 'Nomor AC harus terdiri dari 4 digit',
            'no_ac.unique' => 'Nomor AC sudah digunakan',
            'no_seri_indoor.required' => 'Nomor seri indoor wajib diisi',
            'no_seri_indoor.string' => 'Nomor seri indoor harus berupa string',
            'no_seri_indoor.max' => 'Nomor seri indoor maksimal 100 karakter',
            'no_seri_indoor.unique' => 'Nomor seri indoor sudah digunakan',
            'no_seri_outdoor.required' => 'Nomor seri outdoor wajib diisi',
            'no_seri_outdoor.string' => 'Nomor seri outdoor harus berupa string',
            'no_seri_outdoor.max' => 'Nomor seri outdoor maksimal 100 karakter',
            'no_seri_outdoor.unique' => 'Nomor seri outdoor sudah digunakan',
            'pk_ac.required' => 'PK AC wajib diisi',
            'pk_ac.numeric' => 'PK AC harus berupa angka',
            'jumlah_ac.required' => 'Jumlah AC wajib diisi',
            'jumlah_ac.integer' => 'Jumlah AC harus berupa bilangan bulat',
            'jumlah_ac.min' => 'Jumlah AC minimal 1',
            'tahun_ac.required' => 'Tahun AC wajib diisi',
            'tahun_ac.digits' => 'Tahun AC harus terdiri dari 4 digit',
            'tahun_ac.integer' => 'Tahun AC harus berupa bilangan bulat',
            'tahun_ac.min' => 'Tahun AC minimal tahun 1900',
            'tahun_ac.max' => 'Tahun AC maksimal tahun saat ini',
            'tanggal_pemasangan.required' => 'Tanggal pemasangan wajib diisi',
            'tanggal_pemasangan.date' => 'Tanggal pemasangan harus berupa tanggal',
            'tanggal_habis_garansi.required' => 'Tanggal habis garansi wajib diisi',
            'tanggal_habis_garansi.date' => 'Tanggal habis garansi harus berupa tanggal',
            'tanggal_habis_garansi.after_or_equal' => 'Tanggal habis garansi harus sama atau setelah tanggal pemasangan',
        ]);

        $angka = preg_replace('/[^0-9]/', '', $validatedData['no_ac']);
        $angka = str_pad($angka, 4, '0', STR_PAD_LEFT);
        $validatedData['no_ac'] = 'I-' . $angka;

        $acdetail = DetailAC::findOrFail($id);
        $acdetail->update($validatedData);

        return redirect()->route('superadmin.detailac')->with('success', 'Detail AC berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $detailAc = DetailAC::findOrFail($id);

        try {
            $detailAc->delete();
            return redirect()->route('superadmin.detailac')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('superadmin.detailac')->with('error', 'Data gagal dihapus');
        }
    }
}
