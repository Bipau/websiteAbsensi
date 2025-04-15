<form wire:submit="store">
    <div class="form-group">
        <label>Kode Mapel</label>
        <input type="text" class="form-control" wire:model="kode_mapel">
        @error('kode_mapel') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>Nama Mapel</label>
        <input type="text" class="form-control" wire:model="nama_mapel">
        @error('nama_mapel') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
   
    <button type="submit" class="btn btn-primary">Save</button>
    <button type="button" class="btn btn-secondary" wire:click="$set('addPage', false)">Close</button>
</form>
