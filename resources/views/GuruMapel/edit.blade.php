<!-- filepath: resources/views/GuruMapel/edit.blade.php -->
<form wire:submit.prevent="update">
    <div class="form-group">
        <label for="karyawan_id">Nama Karyawan</label>
        <select class="form-control" id="karyawan_id" wire:model="karyawan_id">
            <option value="">Pilih Karyawan</option>
            @foreach ($karyawan as $item)
                <option value="{{ $item->id }}">{{ $item->user->nama }}</option>
            @endforeach
        </select>
        @error('karyawan_id') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label for="mapel_id">Mata Pelajaran</label>
        <select class="form-control" id="mapel_id" wire:model="mapel_id">
            <option value="">Pilih Mata Pelajaran</option>
            @foreach ($mapel as $item)
                <option value="{{ $item->id }}">{{ $item->nama_mapel }}</option>
            @endforeach
        </select>
        @error('mapel_id') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a class="btn btn-secondary" data-bs-dismiss="modal">Batal</a>
</form>
