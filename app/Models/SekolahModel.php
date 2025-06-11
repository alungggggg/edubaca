<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SekolahModel extends Model
{
    use HasFactory;
    protected $table = 'sekolah';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'nama_sekolah',
    ];

    
    
    public function kelas(): HasMany
    {
        return $this->hasMany(KelasModel::class, 'sekolah_id', 'id');
    }
}
