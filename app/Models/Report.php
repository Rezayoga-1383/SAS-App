<?php

namespace App\Models;

use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'user_id',
        'file',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }
}
