<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisAC extends Model
{
    use HasFactory;
    
    protected $table = 'jenisac';
    protected $fillable = [
        'nama_jenis',
    ];

    public $timestamps = false;

    public function acdetail()
{
    return $this->hasMany(DetailAC::class, 'id_jenisac', 'id');
}
}
