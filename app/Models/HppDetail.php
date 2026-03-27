<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HppDetail extends Model
{
    protected $table = 'hpp_detail';

    protected $fillable = [
        'log_service_id',
        'keterangan',
        'nominal'
    ];

    public function logService()
    {
        return $this->belongsTo(LogService::class);
    }
}
