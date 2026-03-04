<?php

namespace App\Jobs;

use App\Models\AcHistoryImage;
use App\Models\LogService;
use App\Models\LogServiceImage;
use App\Models\LogServiceUnit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ProcessSPKImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;   // maksimal 5 menit
    public $tries = 3;       
    public $backoff = 10;    

    protected $spkId;
    protected $historyPaths;
    protected $kolasePaths;
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
        // Menggunakan driver Imagick (atau Gd) sesuai standar Intervention Image versi 3
        $manager = new ImageManager(\Intervention\Image\Drivers\Imagick\Driver::class); // atau \Intervention\Image\Drivers\Gd\Driver::class

        $units = LogServiceUnit::where('log_service_id', $this->spkId)
                    ->orderBy('id')
                    ->get();

        // ---------------- HISTORY & KOLOASE ----------------
        foreach ($units as $i => $unit) {

            // History Image
            if (isset($this->historyPaths[$i])) {
                $path = $this->convertToWebp($manager, $this->historyPaths[$i], 'spk_images/kartu_history');
                AcHistoryImage::create([
                    'log_service_unit_id' => $unit->id,
                    'image_path' => $path,
                ]);
            }

            // Kolase
            if (isset($this->kolasePaths[$i])) {
                $path = $this->convertToWebp($manager, $this->kolasePaths[$i], 'spk_images/kolase');
                LogServiceImage::create([
                    'log_service_unit_id' => $unit->id,
                    'image_path' => $path,
                ]);
            }
        }

        // ---------------- FILE SPK UTAMA ----------------
        if ($this->fileSpkTemp) {
            $spkWebpPath = $this->convertToWebp($manager, $this->fileSpkTemp, 'spk_images/file_spk');

            if ($spkWebpPath) {
                $spk = LogService::find($this->spkId);
                if ($spk) {
                    $spk->file_spk = $spkWebpPath;
                    $spk->save();
                }
            }
        }

        // ---------------- BERSIHKAN FILE TEMP ----------------
        // Hapus file temp di akhir agar jika job gagal di tengah jalan dan di-retry,
        // file temp dari file sebelumnya masih ada dan tidak menyebabkan error.
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

        // Jika file sudah tidak ada (misal job diretry tapi sudah kehapus sebelumnya), bypass
        if (!file_exists($fullPath)) {
            return null;
        }

        // Gunakan read() dari ImageManager (pengganti make() di versi 3)
        $image = $manager->read($fullPath);

        // Di versi 3, gunakan scaleDown() untuk resize lebar maksimal 1600 
        // secara proporsional dan tidak akan membesarkan gambar kecil (upsize)
        if ($image->width() > 1600) {
            $image->scaleDown(width: 1600);
        }

        $filename = uniqid('', true) . '.webp';
        $path = $folder . '/' . $filename;

        Storage::disk('public')->makeDirectory($folder);

        // Di versi 3, gunakan toWebp() untuk konversi format
        Storage::disk('public')->put($path, (string) $image->toWebp(75));

        // Hancurkan instansi image untuk menghindari memory leak
        // Di PHP 8 + Intervention Image 3, garbage collector biasanya sudah cukup.
        unset($image);

        return $path;
    }
}