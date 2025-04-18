<?php

namespace App\Livewire;

use App\Models\AbsenKelas;
use App\Models\TokenQr;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SiswaAbsenKelasComponent extends Component
{
    public $message = null;
    public $manualToken = '';
    public $scanActive = false;

    protected $listeners = [
        'tokenScanned' => 'setToken',
        'scannerStarted' => 'setScannerStatus',
        'scannerStopped' => 'setScannerStatus'
    ];

    public function setScannerStatus($status)
    {
        if (is_array($status) && isset($status['status'])) {
            $this->scanActive = $status['status'];
        } else {
            $this->scanActive = (bool)$status;
        }
    }

    public function setToken($token)
    {
        if (is_array($token) && isset($token['token'])) {
            $this->manualToken = $token['token'];
        } else {
            $this->manualToken = $token;
        }
        $this->prosesAbsenManual();
    }

    public function prosesAbsen($token)
    {
        try {
            // Validate token
            if (empty($token)) {
                $this->message = "Token tidak boleh kosong";
                return;
            }

            $qr = TokenQr::where('token_qr', $token)
                ->where('expired_at', '>', now())
                ->first();

            if (!$qr) {
                $this->message = "Token tidak valid atau sudah kadaluarsa.";
                return;
            }

            // Check if already present
            $already = AbsenKelas::where('siswa_id', Auth::id())
                ->whereDate('tanggal', today())
                ->where('jadwal_id', $qr->jadwal_id)
                ->exists();

            if ($already) {
                $this->message = "Kamu sudah absen hari ini.";
                return;
            }

            // Record attendance
            AbsenKelas::create([
                'siswa_id' => Auth::id(),
                'kelas_id' => $qr->kelas_id,
                'jadwal_id' => $qr->jadwal_id,
                'tanggal' => now()->toDateString(),
                'jam_absen' => now()->toTimeString(),
                'status' => 'Hadir'
            ]);

            $this->message = "Absen berhasil. Terima kasih!";
            $this->manualToken = '';

            // Show success notification
            $this->dispatch('show-success', [
                'message' => 'Absen berhasil tercatat!'
            ]);

        } catch (\Exception $e) {
            $this->message = "Terjadi kesalahan: " . $e->getMessage();
            
            $this->dispatch('show-error', [
                'message' => 'Gagal melakukan absensi: ' . $e->getMessage()
            ]);
        }
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