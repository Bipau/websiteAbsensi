<?php

namespace App\Livewire;

use App\Models\AbsenKelas;
use App\Models\TokenQr;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SiswaAbsenKelasComponent extends Component
{
    public $message = null;
    public $manualToken;


    public function prosesAbsen($token)
    {
        $qr = TokenQr::where('token_qr', $token)
            ->where('expired_at', '>', now())
            ->first();

        if (!$qr) {
            $this->message = "Token tidak valid atau sudah kadaluarsa.";
            return;
        }

        $already = AbsenKelas::where('siswa_id', Auth::id())
            ->whereDate('tanggal', today())
            ->where('jadwal_id', $qr->jadwal_id)
            ->exists();

        if ($already) {
            $this->message = "Kamu sudah absen hari ini.";
            return;
        }

        AbsenKelas::create([
            'siswa_id' => Auth::id(),
            'kelas_id' => $qr->kelas_id,
            'jadwal_id' => $qr->jadwal_id, // Pastikan jadwal_id disertakan
            'tanggal' => now()->toDateString(),
            'jam_absen' => now()->toTimeString(),
        ]);

        $this->message = "Absen berhasil. Terima kasih!";
        $this->manualToken = null;
    }

    public function prosesAbsenManual()
    {
        $this->prosesAbsen($this->manualToken);
    }

    public function render()
    {
        return view('livewire.siswa-absen-kelas-component');
    }
}
