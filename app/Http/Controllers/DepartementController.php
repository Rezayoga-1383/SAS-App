<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DepartementController extends Controller
{
    public function index()
    {
        return view('admin.departement');
    }

    public function getData(Request $request)
    {
        if (! $request->ajax()) {
        abort(404); // tampilkan halaman not found
        }
        
        $data = Departement::select('id', 'nama_departement');
        return DataTables::of($data)->make(true);
    }

    public function create()
    {
        return view('admin.formtambahdepartement');
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
        ]);

        Departement::create([
            'nama_departement' => $request->input('nama_departement'),
        ]);

        return redirect()->route('departement')->with('success', 'Departement berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $departement = Departement::findOrFail($id);
        return view('admin.formeditdepartement', compact('departement'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
        'nama_departement' => 'required|string|max:255',
        ], [
            'nama_departement.required' => 'Nama Departement wajib diisi.',
            'nama_departement.string' => 'Nama Departement harus berupa string.',
            'nama_departement.max' => 'Nama Departement maksimal 255 karakter.',
        ]);

        $departement = Departement::findOrFail($id);
        $departement->nama_departement = $request->input('nama_departement');
        $departement->save();

        return redirect()->route('departement')->with('success', 'Departement berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $departement = Departement::findOrFail($id);

        // Cek apakah ada DetailAC yang terkait dengan JenisAC ini
        if ($departement->ruangan()->count()) {
            return redirect()->route('departement')->with('error', "Departement \"{$departement->nama_departement}\" tidak bisa dihapus karena masih ada di ruangan.");
        }
        $departement->delete();

        return redirect()->route('departement')->with('success', 'Departement berhasil dihapus.');
    }
}
