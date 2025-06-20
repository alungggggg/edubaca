<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerangkatMateriModel extends Model
{
    use HasFactory;
    protected $table = 'perangkat_materi';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'judul',
        'cover',
        'file',
    ];
}
