<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;

use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;

class KaryawanComponent extends Component
{
    use WithPagination;

    public $nama, $email, $password, $nomor;
    public $nip, $JK, $alamat, $jabatan,$role;
    public $search = '';
    public $siswa_id, $user_id, $delete_id;
    public $addPage = false;
    public $editPage = false;

    protected $listeners = ['refreshTable' => '$refresh'];

    public function render()
    {
        $karyawan = Karyawan::with(['user',])
            ->whereHas('user', function($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orWhere('nip', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.karyawan-component', [
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
            'nama' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'nomor' => 'required',
            'nip' => 'required|unique:siswa',
            'JK' => 'required|in:L,P',
            'alamat' => 'required',
            'jabatan' => 'required',
            'role' => 'required',
        ]);

        $user = User::create([
            'nama' => $this->nama,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'nomor' => $this->nomor,
            'role' => $this->role,
        ]);

        // $user->assignRole('siswa');

            Karyawan::create([
            'user_id' => $user->id,
            'nip' => $this->nip,
            'JK' => $this->JK,
            'alamat' => $this->alamat,
            'jabatan' => $this->jabatan,

        ]);

        $this->resetInput();
        $this->addPage = false;
        $this->dispatch('close-modal');
        $this->dispatch('success', ['message' => 'Karyawan berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $this->editPage = true;
        $siswa = Karyawan::with('user')->findOrFail($id);
        $this->siswa_id = $id;
        $this->user_id = $siswa->user_id;
        $this->nama = $siswa->user->nama;
        $this->email = $siswa->user->email;
        $this->nomor = $siswa->user->nomor;
        $this->nip = $siswa->nip;
        $this->JK = $siswa->JK;
        $this->alamat = $siswa->alamat;
        $this->jabatan = $siswa->jabatan;
        $this->role = $siswa->role;
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'nomor' => 'required',
            'nip' => 'required|unique:siswa,nip,' . $this->siswa_id,
            'JK' => 'required|in:L,P',
            'alamat' => 'required',
            'jabatan' => 'required',
            'role' => 'required',
        ]);

        $user = User::find($this->user_id);
        $user->update([
            'nama' => $this->nama,
            'email' => $this->email,
            'nomor' => $this->nomor,
            'role' => $this->role,
        ]);

        if($this->password) {
            $user->update(['password' => Hash::make($this->password)]);
        }

        $siswa = Karyawan::find($this->siswa_id);
        $siswa->update([
            'nip' => $this->nip,
            'JK' => $this->JK,
            'alamat' => $this->alamat,
            'jabatan' => $this->jabatan,

        ]);

        $this->resetInput();
        $this->editPage = false;
        $this->dispatch('close-modal');
        $this->dispatch('success', ['message' => 'Karyawan berhasil diupdate']);
    }

    public function deleteConfirmation($id)
    {
        $this->delete_id = $id;
        $this->dispatch('show-delete-confirmation');
    }

    #[On('delete-confirmed')]
    public function destroy()
    {
        $siswa = Karyawan::findOrFail($this->delete_id);
        $user = User::findOrFail($siswa->user_id);

        $siswa->delete();
        $user->delete();

        $this->dispatch('siswa-deleted');
        session()->flash('success', 'Karyawan berhasil dihapus');
        $this->reset('delete_id');
    }

    private function resetInput()
    {
        $this->nama = '';
        $this->email = '';
        $this->password = '';
        $this->nomor = '';
        $this->nip = '';
        $this->JK = '';
        $this->alamat = '';
        $this->jabatan = '';
        $this->siswa_id = '';
        $this->user_id = '';
        $this->addPage = false; // Nonaktifkan halaman tambah data
        $this->editPage = false; // Nonaktifkan halaman edit data
    }

}
