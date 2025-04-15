<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\JadwalPelajaran;
use App\Models\Karyawan;
use App\Models\Mapel;
use App\Models\Kelas;
use Livewire\Attributes\On;
class JadwalComponent extends Component
{
    use WithPagination, WithoutUrlPagination;
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';
    public $karyawan_id, $hari, $jam_mulai, $jam_selesai, $kelas_id, $mapel_id, $id, $delete_id, $search;
    public $addPage = false, $editPage = false;

    protected $listeners = ['refreshTable' => '$refresh', 'resetInput'];

    public function render()
    {
        $jadwal = JadwalPelajaran::with(['karyawan', 'mapel', 'kelas'])
            ->whereHas('mapel', function ($query) {
                $query->where('nama_mapel', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('karyawan.user', function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%'); // Asumsi Karyawan memiliki kolom nama
            })
            ->orWhere('hari', 'like', '%' . $this->search . '%')
            ->paginate(10);

        $karyawan = Karyawan::all();
        $mapel = Mapel::all();
        $kelas = Kelas::all();
        return view('livewire.jadwal-component',[
            'jadwal' => $jadwal,
            'karyawan' => $karyawan,
            'mapel' => $mapel,
            'kelas' => $kelas,
        ]);
    }

    public function create()
    {
        $this->resetInput();
        $this->addPage = true;
    }

    public function store()
    {
        $this->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
        ], [
            'karyawan_id.required' => 'Karyawan harus dipilih',
            'karyawan_id.exists' => 'Karyawan tidak valid',
            'hari.required' => 'Hari harus diisi',
            'hari.in' => 'Hari tidak valid',
            'jam_mulai.required' => 'Jam mulai harus diisi',
            'jam_mulai.date_format' => 'Format jam mulai tidak valid',
            'jam_selesai.required' => 'Jam selesai harus diisi',
            'jam_selesai.date_format' => 'Format jam selesai tidak valid',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai',
            'kelas_id.required' => 'Kelas harus dipilih',
            'kelas_id.exists' => 'Kelas tidak valid',
            'mapel_id.required' => 'Mapel harus dipilih',
            'mapel_id.exists' => 'Mapel tidak valid',
        ]);

        JadwalPelajaran::create([
            'karyawan_id' => $this->karyawan_id,
            'hari' => $this->hari,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'kelas_id' => $this->kelas_id,
            'mapel_id' => $this->mapel_id,
        ]);

        $this->resetInput();
        $this->addPage = false;

        session()->flash('success', 'Jadwal berhasil ditambahkan');
        $this->dispatch('refresh-page');
        $this->dispatch('close-modal');
        $this->dispatch('success', ['message' => 'Jadwal berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $this->editPage = true;
        $jadwal = JadwalPelajaran::findOrFail($id);
        $this->id = $id;
        $this->karyawan_id = $jadwal->karyawan_id;
        $this->hari = $jadwal->hari;
        $this->jam_mulai = $jadwal->jam_mulai;
        $this->jam_selesai = $jadwal->jam_selesai;
        $this->kelas_id = $jadwal->kelas_id;
        $this->mapel_id = $jadwal->mapel_id;
    }

    public function update()
    {
        $this->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
        ], [
            'karyawan_id.required' => 'Karyawan harus dipilih',
            'karyawan_id.exists' => 'Karyawan tidak valid',
            'hari.required' => 'Hari harus diisi',
            'hari.in' => 'Hari tidak valid',
            'jam_mulai.required' => 'Jam mulai harus diisi',
            'jam_mulai.date_format' => 'Format jam mulai tidak valid',
            'jam_selesai.required' => 'Jam selesai harus diisi',
            'jam_selesai.date_format' => 'Format jam selesai tidak valid',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai',
            'kelas_id.required' => 'Kelas harus dipilih',
            'kelas_id.exists' => 'Kelas tidak valid',
            'mapel_id.required' => 'Mapel harus dipilih',
            'mapel_id.exists' => 'Mapel tidak valid',
        ]);

        $jadwal = JadwalPelajaran::findOrFail($this->id);
        $jadwal->update([
            'karyawan_id' => $this->karyawan_id,
            'hari' => $this->hari,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'kelas_id' => $this->kelas_id,
            'mapel_id' => $this->mapel_id,
        ]);

        $this->resetInput();
        $this->editPage = false;

        session()->flash('success', 'Jadwal berhasil diubah');
        $this->dispatch('close-modal');
        $this->dispatch('success', ['message' => 'Jadwal berhasil diubah']);
    }

    #[On('delete-confirmed')]
    public function destroy()
    {
        $jadwal = JadwalPelajaran::findOrFail($this->delete_id);
        $jadwal->forceDelete();

        session()->flash('success', 'Jadwal berhasil dihapus');
        $this->dispatch('jadwal-deleted');
        $this->reset('delete_id');
    }

    public function deleteConfirmation($id)
    {
        $this->delete_id = $id;
        $this->dispatch('show-delete-confirmation');
    }

    public function resetInput()
    {
        $this->id = '';
        $this->karyawan_id = '';
        $this->hari = '';
        $this->jam_mulai = '';
        $this->jam_selesai = '';
        $this->kelas_id = '';
        $this->mapel_id = '';
        $this->addPage = false;
        $this->editPage = false;
    }
}
