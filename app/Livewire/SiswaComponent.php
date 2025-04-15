<?php

namespace App\Livewire;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;

class SiswaComponent extends Component
{
    use WithPagination;

    public $nama, $email, $password, $nomor;
    public $nis, $JK, $alamat, $tingkat_kelas, $kelas_id;
    public $search = '';
    public $siswa_id, $user_id, $delete_id;
    public $addPage = false;
    public $editPage = false;

    protected $listeners = ['refreshTable' => '$refresh'];

    public function render()
    {
        $siswas = Siswa::with(['user', 'kelas'])
            ->whereHas('user', function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orWhere('nis', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.siswa-component', [
            'siswas' => $siswas,
            'kelas' => Kelas::all()
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
            'nama' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'nomor' => 'required',
            'nis' => 'required|unique:siswa',
            'JK' => 'required|in:L,P',
            'alamat' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $user = User::create([
            'nama' => $this->nama,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'nomor' => $this->nomor,
            'role' => 'siswa',
        ]);

        Siswa::create([
            'user_id' => $user->id,
            'nis' => $this->nis,
            'JK' => $this->JK,
            'alamat' => $this->alamat,
            'kelas_id' => $this->kelas_id,
        ]);

        $this->resetInput();
        $this->dispatch('close-modal');
        session()->flash('success', 'Data siswa berhasil ditambahkan.');
    }



    public function edit($id)
    {
        $this->editPage = true;
        $siswa = Siswa::with('user')->findOrFail($id);
        $this->siswa_id = $id;
        $this->user_id = $siswa->user_id;
        $this->nama = $siswa->user->nama;
        $this->email = $siswa->user->email;
        $this->nomor = $siswa->user->nomor;
        $this->nis = $siswa->nis;
        $this->JK = $siswa->JK;
        $this->alamat = $siswa->alamat;
        $this->tingkat_kelas = $siswa->tingkat_kelas;
        $this->kelas_id = $siswa->kelas_id;
    }


    public function update()
    {
        $this->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'nomor' => 'required',
            'nis' => 'required|unique:siswa,nis,' . $this->siswa_id,
            'JK' => 'required|in:L,P',
            'alamat' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $user = User::findOrFail($this->user_id);
        $user->update([
            'nama' => $this->nama,
            'email' => $this->email,
            'nomor' => $this->nomor,
        ]);

        $siswa = Siswa::findOrFail($this->siswa_id);
        $siswa->update([
            'nis' => $this->nis,
            'JK' => $this->JK,
            'alamat' => $this->alamat,
            'kelas_id' => $this->kelas_id,
        ]);

        $this->resetInput();
        $this->editPage = false;

        session()->flash('success', 'Data siswa berhasil diperbarui.');
        $this->dispatch('close-modal');
    }

    public function deleteConfirmation($id)
    {
        $this->delete_id = $id;
        $this->dispatch('show-delete-confirmation');
    }

    #[On('delete-confirmed')]
    public function destroy()
    {
        $siswa = Siswa::findOrFail($this->delete_id);
        $user = User::findOrFail($siswa->user_id);

        $siswa->delete();
        $user->delete();

        $this->dispatch('siswa-deleted');
        session()->flash('success', 'Siswa berhasil dihapus');
        $this->reset('delete_id');
    }

    private function resetInput()
    {
        $this->nama = '';
        $this->email = '';
        $this->password = '';
        $this->nomor = '';
        $this->nis = '';
        $this->JK = '';
        $this->alamat = '';
        $this->tingkat_kelas = '';
        $this->kelas_id = '';
        $this->siswa_id = '';
        $this->user_id = '';
        $this->addPage = false; // Nonaktifkan halaman tambah data
        $this->editPage = false; // Nonaktifkan halaman edit data
    }
}
