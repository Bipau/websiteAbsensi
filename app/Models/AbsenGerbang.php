<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenGerbang extends Model
{

    protected $table = 'absen_gerbang';

    protected $fillable = [
        'user_id',
        'foto',
        'jam_masuk',
        'jam_keluar',
        'tanggal',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
