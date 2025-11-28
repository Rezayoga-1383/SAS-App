<?php

namespace App\Models;

use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departement extends Model
{
    use HasFactory;

    protected $table = 'departement';
    protected $fillable = [
        'nama_departement',
    ];

    public $timestamps = false;

    public function ruangan()
    {
        return $this->hasMany(Ruangan::class, 'id_departement', 'id');
    }
}
