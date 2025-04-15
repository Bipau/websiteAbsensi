<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

     protected $table ='jadwal_pelajaran';

     
    protected $fillable = [
        'karyawan_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kelas_id',
        'mapel_id',
    ];
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function guruMapel()
    {
        return $this->belongsTo(GuruMapel::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
