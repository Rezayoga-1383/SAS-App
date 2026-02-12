<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcHistoryImage extends Model
{
    protected $table = 'ac_history_images';
    protected $fillable = [
        'log_service_unit_id',
        'image_path',
    ];

    public function logServiceUnit()
    {
        return $this->belongsTo(LogServiceUnit::class, 'log_service_unit_id');
    }
}
