<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankBacaanModel extends Model
{
    use HasFactory;
    protected $table = 'bank_bacaan';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'judul',
        'cover',
        'pdf',
    ];
}
