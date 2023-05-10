<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalHarian extends Model
{
    use HasFactory;
    protected $table = "jadwal_harian";
    public $timestamps = false;
    protected $primaryKey = "id";

    protected $fillable = [
        'id_jadwal_umum',
        'status',
    ];
}
