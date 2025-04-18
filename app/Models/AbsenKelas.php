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
        'jadwal_id',
        'kelas_id',
        'tanggal',
        'jam_absen',
        'status',
    ];

    // public function siswa()
    // {
    //     return $this->belongsTo(Siswa::class);
    // }
    public function user()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
    

    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    protected $casts = [
        'tanggal' => 'date',
        'jam_absen' => 'datetime',
    ];
}
