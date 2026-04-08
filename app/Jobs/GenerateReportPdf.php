<?php

namespace App\Jobs;

use App\Models\LogService;
use App\Models\Report;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class GenerateReportPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $start;
    protected $end;
    protected $jenis;
    protected $userId;
    protected $reportId;
    
    public $timeout = 0;


    /**
     * Create a new job instance.
     */
    public function __construct($start, $end, $jenis, $userId, $reportId)
    {
        $this->start = $start;
        $this->end = $end;
        $this->jenis = $jenis;
        $this->userId = $userId;
        $this->reportId = $reportId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ini_set('memory_limit', '2048M');

        $query = LogService::with([
            'units.acdetail.ruangan.departement',
            'units.images',
            'units.historyImages',
            'details'
        ])
        ->whereBetween('tanggal', [$this->start, $this->end])
        ->orderBy('tanggal', 'asc');

        if (!empty($this->jenis)) {
            $query->whereHas('details', function($q) {
                $q->where('kategori_pekerjaan', $this->jenis);
            });
        }

        $spkList = $query->get();

        $data = [];

        foreach ($spkList as $spk) {
            foreach ($spk->units as $unit) {
                $data[] = [
                    'tanggal' => Carbon::parse($spk->tanggal)->format('d-m-Y'),
                    'no_ac' => $unit->acdetail->no_ac ?? '-',
                    'ruangan' => optional($unit->acdetail->ruangan)->nama_ruangan ?? '-',
                    'departemen' => optional($unit->acdetail->ruangan->departement)->nama_departement ?? '-',
                    'foto_history' => optional($unit->historyImages->first())->image_path,
                    'foto_kolase' => optional($unit->images->first())->image_path,
                ];
            }
        }

        $pdf = \PDF::loadView('admin.reportpdf', [
            'data' => $data,
            'start_date' => $this->start,
            'end_date' => $this->end,
            'jenis_service' => $this->jenis
        ])->setPaper('a4', 'landscape');

        $filename = 'report-' . time() . '.pdf';

        Storage::disk('public')->put(
            'reports/' . $filename,
            $pdf->output()
        );

        Report::where('id', $this->reportId)->update([
            'file' => $filename,
            'status' => 'done'
        ]);
    }
}
