<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Karyawan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'karyawan';
    protected $fillable = [
        'user_id',
        'nip',
        'JK',
        'alamat',
        'jabatan',
        'nomor',
    ];

    /**
     * Get the user that owns the Karyawan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function guruMapel()
    {
        return $this->hasMany(GuruMapel::class);
    }
    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }
}
