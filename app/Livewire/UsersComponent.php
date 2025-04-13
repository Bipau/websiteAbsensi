<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;


class UsersComponent extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = "bootstrap";
    public $addPage = false, $editPage = false;
    public $nama, $nomor, $email, $password, $role, $search, $id, $delete_id;
    protected $listeners = ['refreshTable' => '$refresh'];

    public function render()
    {

        $users = User::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('nomor', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('role', 'like', '%' . $this->search . '%')
            ->paginate(5); // Sesuaikan jumlah item per halaman

        return view('livewire.users-component',['users' => $users]);
    }

    public function create()
    {
        $this->resetInput();
        $this->addPage = true;
    }

    public function store()
    {
        // Validasi input
        $this->validate([
            'nama' => 'required',
            'nomor' => 'required|unique:users,nomor|numeric',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        // Simpan data user
        User::create([
            'nama' => $this->nama,
            'nomor' => $this->nomor,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role
        ]);

        // Reset form dan beri pesan sukses
        $this->resetInput();
        $this->addPage = false;
        $this->dispatch('refreshTable');
        $this->dispatch('close-modal');
        $this->dispatch('success', ['message' => 'User berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $this->editPage = true;
        $user = User::findOrFail($id);
        $this->id = $id;
        $this->nama = $user->nama;
        $this->nomor = $user->nomor;
        $this->email = $user->email;
        $this->role = $user->role;
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required',
            'nomor' => 'required|numeric|unique:users,nomor,' . $this->id,
            'email' => 'required|email|unique:users,email,' . $this->id,
            'role' => 'required'
        ]);

        $user = User::find($this->id);
        $data = [
            'nama' => $this->nama,
            'nomor' => $this->nomor,
            'email' => $this->email,
            'role' => $this->role
        ];

        if($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        $this->resetInput();
        $this->editPage = false;
        $this->dispatch('close-modal');
        $this->dispatch('success', ['message' => 'User berhasil diupdate']);
    }

    public function deleteConfirmation($id)
    {
        $this->delete_id = $id;
        $this->dispatch('show-delete-confirmation');
    }

    #[On('delete-confirmed')]
    public function destroy()
    {
        $user = User::findOrFail($this->delete_id);
        $user->delete();

        $this->dispatch('user-deleted');
        session()->flash('success', 'User berhasil dihapus');
        $this->reset('delete_id');
    }

    private function resetInput()
    {
        $this->nama = '';
        $this->nomor = '';
        $this->email = '';
        $this->password = '';
        $this->role = '';
        $this->id = '';
    }
}
