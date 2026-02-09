<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogServiceImage extends Model
{
    protected $table = 'log_service_image';
    protected $fillable = [
        'log_service_unit_id',
        'image_path',
    ];

    public function logServiceUnit()
    {
        return $this->belongsTo(LogServiceUnit::class);
    }
}
