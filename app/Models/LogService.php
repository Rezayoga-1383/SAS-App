<?php

namespace App\Models;

use App\Models\DetailAC;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogService extends Model
{
    use HasFactory;

    protected $table = 'log_service';
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
        'before_image',
        'after_image',
    ];

    public $timestamps = false;

    public function acdetail()
    {
        return $this->belongsToMany(
            DetailAC::class, 'log_service_detail', 'log_service_id', 'acdetail_id'
        )->withPivot('keluhan', 'jenis_pekerjaan')->withTimestamps();
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
}
