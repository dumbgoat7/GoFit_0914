<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;
    protected $table = "promo";
    public $timestamps = false;
    protected $primaryKey = "id";
    protected $fillable = [
        'nama_promo',
        'keterangan_promo',
    ];
}
