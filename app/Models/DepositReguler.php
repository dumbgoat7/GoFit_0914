<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositReguler extends Model
{
    use HasFactory;
    protected $table = "transaksi_deposit_reguler";
    public $timestamps = false;
    protected $primaryKey = "no_struk";
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_struk',
        'id_promo',
        'id_kasir',
        'id_member',
        'tanggal_transaksi',
        'deposit',
        'bonus_deposit',
        'sisa_deposit',
        'total_deposit',
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
}
