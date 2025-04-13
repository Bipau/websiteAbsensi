<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenKelas extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'siswa_id',
        'jadwal_pelajaran_id',
        'jam_masuk',
        'jam_keluar',
        'tanggal',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jadwalPelajaran()
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }
}
