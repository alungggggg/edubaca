<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SoalModel;
use App\Models\NilaiModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArtikelModel extends Model
{
    use HasFactory;
    protected $table = 'artikel';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'artikel_link',
        'judul',
        "type",
        'image',
        'deskripsi',
    ];

    public function soal(): HasMany
    {
        return $this->hasMany(SoalModel::class, 'id_artikel', 'id');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(NilaiModel::class, 'id_artikel', 'id');
    }
}
