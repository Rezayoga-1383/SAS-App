<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DepartementController extends Controller
{
    public function index()
    {
        return view('superadmin.departement');
    }

    public function getData(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $data = Departement::select('id', 'nama_departement');
        return DataTables::of($data)->make(true);
    }

    public function create()
    {
        return view('superadmin.formadddepartement');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_departement' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:departement,nama_departement',
            ],
        ], [
                'nama_departement.required' => 'Nama Departement wajib diisi',
                'nama_departement.string' => 'Nama Departement harus berupa string',
                'nama_departement.max' => 'Nama Departement maksimal 255 karakter',
                'nama_departement.unique' => 'Nama Departement sudah ada',
        ]);

        Departement::create([
            'nama_departement' => $request->input('nama_departement'),
        ]);

        return redirect()->route('superadmin.departement')->with('success', 'Departement berhasil ditambahkan');
    }

    public function edit($id)
    {
        $departement = Departement::findOrFail($id);
        return view('superadmin.formeditdepartement', compact('departement'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_departement' => 'required|string|max:255,'
        ], [
            'nama_departement.required' => 'Nama Departement wajib diisi',
            'nama_departement.string' => 'Nama Departement harus berupa string',
            'nama_departement.max' => 'Nama Departement maksimal 255 karakter',
        ]);

        $departement = Departement::findOrFail($id);
        $departement->nama_departement = $request->input('nama_departement');
        $departement->save();

        return redirect()->route('superadmin.departement')->with('success', 'Departement berhasil di update');
    }

    public function destroy($id)
    {
        $departement = Departement::findOrFail($id);

        if ($departement->ruangan()->count()) {
            return redirect()->route('superadmin.departement')->with('error', "Departement \"{$departement->nama_departement}\" tidak bisa dihapus karena masih ada di ruangan");
        }
        $departement->delete();

        return redirect()->route('superadmin.departement')->with('success', 'Departement berhasil dihapus');
    }
}
