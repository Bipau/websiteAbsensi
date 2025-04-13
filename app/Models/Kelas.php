<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $fillable = ['nama_kelas', 'tingkat', 'jurusan', 'wali_kelas_id'];

    public function waliKelas()
    {
        return $this->belongsTo(Karyawan::class, 'wali_kelas_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    public function absenKelas()
    {
        return $this->hasMany(AbsenKelas::class);
    }
}
