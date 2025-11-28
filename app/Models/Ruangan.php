<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';
    protected $fillable = [
        'id_departement',
        'nama_ruangan',
    ];
    
    public $timestamps = false;

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'id_departement', 'id');
    }

    public function acdetail()
    {
        return $this->hasMany(DetailAC::class, 'id_ruangan', 'id');
    }
}