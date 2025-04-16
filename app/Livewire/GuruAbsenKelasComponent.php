<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Siswa;
use App\Models\AbsenKelas;
use App\Models\Kelas;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class GuruAbsenKelasComponent extends Component
{
    public $kelas_id = '';
    public $jadwal_id = '';
    public $tanggal;
    public $siswaList = [];
    public $status = [];  // Change from $hadir to $status

    public function mount()
    {
        $this->tanggal = now()->format('Y-m-d');
        // Initialize all students as 'Alpha' by default
        if ($this->siswaList) {
            foreach ($this->siswaList as $siswa) {
                $this->status[$siswa->id] = 'Alpha';
            }
        }
    }

    public function updatedKelasId()
    {
        $this->reset('siswaList', 'status');
        if (!empty($this->kelas_id)) {
            $this->siswaList = Siswa::where('kelas_id', $this->kelas_id)->get();
            
            // Pre-fill existing attendance data if any
            if (!empty($this->jadwal_id) && !empty($this->tanggal)) {
                $this->loadExistingAttendance();
            }
        }
    }

    public function updatedJadwalId()
    {
        if (!empty($this->kelas_id) && !empty($this->tanggal)) {
            $this->loadExistingAttendance();
        }
    }

    public function updatedTanggal()
    {
        if (!empty($this->kelas_id) && !empty($this->jadwal_id)) {
            $this->loadExistingAttendance();
        }
    }

    protected function loadExistingAttendance()
    {
        $existingAttendance = AbsenKelas::where([
            'kelas_id' => $this->kelas_id,
            'jadwal_id' => $this->jadwal_id,
            'tanggal' => $this->tanggal
        ])->get();

        foreach ($existingAttendance as $attendance) {
            $this->status[$attendance->siswa_id] = $attendance->status;
        }
    }

    public function pilihSemua($status = 'Hadir')
    {
        foreach ($this->siswaList as $siswa) {
            $this->status[$siswa->id] = $status;
        }
    }

    public function simpan()
    {
        $this->validate([
            'kelas_id' => 'required',
            'jadwal_id' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            // First, reset attendance for this class, date, and schedule
            AbsenKelas::where([
                'kelas_id' => $this->kelas_id,
                'tanggal' => $this->tanggal,
                'jadwal_id' => $this->jadwal_id
            ])->delete();

            foreach ($this->status as $siswa_id => $status) {
                AbsenKelas::create([
                    'siswa_id' => $siswa_id,
                    'jadwal_id' => $this->jadwal_id,
                    'kelas_id' => $this->kelas_id,
                    'tanggal' => $this->tanggal,
                    'jam_absen' => now(),
                    'status' => $status
                ]);
            }

            DB::commit();
            session()->flash('message', 'Absensi berhasil disimpan!');
            
            // Reset form after successful save
            $this->reset(['status']);
            $this->mount();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $jadwalList = collect();
        
        if (!empty($this->kelas_id)) {
            // Check if JadwalPelajaran has kelas_id field
            if (Schema::hasColumn('jadwal_pelajaran', 'kelas_id')) {
                $jadwalList = JadwalPelajaran::where('kelas_id', $this->kelas_id)->get();
            } else {
                $jadwalList = JadwalPelajaran::all();
            }
        }
        
        return view('livewire.guru-absen-kelas-component', [
            'kelasList' => Kelas::all(),
            'jadwalList' => JadwalPelajaran::select('jadwal_pelajaran.id', 'mapel.kode_mapel', 'mapel.nama_mapel', 'jadwal_pelajaran.hari')
                ->join('mapel', 'mapel.id', '=', 'jadwal_pelajaran.mapel_id')
                ->get(),
        ]);
    }
}