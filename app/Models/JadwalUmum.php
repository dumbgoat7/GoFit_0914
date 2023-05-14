<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalUmum extends Model
{
    use HasFactory;

    protected $table = "jadwal_umum";
    public $timestamps = false;
    protected $primaryKey = "id_jadwal";
    protected $fillable = [
        'id_kelas',
        'id_instruktur',
        'hari',
        'jam_mulai',
        'sesi_jadwal',
    ];

    public function Kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function Instruktur(){
        return $this->belongsTo(Instruktur::class, 'id_instruktur', 'id');
    }
}
