<!-- filepath: resources/views/Kelas/create.blade.php -->
<form wire:submit.prevent="store">
    <div class="mb-3">
        <label for="nama_kelas" class="form-label">Nama Kelas</label>
        <input type="text" class="form-control" id="nama_kelas" wire:model="nama_kelas" placeholder="Masukkan nama kelas">
        @error('nama_kelas') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="tingkat" class="form-label">Tingkat</label>
        <input type="text" class="form-control" id="tingkat" wire:model="tingkat" placeholder="Masukkan tingkat kelas">
        @error('tingkat') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="jurusan" class="form-label">Jurusan</label>
        <input type="text" class="form-control" id="jurusan" wire:model="jurusan" placeholder="Masukkan jurusan">
        @error('jurusan') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="wali_kelas_id" class="form-label">Wali Kelas</label>
        <select class="form-control" id="wali_kelas_id" wire:model="wali_kelas_id">
            <option value="">Pilih Wali Kelas</option>
            @foreach ($karyawan as $item)
                <option value="{{ $item->id }}">{{ $item->user->nama }}</option>
            @endforeach
        </select>
        @error('wali_kelas_id') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
