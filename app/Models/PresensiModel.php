<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiModel extends Model
{
    use HasFactory;
    protected $table = 'presensi';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $hidden = [
        'id',
        'user_id',
        'kelas_id',
    ];
    protected $fillable = [
        'user_id',
        'kelas_id',
        'status',
        'tanggal',
    ];
}
