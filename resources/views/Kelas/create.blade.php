<!-- filepath: resources/views/Kelas/create.blade.php -->
<form wire:submit.prevent="store">
    <div class="mb-3">
        <label for="nama_kelas" class="form-label">Nama Kelas</label>
        <input type="text" class="form-control" id="nama_kelas" wire:model="nama_kelas" placeholder="Masukkan nama kelas">
        @error('nama_kelas') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="tingkat" class="form-label">Tingkat</label>
        <select class="form-control" id="tingkat" wire:model="tingkat">
            <option value="">Pilih Tingkat</option>
            <option value="X">X</option>
            <option value="XI">XI</option>
            <option value="XII">XII</option>
        </select>
        @error('tingkat') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        <label for="jurusan" class="form-label">Jurusan</label>
        <select class="form-control" id="jurusan" wire:model="jurusan">
            <option value="">Pilih Jurusan</option>
            <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
            <option value="Teknik Komputer Jaringan">Teknik Komputer Jaringan</option>
            <option value="Teknik Kendaraan Ringan">Teknik Kendaraan Ringan</option>
            <option value="Teknik Permesinan">Teknik Permesinan</option>
            <option value="Teknik Sepede Motor">Teknik Sepede Motor</option>
        </select>
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
