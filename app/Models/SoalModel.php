<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoalModel extends Model
{
    use HasFactory;
    protected $table = 'soal';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id_artikel',
        'soal',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'opsi_e',
        'jawaban',
        'score',
    ];
    public function artikel(): BelongsTo
    {
        return $this->belongsTo(ArtikelModel::class, 'id_artikel');
    }
}
