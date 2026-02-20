<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogServiceDetail extends Model
{
    protected $table = 'log_service_detail';
    protected $fillable = [
        'log_service_id',
        'acdetail_id',
        'keluhan',
        'jenis_pekerjaan',
    ];

    public function acdetail()
    {
        return $this->belongsTo(DetailAC::class, 'acdetail_id');
    }

    public function logService()
    {
        return $this->belongsTo(LogService::class, 'log_service_id');
    }

}
