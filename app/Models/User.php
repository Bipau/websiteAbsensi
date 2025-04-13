<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'nomor',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return $this->role === $roles;
    }
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superAdmin';
    }
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }
    public function isKaryawan(): bool
    {
        return $this->role === 'karyawan';
    }
    public function isKurikulum(): bool
    {
        return $this->role === 'kurikulum';
    }
    public function isWaliKelas(): bool
    {
        return $this->role === 'walikelas';
    }

    //relasi antar table
    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class);
    }
    public function karyawan(): HasOne
    {
        return $this->hasOne(Karyawan::class);
    }
    public function absenKelas(): HasMany
    {
        return $this->hasMany(AbsenKelas::class);
    }
    public function jadwalPelajaran(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class);
    }
    public function mapel(): HasMany
    {
        return $this->hasMany(Mapel::class);
    }
    public function absenGerbang()
    {
        return $this->hasMany(AbsenGerbang::class);
    }
    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
}
