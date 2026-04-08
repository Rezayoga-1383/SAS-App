<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\JenisAC;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JenisACController extends Controller
{
    public function index()
    {
        return view('superadmin.jenisac');
    }

    public function getData(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $data = JenisAC::select('id', 'nama_jenis');
        return DataTables::of($data)->make(true);
    }

    public function create()
    {
        return view('superadmin.formaddjenisac');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => [
                'required',
                'string',
                'max:255',
                'unique:jenisac,nama_jenis',
                'regex:/^[A-Za-z\s]+$/',
            ],
        ], [
            'nama_jenis.required' => 'Jenis AC wajib diisi',
            'nama_jenis.string' => 'Jenis AC harus berupa teks',
            'nama_jenis.max' => 'Jenis AC maksimal 255 karakter',
            'nama_jenis.unique' => 'Jenis AC sudah ada di database',
            'nama_jenis.regex' => 'Jenis AC hanya boleh mengandung huruf dan spasi',
        ]);

        JenisAC::create([
            'nama_jenis' => $request->input('nama_jenis'),
        ]);

        return redirect()->route('superadmin.jenisac')->with('success', 'Jenis AC berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jenis = JenisAC::findOrFail($id);
        return view('superadmin.formeditjenisac', compact('jenis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255',
        ], [
            'nama_jenis.required' => 'Jenis AC wajib diisi',
            'nama_jenis.string' => 'Jenis AC harus berupa teks',
            'nama_jenis.max' => 'Jenis AC maksimal 255 karakter',
        ]);

        $jenis = JenisAC::findOrFail($id);
        $jenis->nama_jenis = $request->input('nama_jenis');
        $jenis->save();

        return redirect()->route('superadmin.jenisac')->with('success', 'Jenis AC berhasil diperbarui');
    }

    public function destroy($id)
    {
        $jenis = JenisAC::findOrFail($id);

        if ($jenis->acdetail()->count()) {
            return redirect()->route('superadmin.jenisac')->with('error', 'Jenis AC tidak bisa dihapus karena masih digunakan di detail AC');
        }

        $jenis->delete();

        return redirect()->route('superadmin.jenisac')->with('success', 'Jenis AC berhasil dihapus');
    }
}
