<?php

namespace App\Http\Controllers;

use App\Models\DetailAC;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportDataACController extends Controller
{
    public function export()
    {
        $data = DetailAC::with([
            'merkac',
            'jenisac',
            'ruangan.departement'
        ])->get();

        $pdf = Pdf::loadView('admin.reportdataac', [
            'data' => $data,
            'tanggal' => now()->format('d-m-Y H:i')
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download(
            'Data_Detail_AC_' . now()->format('Y-m-d_H-i-s') . '.pdf'
        );
    }
}
