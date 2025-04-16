<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenQr extends Model
{
    protected $table = 'token_qr';
    protected $fillable = ['token_qr', 'jadwal_id', 'kelas_id', 'expired_at'];
    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
