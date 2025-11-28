<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MerkAC extends Model
{
    use HasFactory;
    
    protected $table = 'merkac';
    protected $fillable = [
        'nama_merk',
    ];

    public $timestamps = false;

    public function acdetail()
{
    return $this->hasMany(DetailAC::class, 'id_merkac', 'id');
}
}
