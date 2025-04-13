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
    protected $fillable = [
        'guru_mapel_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kelas',
        'mapel_id',
    ];

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
