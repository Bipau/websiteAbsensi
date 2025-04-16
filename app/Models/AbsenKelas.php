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
    protected $table = 'absen_kelas';
    protected $fillable = [
        'siswa_id',
        'jadwal_id',  // Add this
        'kelas_id',   // Add this
        'tanggal',
        'jam_absen',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
