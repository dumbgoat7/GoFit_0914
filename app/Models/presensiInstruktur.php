<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class presensiInstruktur extends Model
{
    use HasFactory;
    protected $table = "promo";
    public $timestamps = false;
    protected $primaryKey = "id";
    protected $fillable = [
        'id_mo',
        'id_instruktur',
        'id_jadwal',
        'tanggal_presensi',
        'jam_datang',
        'waktu_terlambat',
        'status'
    ];
}
