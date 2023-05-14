<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IjinInstruktur extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "ijin_instruktur";
    protected $primaryKey = "id";
    protected $fillable = [
        'id_instruktur',
        'id_mo',
        'id_jadwal_harian',
        'tanggal_pembuatan_ijin',
        'tanggal_ijin',
        'keterangan',
    ];
}
