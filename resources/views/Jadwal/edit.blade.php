<!-- filepath: resources/views/Jadwal/edit.blade.php -->
<form wire:submit.prevent="update">
    <div class="form-group">
        <label for="guru_mapel_id">Guru Mapel</label>
        <select class="form-control" id="guru_mapel_id" wire:model="guru_mapel_id">
            <option value="">Pilih Guru Mapel</option>
            @foreach ($guruMapel as $item)
                <option value="{{ $item->id }}">{{ $item->karyawan->user->nama }} - {{ $item->mapel->nama_mapel }}</option>
            @endforeach
        </select>
        @error('guru_mapel_id') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label for="hari">Hari</label>
        <input type="text" class="form-control" id="hari" wire:model="hari" placeholder="Masukkan hari">
        @error('hari') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label for="jam_mulai">Jam Mulai</label>
        <input type="time" class="form-control" id="jam_mulai" wire:model="jam_mulai">
        @error('jam_mulai') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label for="jam_selesai">Jam Selesai</label>
        <input type="time" class="form-control" id="jam_selesai" wire:model="jam_selesai">
        @error('jam_selesai') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label for="kelas_id">Kelas</label>
        <select class="form-control" id="kelas_id" wire:model="kelas_id">
            <option value="">Pilih Kelas</option>
            @foreach ($kelas as $item)
                <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
            @endforeach
        </select>
        @error('kelas_id') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>