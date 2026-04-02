<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogService extends Model
{
    use HasFactory;

    protected $table = 'log_service';

    const STATUS_MENUNGGU   = 'menunggu';
    const STATUS_DISETUJUI  = 'disetujui';
    const STATUS_SELESAI    = 'selesai';
    const STATUS_BELUM_SELESAI = 'belum selesai';

    protected $fillable = [
        'no_spk',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'jumlah_orang',
        'kepada',
        'mengetahui',
        'hormat_kami',
        'pelaksana_ttd',
        'file_spk',
        'status',
        'keterangan_spk',
        'catatan_spk',
    ];

    protected $attributes = [
        'status' => self::STATUS_MENUNGGU,
    ];

    public function units()
    {
        return $this->hasMany(LogServiceUnit::class, 'log_service_id');
    }

    public function pelaksana()
    {
        return $this->belongsTo(Pengguna::class, 'pelaksana_ttd', 'id');
    }
    public function teknisi()
    {
        return $this->belongsToMany(
            Pengguna::class, 'log_service_teknisi', 'log_service_id', 'id_pengguna'
        );
    }
    public function hormatKamiUser()
    {
        return $this->belongsTo(Pengguna::class, 'hormat_kami'); // kolom di log_service yang menyimpan id user
    }

    public function details()
    {
        return $this->hasMany(LogServiceDetail::class, 'log_service_id');
    }

    // Helper
    public function isMenunggu()
    {
        return $this->status === self::STATUS_MENUNGGU;
    }

    public function isDisetujui()
    {
        return $this->status === self::STATUS_DISETUJUI;
    }

    public function isSelesai()
    {
        return $this->status === self::STATUS_SELESAI;
    }

    public function isBelumSelesai()
    {
        return $this->status === self::STATUS_BELUM_SELESAI;
    }
    public function hppDetail()
    {
        return $this->hasMany(HppDetail::class, 'log_service_id');
    }

}
