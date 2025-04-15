<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\Mapel;
use App\Models\GuruMapel;
use Livewire\Attributes\On;

class MapelComponent extends Component
{

    use WithPagination, WithoutUrlPagination;

    protected $listeners = ['refreshTable' => '$refresh'];

    protected $paginationTheme = "bootstrap";
    public $kode_mapel, $nama_mapel, $jurusan_id, $id, $delete_id, $search;
    public $addPage, $editPage = false;

    public function render()
    {

        $mapel = Mapel::where('nama_mapel', 'like', '%' . $this->search . '%')
            ->orWhere('kode_mapel', 'like', '%' . $this->search . '%')
            ->paginate(10);

        $guruMapel = GuruMapel::all();

        // Mengirim data ke view
        return view('livewire.mapel-component', [
            'mapel' => $mapel,
            'guruMapel' => $guruMapel,  

        ]);

    }
    public function create(){

        $this->resetInput();
        $this->addPage = true;

    }


    public function store()
    {
        $this->validate([
            'kode_mapel' => 'required|unique:mapel,kode_mapel,',
            'nama_mapel' => 'required'
        ], [
            'kode_mapel.required' => 'Kode Mapel tidak boleh kosong',
            'kode_mapel.unique' => 'Kode Mapel sudah ada',
            'nama_mapel.required' => 'Nama Mapel tidak boleh kosong'
        ]);

        Mapel::create([
            'kode_mapel' => $this->kode_mapel,
            'nama_mapel' => $this->nama_mapel
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
        $jurusan = Mapel::findOrFail($this->delete_id);
        $jurusan->forcedelete();

        $this->dispatch('jurusan-deleted');

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
        $jurusan = Mapel::findOrFail($id);
        $this->jurusan_id = $id;
        $this->kode_mapel = $jurusan->kode_mapel;
        $this->nama_mapel = $jurusan->nama_mapel;
    }

    public function update()
    {
        $jurusan = Mapel::findOrFail($this->jurusan_id);

        $this->validate([
            'kode_mapel' => 'required|unique:mapel,kode_mapel,' . $this->jurusan_id,
            'nama_mapel' => 'required'
        ], [
            'kode_mapel.required' => 'Kode Mapel tidak boleh kosong',
            'kode_mapel.unique' => 'Kode Mapel sudah ada',
            'nama_mapel.required' => 'Nama Mapel tidak boleh kosong'
        ]);


        $jurusan->update([
            'kode_mapel' => $this->kode_mapel,
            'nama_mapel' => $this->nama_mapel
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
        $this->kode_mapel = '';
        $this->nama_mapel = '';
        $this->addPage = false; // Nonaktifkan halaman tambah data
        $this->editPage = false; // Nonaktifkan halaman edit data

    }

}
