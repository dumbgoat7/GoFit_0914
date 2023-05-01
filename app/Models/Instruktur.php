<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Instruktur extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    public $timestamps = false;
    protected $table = "instruktur";
    protected $primaryKey = "id";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_instruktur',
        'no_telp',
        'alamat_instruktur',
        'gaji_instruktur',
        'email_instruktur',
        'tanggal_lahir',
        'username',
        'password',
    ];
}
