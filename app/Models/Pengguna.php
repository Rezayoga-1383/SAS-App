<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    use HasFactory,Notifiable;

    protected $table = 'pengguna';
    protected $rememberTokenName = 'remember_token';

    protected $fillable = [
        'email',
        'nama',
        'password',
        'role',
    ];

    public $timestamps = false;
}
