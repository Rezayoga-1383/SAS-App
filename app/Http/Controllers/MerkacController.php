<?php

namespace App\Http\Controllers;

use App\Models\MerkAC;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MerkacController extends Controller
{
    public function index()
    {
        return view('admin.merk-ac');
    }

    public function getData(Request $request)
    {
        if (! $request->ajax()) {
        abort(404); // tampilkan halaman not found
        }
        
        $data = MerkAC::select('id', 'nama_merk');
        return DataTables::of($data)->make(true);
    }

    public function create()
    {
        return view('admin.formtambahmerk');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_merk' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:merkac,nama_merk',
                    'regex:/^[A-Za-z\s]+$/'
            ],
        ], [
                'nama_merk.required' => 'Merk AC wajib diisi.',
                'nama_merk.string' => 'Merk AC harus berupa teks.',
                'nama_merk.max' => 'Merk AC maksimal 255 karakter.',
                'nama_merk.unique' => 'Merk AC sudah ada di database.',
                'nama_merk.regex' => 'Merk AC hanya boleh mengandung huruf dan spasi.',
        ]);

        MerkAC::create([
            'nama_merk' => $request->input('nama_merk'),
        ]);

        return redirect()->route('merk-ac')->with('success', 'Merk AC berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $merk = MerkAC::findOrFail($id);
        return view('admin.formeditmerk', compact('merk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_merk' => 'required|string|max:255',
        ], [
            'nama_merk.required' => 'Merk AC wajib diisi.',
            'nama_merk.string' => 'Merk AC harus berupa teks.',
            'nama_merk.max' => 'Merk AC maksimal 255 karakter.',
        ]);

        $merk = MerkAC::findOrFail($id);
        $merk->nama_merk = $request->nama_merk;
        $merk->save();

        return redirect()->route('merk-ac')->with('success', 'Data Merk AC berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $merk = MerkAC::findOrFail($id);

        // Cek apakah merk masih dipakai di tabel acdetail
        if ($merk->acdetail()->count() > 0) {
            return redirect()->route('merk-ac')->with('error', 'Merk AC tidak bisa dihapus karena masih digunakan di detail AC.');
        }

        $merk->delete();
        return redirect()->route('merk-ac')->with('success', 'Merk AC berhasil dihapus.');
    }
}
