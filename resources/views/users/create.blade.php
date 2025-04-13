<form wire:submit="store">
    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" wire:model="nama">
        @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label>No. Telp</label>
        <input type="text" class="form-control" wire:model="nomor">
        @error('nomor') <span class="text-danger">{{ $message }}</span> @enderror
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
        <label>Role</label>
        <select class="form-control" wire:model="role">
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="kurikulum">Kurikulum</option>
            <option value="walikelas">Walikelas</option>
         
        </select>
        @error('role') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <button type="button" class="btn btn-secondary" wire:click="$set('addPage', false)">Close</button>
</form>
