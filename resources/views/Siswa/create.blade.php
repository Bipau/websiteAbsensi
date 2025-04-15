<form wire:submit.prevent="store">
    <div class="form-group">
        <label>NISN</label>
        <input type="text" class="form-control" wire:model="nis">
        @error('nis') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" wire:model="nama">
        @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control" wire:model="email">
        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" wire:model="password">
        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label>No. Telp</label>
        <input type="text" class="form-control" wire:model="nomor">
        @error('nomor') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label>Jenis Kelamin</label>
        <select class="form-control" wire:model="JK">
            <option value="">Pilih Jenis Kelamin</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>
        @error('JK') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label>Alamat</label>
        <textarea class="form-control" wire:model="alamat"></textarea>
        @error('alamat') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label>Kelas</label>
        <select class="form-control" wire:model="kelas_id">
            <option value="">Pilih Kelas</option>
            @foreach($kelas as $data)
                <option value="{{ $data->id }}">{{ $data->nama_kelas}}</option>
            @endforeach
        </select>
        @error('kelas_id') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>
</form>
