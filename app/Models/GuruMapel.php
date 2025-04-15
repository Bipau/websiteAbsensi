<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruMapel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>

     */

    protected $table = 'guru_mapel';
    protected $fillable = [
        'karyawan_id',
        'mapel_id',
    ];

    /**
     * Get the karyawan that owns the GuruMapel.
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }
}
