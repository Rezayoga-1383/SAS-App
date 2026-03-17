<?php

namespace App\Jobs;

use App\Models\AcHistoryImage;
use App\Models\LogService;
use App\Models\LogServiceImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ProcessUpdateSPKImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;   // maksimal 5 menit
    public $tries = 3;       
    public $backoff = 10;    

    protected $spkId;
    protected $historyPaths; // associative array: [ unit_id => temp_path ]
    protected $kolasePaths;  // associative array: [ unit_id => temp_path ]
    protected $fileSpkTemp;

    public function __construct($spkId, $historyPaths, $kolasePaths, $fileSpkTemp)
    {
        $this->spkId = $spkId;
        $this->historyPaths = $historyPaths;
        $this->kolasePaths = $kolasePaths;
        $this->fileSpkTemp = $fileSpkTemp;
    }

    public function handle()
    {
        $manager = new ImageManager(\Intervention\Image\Drivers\Imagick\Driver::class);

        // ---------------- HISTORY ----------------
        foreach ($this->historyPaths as $unitId => $tempPath) {
            $path = $this->convertToWebp($manager, $tempPath, 'spk_images/kartu_history');
            if ($path) {
                // Hapus old physical file if exists
                $history = AcHistoryImage::where('log_service_unit_id', $unitId)->first();
                if ($history && $history->image_path && Storage::disk('public')->exists($history->image_path)) {
                    Storage::disk('public')->delete($history->image_path);
                }

                // Update or Create new record
                AcHistoryImage::updateOrCreate(
                    ['log_service_unit_id' => $unitId],
                    ['image_path' => $path]
                );
            }
        }

        // ---------------- KOLASE ----------------
        foreach ($this->kolasePaths as $unitId => $tempPath) {
            $path = $this->convertToWebp($manager, $tempPath, 'spk_images/kolase');
            if ($path) {
                // Hapus old physical file if exists
                $existingImage = LogServiceImage::where('log_service_unit_id', $unitId)->first();
                if ($existingImage && $existingImage->image_path && Storage::disk('public')->exists($existingImage->image_path)) {
                    Storage::disk('public')->delete($existingImage->image_path);
                }

                // Update or Create new record
                LogServiceImage::updateOrCreate(
                    ['log_service_unit_id' => $unitId],
                    ['image_path' => $path]
                );
            }
        }

        // ---------------- FILE SPK UTAMA ----------------
        if ($this->fileSpkTemp) {
            $spkWebpPath = $this->convertToWebp($manager, $this->fileSpkTemp, 'spk_images/file_spk');

            if ($spkWebpPath) {
                $spk = LogService::find($this->spkId);
                if ($spk) {
                    if ($spk->file_spk && Storage::disk('public')->exists($spk->file_spk)) {
                        Storage::disk('public')->delete($spk->file_spk);
                    }
                    $spk->file_spk = $spkWebpPath;
                    $spk->save();
                }
            }
        }

        // ---------------- BERSIHKAN FILE TEMP ----------------
        foreach ($this->historyPaths as $path) {
            if (Storage::disk('public')->exists($path)) Storage::disk('public')->delete($path);
        }
        foreach ($this->kolasePaths as $path) {
            if (Storage::disk('public')->exists($path)) Storage::disk('public')->delete($path);
        }
        if ($this->fileSpkTemp && Storage::disk('public')->exists($this->fileSpkTemp)) {
            Storage::disk('public')->delete($this->fileSpkTemp);
        }
    }

    private function convertToWebp($manager, $tempPath, $folder)
    {
        $fullPath = storage_path('app/public/' . $tempPath);

        if (!file_exists($fullPath)) {
            return null;
        }

        $image = $manager->read($fullPath);

        if ($image->width() > 1600) {
            $image->scaleDown(width: 1600);
        }

        $filename = uniqid('', true) . '.webp';
        $path = $folder . '/' . $filename;

        Storage::disk('public')->makeDirectory($folder);

        Storage::disk('public')->put($path, (string) $image->toWebp(75));

        unset($image);

        return $path;
    }
}
