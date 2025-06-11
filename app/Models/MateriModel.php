<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriModel extends Model
{
    use HasFactory;
    protected $table = 'materi';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'judul',
        'cover',
        'pdf',
    ];
}
