<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TokenQr;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GenerateQrCodeComponent extends Component
{
    public $kelas_id;
    public $jadwal_id;
    public $token;
    public $expiredAt;

    public function generate()
    {
        $this->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'jadwal_id' => 'required|exists:jadwal_pelajaran,id',
        ]);

        $this->token = Str::uuid()->toString();
        $this->expiredAt = Carbon::now()->addMinutes(10);

        TokenQr::create([
            'token_qr' => $this->token,
            'kelas_id' => $this->kelas_id,
            'jadwal_id' => $this->jadwal_id,
            'expired_at' => $this->expiredAt,
        ]);
    }

    public function render()
    {
        return view('livewire.generate-qr-code-component',[
            'kelasList' => Kelas::all(),
            'jadwalList' => JadwalPelajaran::all(),
        ]);
    }
}
