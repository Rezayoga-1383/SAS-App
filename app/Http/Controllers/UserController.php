<?php

namespace App\Http\Controllers;

use App\Models\MerkAC;
use App\Models\JenisAC;
use App\Models\Ruangan;
use App\Models\DetailAC;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('index');
    }

    public function getRuangan($id)
    {
        // abort(404);

        $ruangan = Ruangan::where('id_departement', $id)->get();
        // dd($ruangan);
        return response()->json($ruangan);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $merkac = MerkAC::all(); // ambil semua data merk AC
        $jenisac = JenisAC::all(); // ambil semua data jenis AC
        $departement = Departement::all();
        // $ruangan = Ruangan::with('departement')->get(); // ambil semua data ruangan
        return view('user.FormInput', compact('merkac', 'jenisac','departement'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Format otomatis no_ac: hapus non-angka, pad 3 digit, tambahkan prefix I-
        $angka = preg_replace('/[^0-9]/', '', $request->no_ac);
        $angka = str_pad($angka, 3, '0', STR_PAD_LEFT);
        $no_ac = 'I-' . $angka;

        // Validator
        $validator = Validator::make($request->all(), [
            'id_merkac' => 'required|exists:merkac,id',
            'id_jenisac' => 'required|exists:jenisac,id',
            'id_ruangan' => 'required|exists:ruangan,id',
            'no_ac' => 'required|string|digits:3',
            'no_seri_indoor' => 'required|string|max:100|unique:acdetail,no_seri_indoor',
            'no_seri_outdoor' => 'required|string|max:100|unique:acdetail,no_seri_outdoor',
            'pk_ac' => 'required|numeric',
            'jumlah_ac' => 'nullable|integer|min:1',
            'tahun_ac' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'tanggal_pemasangan' => 'required|date',
            'tanggal_habis_garansi' => 'required|date|after:tanggal_pemasangan',
        ], [
            'id_merkac.required' => 'Merk AC wajib dipilih.',
            'id_merkac.exists' => 'Merk AC tidak valid.',
            'id_jenisac.required' => 'Jenis AC wajib dipilih.',
            'id_jenisac.exists' => 'Jenis AC tidak valid.',
            'id_ruangan.required' => 'Ruangan wajib dipilih.',
            'id_ruangan.exists' => 'Ruangan tidak valid.',
            'no_ac.required' => 'Nomor AC wajib diisi.',
            'no_ac.digits' => 'Nomor AC harus terdiri dari 3 digit angka.',
            'no_seri_indoor.required' => 'Nomor Seri Indoor wajib diisi.',
            'no_seri_indoor.unique' => 'Nomor Seri Indoor sudah digunakan.',
            'no_seri_outdoor.required' => 'Nomor Seri Outdoor wajib diisi.',
            'no_seri_outdoor.unique' => 'Nomor Seri Outdoor sudah digunakan.',
            'pk_ac.required' => 'PK AC wajib diisi.',
            'pk_ac.numeric' => 'PK AC harus berupa angka.',
            'jumlah_ac.integer' => 'Jumlah AC harus berupa angka bulat.',
            'jumlah_ac.min' => 'Jumlah AC minimal 1.',
            'tahun_ac.required' => 'Tahun AC wajib diisi.',
            'tahun_ac.digits' => 'Tahun AC harus terdiri dari 4 digit angka.',
            'tahun_ac.min' => 'Tahun AC tidak boleh kurang dari 1900.',
            'tahun_ac.max' => 'Tahun AC tidak boleh lebih dari tahun saat ini.',
            'tanggal_pemasangan.required' => 'Tanggal Pemasangan wajib diisi.',
            'tanggal_pemasangan.date' => 'Tanggal Pemasangan tidak valid.',
            'tanggal_habis_garansi.required' => 'Tanggal Habis Garansi wajib diisi.',
            'tanggal_habis_garansi.date' => 'Tanggal Habis Garansi tidak valid.',
            'tanggal_habis_garansi.after' => 'Tanggal Habis Garansi harus setelah Tanggal Pemasangan.',
        ]);

        // Validasi tambahan untuk no_ac
        $validator->after(function ($validator) use ($no_ac) {
            if (DetailAC::where('no_ac', $no_ac)->exists()) {
                $validator->errors()->add('no_ac', "Nomor AC $no_ac sudah digunakan.");
            }
        });

        // Jika validasi gagal, kembali ke form
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
            'jumlah_ac' => $request->jumlah_ac ?? 1, // default 1 jika kosong
            'tahun_ac' => $request->tahun_ac,
            'tanggal_pemasangan' => $request->tanggal_pemasangan,
            'tanggal_habis_garansi' => $request->tanggal_habis_garansi,
        ]);

        return redirect()->back()->with('success', 'Data AC Berhasil Dikirim, Terimakasih.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
