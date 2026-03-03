<?php

namespace App\Jobs;

use App\Models\AcHistoryImage;
use App\Models\LogServiceImage;
use App\Models\LogServiceUnit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class ProcessSPKImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;   
    public $tries = 3;       
    public $backoff = 10;    

    protected $spkId;
    protected $historyPaths;
    protected $kolasePaths;

    public function __construct($spkId, $historyPaths, $kolasePaths)
    {
        $this->spkId = $spkId;
        $this->historyPaths = $historyPaths;
        $this->kolasePaths = $kolasePaths;
    }

    public function handle()
    {
        $manager = new ImageManager(new Driver());

        $units = LogServiceUnit::where('log_service_id', $this->spkId)
                    ->orderBy('id')
                    ->get();

        foreach ($units as $i => $unit) {

            // HISTORY IMAGE
            if (isset($this->historyPaths[$i])) {

                $path = $this->convertToWebp(
                    $manager,
                    $this->historyPaths[$i],
                    'spk_images/kartu_history'
                );

                AcHistoryImage::create([
                    'log_service_unit_id' => $unit->id,
                    'image_path' => $path,
                ]);
            }

            // KOLOASE
            if (isset($this->kolasePaths[$i])) {

                $path = $this->convertToWebp(
                    $manager,
                    $this->kolasePaths[$i],
                    'spk_images/kolase'
                );

                LogServiceImage::create([
                    'log_service_unit_id' => $unit->id,
                    'image_path' => $path,
                ]);
            }
        }
    }

    private function convertToWebp($manager, $tempPath, $folder)
    {
        $fullPath = storage_path('app/public/' . $tempPath);

        $image = $manager->read($fullPath)->orient();

        if ($image->width() > 1600) {
            $image = $image->scale(width: 1600);
        }

        $filename = uniqid('', true) . '.webp';
        $path = $folder . '/' . $filename;

        Storage::disk('public')->makeDirectory($folder);

        Storage::disk('public')->put(
            $path,
            $image->toWebp(75)->toString()
        );

        // Hapus file temp setelah diproses
        Storage::disk('public')->delete($tempPath);

        return $path;
    }
}