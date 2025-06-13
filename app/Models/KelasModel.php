<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KelasModel extends Model
{
    use HasFactory;
    protected $table = 'kelas';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'kelas',
        'sekolah_id',
    ];

    protected $hidden = [
        'id',
        'sekolah_id'
    ];



    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(SekolahModel::class, 'sekolah_id', 'id');
    }

    public function siswa() : HasMany
    {
        return $this->hasMany(User::class, 'kelas', 'id');
    }

}
