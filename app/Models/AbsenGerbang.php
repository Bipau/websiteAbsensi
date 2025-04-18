<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AbsenGerbang extends Model
{

    protected $table = 'absen_gerbang';

    protected $fillable = [
        'user_id',
        'jam_masuk',
        'jam_keluar',
        'tanggal',
        'latitude',
        'longitude', // Perbaiki dari 'longtitude' menjadi 'longitude'
        'status',
        'foto',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getFotoUrlAttribute()
    {
        return $this->foto ? Storage::url($this->foto) : null;
    }
}
