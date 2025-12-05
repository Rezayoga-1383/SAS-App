<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogServiceTeknisi extends Model
{
    use HasFactory;

    protected $table = 'log_service_teknisi';
    protected $fillable = [
        'log_service_id',
        'id_pengguna',
    ];

    public $timestamps = false;

    public function logService()
    {
        return $this->belongsTo(LogService::class, 'log_service_id', 'id');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id');
    }

}
