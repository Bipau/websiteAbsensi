<!-- filepath: resources/views/Karyawan/edit.blade.php -->
<form wire:submit.prevent="update">
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama" wire:model="nama" placeholder="Masukkan nama">
        @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" wire:model="email" placeholder="Masukkan email">
        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
        <input type="password" class="form-control" id="password" wire:model="password" placeholder="Masukkan password">
        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="nomor" class="form-label">Nomor</label>
        <input type="text" class="form-control" id="nomor" wire:model="nomor" placeholder="Masukkan nomor telepon">
        @error('nomor') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="nip" class="form-label">NIP</label>
        <input type="text" class="form-control" id="nip" wire:model="nip" placeholder="Masukkan NIP">
        @error('nip') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="JK" class="form-label">Jenis Kelamin</label>
        <select class="form-control" id="JK" wire:model="JK">
            <option value="">Pilih Jenis Kelamin</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>
        @error('JK') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea class="form-control" id="alamat" wire:model="alamat" placeholder="Masukkan alamat"></textarea>
        @error('alamat') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="jabatan" class="form-label">Jabatan</label>
        <input type="text" class="form-control" id="jabatan" wire:model="jabatan" placeholder="Masukkan jabatan">
        @error('jabatan') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-control" id="role" wire:model="role">
            <option value="">Pilih Role</option>
            <option value="kurikulum">Kurikulum</option>
            <option value="guru">Guru</option>
            <option value="walikelas">Wali Kelas</option>
        </select>
        @error('role') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
