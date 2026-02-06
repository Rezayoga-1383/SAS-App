<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcHistoryImage extends Model
{
    protected $table = 'ac_history_image_table_';
    protected $fillable = [
        'acdetail_id',
        'log_service_id',
        'image_path',
    ];

    public function acdetail()
    {
        return $this->belongsTo(DetailAC::class, 'acdetail_id');
    }

    public function logService()
    {
        return $this->belongsTo(LogService::class);
    }
}
