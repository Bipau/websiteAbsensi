<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\Mapel;
use App\Models\GuruMapel;
use App\Models\Karyawan;
use Livewire\Attributes\On;

class GuruMapelComponent extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $listeners = ['refreshTable' => '$refresh'];

    protected $paginationTheme = "bootstrap";
    public $kode_mapel, $nama_mapel, $karyawan_id, $mapel_id, $delete_id, $search;
    public $addPage, $editPage = false;

    public function render()
    {
        $guruMapel = GuruMapel::with(['karyawan', 'mapel'])
            ->when($this->search, function ($query) {
            $query->whereHas('karyawan.user', function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%');
            })->orWhereHas('mapel', function ($q) {
                $q->where('nama_mapel', 'like', '%' . $this->search . '%');
            });
            })
            ->paginate(10);

        $mapel = Mapel::all();
        $karyawan = Karyawan::all();

        return view('livewire.guru-mapel-component', [
            'guruMapel' => $guruMapel,
            'mapel' => $mapel,
            'karyawan' => $karyawan,
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
            'mapel_id' => 'required|exists:mapel,id',
        ], [
            'karyawan_id.required' => 'Karyawan harus dipilih.',
            'karyawan_id.exists' => 'Karyawan tidak valid.',
            'mapel_id.required' => 'Mata pelajaran harus dipilih.',
            'mapel_id.exists' => 'Mata pelajaran tidak valid.',
        ]);

        GuruMapel::create([
            'karyawan_id' => $this->karyawan_id,
            'mapel_id' => $this->mapel_id,
        ]);

        $this->resetInput();
        $this->addPage = false;

        session()->flash('success', 'Data berhasil ditambahkan');
        $this->dispatch('refresh-page');
        $this->dispatch('close-modal');
        $this->dispatch('success', ['message' => 'Data berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $this->editPage = true;
        $guruMapel = GuruMapel::findOrFail($id);
        $this->karyawan_id = $guruMapel->karyawan_id;
        $this->mapel_id = $guruMapel->mapel_id;
    }

    public function update()
    {
        $guruMapel = GuruMapel::findOrFail($this->delete_id);

        $this->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'mapel_id' => 'required|exists:mapel,id',
        ], [
            'karyawan_id.required' => 'Karyawan harus dipilih.',
            'karyawan_id.exists' => 'Karyawan tidak valid.',
            'mapel_id.required' => 'Mata pelajaran harus dipilih.',
            'mapel_id.exists' => 'Mata pelajaran tidak valid.',
        ]);

        $guruMapel->update([
            'karyawan_id' => $this->karyawan_id,
            'mapel_id' => $this->mapel_id,
        ]);

        $this->resetInput();
        $this->editPage = false;

        session()->flash('success', 'Data berhasil diubah');
        $this->dispatch('close-modal');
        $this->dispatch('success', ['message' => 'Data berhasil diubah']);
    }

    #[On('delete-confirmed')]
    public function destroy()
    {
        $guruMapel = GuruMapel::findOrFail($this->delete_id);
        $guruMapel->delete();

        session()->flash('success', 'Data berhasil dihapus');
        $this->dispatch('refreshTable');
    }

    private function resetInput()
    {
        $this->karyawan_id = '';
        $this->mapel_id = '';
        $this->addPage = false;
        $this->editPage = false;
    }
}
