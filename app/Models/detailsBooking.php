<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detailsBooking extends Model
{
    use HasFactory;
    protected $table = "details_booking_gym";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'slot_waktu',
        'sisa_kapasitas',
    ];
}
