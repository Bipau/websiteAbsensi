<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mapel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    use HasFactory;
    protected $table = 'mapel';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
    ];

    /**
     * Get the jadwal pelajaran associated with the mapel.
     */
    public function jadwalPelajaran(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class);
    }
    public function guruMapel()
    {
        return $this->hasMany(GuruMapel::class);
    }
}
