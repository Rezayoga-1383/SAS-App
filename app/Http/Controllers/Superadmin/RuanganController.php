<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class RuanganController extends Controller
{
    public function index()
    {
        return view('superadmin.ruangan');
    }

    public function getData(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $data = Ruangan::with('departement')->select('ruangan.id', 'ruangan.nama_ruangan', 'ruangan.id_departement');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_departement', function($row) {
                return $row->departement ? $row->departement->nama_departement : '-';
            })
            ->addColumn('aksi', function($row) {
                return '
                    <a href="/superadmin/ruangan/'.$row->id.'/edit" class="btn btn-md btn-success"><i class="align-middle" data-feather="edit"></i><strong>Edit</strong></a>
                    <button class="btn btn-md btn-danger"><i class="align-middle" data-feather="trash-2"></i><strong>Hapus</strong></button>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $departement = Departement::all();
        return view('superadmin.formaddruangan', compact('departement'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ruangan')->where(function ($query) use ($request) {
                    return $query->where('id_departement', $request->id_departement);
                }),
            ],
            'id_departement' => [
                'required',
                'exists:departement,id',
            ],
        ], [
            'nama_ruangan.required' => 'Nama Ruangan wajib diisi',
            'nama_ruangan.unique' => 'Nama Ruangan sudah ada di Departement yang sama',
            'id_departement.required' => 'Departement wajib dipilih',
            'id_departement.exists' => 'Departement tidak valid',
        ]);

        Ruangan::create([
            'nama_ruangan' => $request->input('nama_ruangan'),
            'id_departement' => $request->input('id_departement'),
        ]);

        return redirect()->route('superadmin.ruangan')->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $departements = Departement::all();
        return view('superadmin.formeditruangan', compact('ruangan', 'departements'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_ruangan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ruangan')->where(function ($query) use ($request, $id) {
                    return $query->where('id_departement', $request->id_departement)
                                 ->where('id', '!=', $id);
                }),
            ],
            'id_departement' => [
                'required',
                'exists:departement,id',
            ],
        ], [
            'nama_ruangan.required' => 'Nama Ruangan wajib diisi',
            'nama_ruangan.unique' => 'Nama Ruangan sudah ada di Departement yang sama',
            'id_departement.required' => 'Departement wajib dipilih',
            'id_departement.exists' => 'Departement tidak valid',
        ]);

        $ruangan = Ruangan::findOrFail($id);
        $ruangan->nama_ruangan = $request->input('nama_ruangan');
        $ruangan->id_departement = $request->input('id_departement');
        $ruangan->save();

        return redirect()->route('superadmin.ruangan')->with('success', 'Ruangan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);

        if ($ruangan->acdetail()->count() > 0) {
            return redirect()->route('superadmin.ruangan')->with('error', 'Ruangan tidak dapat dihapus karena masih memiliki data di Detail AC');
        }
        $ruangan->delete();

        return redirect()->route('superadmin.ruangan')->with('success', 'Ruangan berhasil dihapus');
    }
}
