<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bookingGym extends Model
{
    use HasFactory;
    protected $table = "booking_gym";
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = "id";
    protected $fillable = [
        'id',
        'id_member',
        'id_details_booking',
        'tanggal_pembuatan_booking',
        'tanggal_booking',
        'tanggal_presensi',
        'status_booking',
        'status_presensi',
    ];
}
