<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bookingKelas extends Model
{
    use HasFactory;
    protected $table = "booking_kelas";
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = "id";
    protected $fillable = [
        'id',
        'id_member',
        'id_jadwal',
        'tanggal_pembuatan_booking',
        'tanggal_booking',
        'tanggal_presensi',
        'status_booking',
        'metode_pembayaran',
    ];
}
