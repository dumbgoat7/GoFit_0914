<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositKelas extends Model
{
    use HasFactory;
    protected $table = "transaksi_deposit_kelas";
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = "no_struk";
    protected $fillable = [
        'no_struk',
        'id_promo',
        'id_kasir',
        'id_member',
        'id_kelas',
        'tanggal_transaksi',
        'deposit',
        'deposit_kelas',
        'masa_berlaku',
        'status'
    ];

    public function Promo(){
        return $this->belongsTo(Promo::class, 'id_promo', 'id');
    }
    public function Pegawai(){
        return $this->belongsTo(User::class, 'id_kasir', 'id_pegawai');
    }
    public function Member(){
        return $this->belongsTo(Member::class, 'id_member', 'id_member');
    }
    public function Kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

}
