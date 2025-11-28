<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailAC extends Model
{
    use HasFactory;
    
    protected $table = 'acdetail';
    protected $fillable = [
        'id_merkac',
        'id_jenisac',
        'id_ruangan',
        'no_ac',
        'no_seri_indoor',
        'no_seri_outdoor',
        'pk_ac',
        'jumlah_ac',
        'tahun_ac',
        'tanggal_pemasangan',
        'tanggal_habis_garansi',
    ];

    public $timestamps = false;

    public function merkac()
    {
        return $this->belongsTo(MerkAC::class, 'id_merkac', 'id');
    }
    public function jenisac()
    {
        return $this->belongsTo(JenisAC::class, 'id_jenisac', 'id');
    }
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id');
    }
}
