<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\Kelas;
use App\Models\Karyawan;
use App\Models\User;
use Livewire\Attributes\On;

class KelasComponent extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $listeners = ['refreshTable' => '$refresh'];

    protected $paginationTheme = "bootstrap";
    public $nama_kelas, $kelas_property, $tingkat, $id, $wali_kelas_id, $delete_id, $search, $jurusan;
    public $addPage, $editPage = false;

    public function render()
    {
        $query = Kelas::query();

        if ($this->search) {
            $query->where('nama_kelas', 'like', '%' . $this->search . '%')
                ->orWhere('tingkat', 'like', '%' . $this->search . '%')
                ->orWhere('jurusan', 'like', '%' . $this->search . '%');
        }

        $kelasData = $query->paginate(10);

        return view('livewire.kelas-component', [
            'kelas' => $kelasData,
            'karyawan' => Karyawan::whereHas('user', function ($query) {
            $query->where('role', 'walikelas');
            })->get()
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
            'nama_kelas' => 'required',
            'tingkat' => 'required',
            'jurusan' => 'required',
            'wali_kelas_id' => 'required',
        ]);

        Kelas::create([
            'nama_kelas' => $this->nama_kelas,
            'tingkat' => $this->tingkat,
            'jurusan' => $this->jurusan,
            'wali_kelas_id' => $this->wali_kelas_id,
        ]);

        $this->resetInput();
        $this->addPage = false;

        session()->flash('success', 'Data berhasil ditambahkan');
        $this->dispatch('refresh-page');
        $this->dispatch('close-modal');
        $this->dispatch('success', ['message' => 'Data berhasil ditambahkan']);
    }

    #[On('delete-confirmed')]
    public function destroy()
    {
        //delete
        $kelas = Kelas::findOrFail($this->delete_id);
        $kelas->forcedelete();

        $this->dispatch('kelas-deleted');

        // Flash success message
        session()->flash('success', 'Berhasil Menghapus Data.');

        // Reset form or state if needed
        $this->reset('delete_id');
    }

    public function deleteConfirmation($id)
    {
        $this->delete_id = $id;


        // Dispatch browser event for SweetAlert
        $this->dispatch('show-delete-confirmation');
    }

    public function edit($id)
    {
        $this->editPage = true;
        $kelas = Kelas::findOrFail($id);
        $this->id = $id;
        $this->nama_kelas = $kelas->nama_kelas;
        $this->tingkat = $kelas->tingkat;
        $this->jurusan = $kelas->jurusan;
        $this->wali_kelas_id = $kelas->wali_kelas_id;
    }

    public function update()
    {
        $kelas = Kelas::findOrFail($this->id);

        $this->validate([
            'nama_kelas' => 'required',
            'tingkat' => 'required',
            'jurusan' => 'required',
            'wali_kelas_id' => 'required',
        ]);

        $kelas->update([
            'nama_kelas' => $this->nama_kelas,
            'tingkat' => $this->tingkat,
            'jurusan' => $this->jurusan,
            'wali_kelas_id' => $this->wali_kelas_id,
        ]);

        $this->resetInput();
        $this->editPage = false;

        session()->flash('success', 'Data berhasil diubah');
        $this->dispatch('close-modal');
        $this->dispatch('success', ['message' => 'Data berhasil diubah']);
    }

    private function resetInput()
    {
        $this->id = '';
        $this->nama_kelas = '';
        $this->tingkat = '';
        $this->jurusan = '';
        $this->wali_kelas_id = '';
        $this->delete_id = '';
        $this->search = '';

        $this->addPage = false; // Nonaktifkan halaman tambah data
        $this->editPage = false; // Nonaktifkan halaman edit data

    }
}
