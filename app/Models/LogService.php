<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogService extends Model
{
    use HasFactory;

    protected $table = 'log_service';
    protected $fillable = [
        'id_acdetail',
        'no_spk',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'jumlah_orang',
        'keluhan',
        'jenis_pekerjaan',
        'kepada',
        'mengetahui',
        'hormat_kami',
        'pelaksana_ttd',
        'file_spk'
    ];

    public $timestamps = false;

    public function acdetail()
    {
        return $this->belongsTo(DetailAC::class, 'id_acdetail', 'id');
    }
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pelaksana_ttd', 'id');
    }
    public function teknisi()
    {
        return $this->belongsToMany(
            Pengguna::class, 'log_service_teknisi', 'log_service_id', 'id_pengguna'
        );
    }
}
