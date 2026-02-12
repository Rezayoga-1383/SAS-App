<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogServiceUnit extends Model
{
    protected $table = 'log_service_unit';
    protected $fillable = [
        'log_service_id',
        'acdetail_id',
    ];

    public function logService()
    {
        return $this->belongsTo(LogService::class);
    }

    public function acdetail()
    {
        return $this->belongsTo(DetailAC::class, 'acdetail_id');
    }

    public function images()
    {
        return $this->hasMany(LogServiceImage::class);
    }

    // helper: ambil foto spesifik
    public function beforeIndoor()
    {
        return $this->images()
            ->where('kondisi', 'before')
            ->where('posisi', 'indoor')
            ->first();
    }

    // di LogServiceUnit.php
    public function detail()
    {
        return $this->hasOne(LogServiceDetail::class, 'acdetail_id', 'acdetail_id');
    }

    // LogServiceUnit.php
    public function historyImages()
    {
        return $this->hasMany(AcHistoryImage::class, 'log_service_unit_id');
    }

}

