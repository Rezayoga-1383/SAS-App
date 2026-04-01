<?php

namespace App\Http\Controllers;

use App\Models\LogService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SPKAprovalController extends Controller
{
    public function checkPendingSpk()
    {
        if (auth()->id() != 8) {
            return response()->json(['has_pending' => false]);
        }

        $hasPending = LogService::where('status', 'menunggu')->exists();

        return response()->json([
            'has_pending' => $hasPending
        ]);
    }

    public function index()
    {
        if (auth()->id() != 8) {
            abort(403, 'Tidak Punya Akses');
        }

        return view('user.spk');
    }

    public function data(Request $request)
    {
        if (auth()->id() != 8) {
            abort(403);
        }
        
        $query = LogService::with(['teknisi', 'units.acdetail.ruangan.departement']);

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $query->latest();

        return DataTables::of($query)
            ->addIndexColumn()

            ->addColumn('no_spk', function ($row) {
                return $row->no_spk;
            })

            ->addColumn('tanggal', function ($row) {
                return \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y');
            })

            ->addColumn('teknisi', function ($row) {
                return $row->teknisi->isNotEmpty() ? $row->teknisi->pluck('nama')->implode(', '): '-';
            })

            ->addColumn('departement', function ($row) {
                return $row->units->pluck('acdetail.ruangan.departement.nama_departement')
                ->filter()
                ->unique()
                ->implode(', ');
            })

            ->addColumn('ruangan', function ($row) {
                return $row->units->pluck('acdetail.ruangan.nama_ruangan')
                ->filter()
                ->unique()
                ->implode(', ');
            })

            ->addColumn('status', function ($row) {
                if ($row->status == 'menunggu') {
                    return '<span class="badge bg-warning text-dark">Menunggu</span>';
                } elseif ($row->status == 'disetujui') {
                    return '<span class="badge bg-primary">Disetujui</span>';
                } else {
                    return '<span class="badge bg-success">Selesai</span>';
                }
            })

            ->addColumn('keterangan_spk', function ($row) {
                if (is_null($row->keterangan_spk)) {
                    return '<span class="text-muted">-</span>';
                }

                if ($row->keterangan_spk == 'cocok') {
                    return '<span class="badge bg-success">Cocok</span>';
                }

                if ($row->keterangan_spk == 'tidak cocok') {
                    return '<span class="badge bg-danger">Tidak Cocok</span>';
                }

                return '<span class="text-muted">-</span>';
            })

            ->addColumn('aksi', function($row) {
                $userId = auth()->id();
                $btn = '<div class="d-flex flex-wrap gap-1">';

                $btn .= '<button class="btn btn-info btn-sm btn-detail" data-id="'.$row->id.'" title="Detail">
                            <i class="bi bi-eye"></i>
                        </button>';
                
                if ($userId == 8) {
                    if ($row->status == 'menunggu') {
                        $btn .= '<button class="btn btn-warning btn-sm btn-approve" data-id="'.$row->id.'" title="Approve">
                                    <i class="bi bi-check-lg"></i>
                                </button>';
                    }
                    if ($row->status == 'disetujui') {
                        $btn .= '<button class="btn btn-success btn-sm btn-selesai" data-id="'.$row->id.'" title="Selesai">
                                    <i class="bi bi-check-circle"></i>
                                </button>';
                    }
                    if (
                        $row->status == 'disetujui' ||
                        ($row->status == 'selesai' && is_null($row->keterangan_spk))
                    ) {
                        if ($row->keterangan_spk == 'cocok') {
                            $color = 'success';
                            $icon = 'bi-check-lg';
                        } elseif ($row->keterangan_spk == 'tidak cocok') {
                            $color = 'danger';
                            $icon = 'bi-x-lg';
                        } else {
                            $color = 'secondary';
                            $icon = 'bi-pencil-square';
                        }

                        $btn .= '<button class = "btn btn-'.$color.' btn-sm btn-keterangan" data-id="'.$row->id.'" title="Keterangan">
                                    <i class="bi '.$icon.'"></i>
                                </button>';
                    }
                    
                }

                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['status','keterangan_spk', 'aksi'])
            ->make(true);
    }

    public function approve($id)
    {
        if (auth()->id() != 8) {
            abort(403);
        }

        $spk = LogService::findOrFail($id);
        $spk->status = LogService::STATUS_DISETUJUI;
        $spk->save();

        return response()->json(['success' => true]);
    }

    public function selesai($id)
    {
        if (auth()->id() != 8) {
            abort(403);
        }

        $spk = LogService::findOrFail($id);
        $spk->status = LogService::STATUS_SELESAI;
        $spk->save();

        return response()->json(['success' => true]);
    }

    public function detail(Request $request, $id)
    {
        $spk = LogService::with([
            'teknisi',
            'units.acdetail.ruangan.departement',
            'units.images',
            'units.historyImages',
            'details'
        ])->find($id);

        if (!$spk){
            return response()->json(['message' => 'Data Tidak Ditemukan'], 404);
        }

        return response()->json($spk);
    }

    public function updateKeterangan(Request $request, $id)
    {
        // dd($request->all());
        // 🔒 Batasi hanya user tertentu
        if (auth()->id() != 8) {
            return response()->json([
                'message' => 'Tidak punya akses'
            ], 403);
        }

        // ✅ Validasi
        $request->validate([
            'keterangan_spk' => 'nullable|in:cocok,tidak cocok'
        ]);

        // 🔍 Ambil data
        $data = LogService::findOrFail($id);

        // 💾 Update
        $data->keterangan_spk = $request->keterangan_spk;
        $data->save();

        return response()->json([
            'message' => 'Keterangan berhasil diupdate'
        ]);
    }
}
