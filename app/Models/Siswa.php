<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

     protected $table = 'siswa';
    protected $fillable = [
        'user_id',
        'nis',
        'JK',
        'alamat',
        'kelas_id',
    ];

    /**
     * Get the user that owns the Siswa.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function absenKelas(): HasMany
    {
        return $this->hasMany(AbsenKelas::class);
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
