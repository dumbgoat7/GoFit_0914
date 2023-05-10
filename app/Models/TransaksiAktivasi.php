<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiAktivasi extends Model
{
    use HasFactory;
    protected $table = "transaksi_aktivasi";
    public $timestamps = false;
    protected $primaryKey = "no_struk_akt";
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'no_struk_akt',
        'id_kasir',
        'id_member',
        'tanggal_transaksi',
        'biaya_transaksi',
    ];

    public function Pegawai(){
        return $this->belongsTo(User::class, 'id_kasir', 'id_pegawai');
    }

    public function Member(){
        return $this->belongsTo(Instruktur::class, 'id_member', 'id_member');
    }
}
