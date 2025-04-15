<div>
    <div class="page-header">
        <div class="page-title">
            <h4>Data Jadwal Pelajaran</h4>
        </div>
        <div class="page-btn">
            <a wire:click="create" data-bs-toggle="modal" data-bs-target="#createModal" class="btn btn-added">
                <img src="assets/img/icons/plus.svg" class="me-1" alt="img">Tambah Data
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <input type="text" class="form-control" wire:model.live.debounce.300ms="search" placeholder="Type here...">
                    </div>
                    <div class="search-input">
                        <a class="btn btn-searchset"><img src="assets/img/icons/search-white.svg" alt="img"></a>
                    </div>
                </div>
                <div class="wordset">
                    <ul>
                        <li>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img src="assets/img/icons/printer.svg" alt="img"></a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" id="tableJadwal">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Karyawan</th>
                            <th>Mata Pelajaran</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Kelas</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwal as $key => $item)
                            <tr>
                                <td>{{ $jadwal->firstItem() + $key }}</td>
                                <td>{{ $item->karyawan->user->nama ?? '-' }}</td>
                                <td>{{ $item->mapel->nama_mapel ?? '-' }}</td>
                                <td>{{ $item->hari }}</td>
                                <td>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                                <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                                <td>
                                    <a class="me-3" wire:click="edit({{ $item->id }})" data-bs-target="#editModal" data-bs-toggle="modal">
                                        <img src="assets/img/icons/edit.svg" alt="img">
                                    </a>
                                    <a class="me-3" wire:click="deleteConfirmation({{ $item->id }})">
                                        <img src="assets/img/icons/delete.svg" alt="img">
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $jadwal->links() }}
            </div>
        </div>
    </div>

    <!-- Modal tambah data -->
    <div wire:ignore.self class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Jadwal Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="store">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Karyawan</label>
                                    <select wire:model="karyawan_id" class="form-control">
                                        <option value="">Pilih Karyawan</option>
                                        @foreach ($karyawan as $k)
                                            <option value="{{ $k->id }}">{{ $k->user->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('karyawan_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mata Pelajaran</label>
                                    <select wire:model="mapel_id" class="form-control">
                                        <option value="">Pilih Mapel</option>
                                        @foreach ($mapel as $m)
                                            <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                                        @endforeach
                                    </select>
                                    @error('mapel_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kelas</label>
                                    <select wire:model="kelas_id" class="form-control">
                                        <option value="">Pilih Kelas</option>
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hari</label>
                                    <select wire:model="hari" class="form-control">
                                        <option value="">Pilih Hari</option>
                                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat',] as $h)
                                            <option value="{{ $h }}">{{ $h }}</option>
                                        @endforeach
                                    </select>
                                    @error('hari') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jam Mulai</label>
                                    <input type="time" wire:model="jam_mulai" class="form-control">
                                    @error('jam_mulai') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jam Selesai</label>
                                    <input type="time" wire:model="jam_selesai" class="form-control">
                                    @error('jam_selesai') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetInput">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal edit data -->
    <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Jadwal Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="update">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Karyawan</label>
                                    <select wire:model="karyawan_id" class="form-control">
                                        <option value="">Pilih Karyawan</option>
                                        @foreach ($karyawan as $k)
                                            <option value="{{ $k->id }}">{{ $k->user->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('karyawan_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mata Pelajaran</label>
                                    <select wire:model="mapel_id" class="form-control">
                                        <option value="">Pilih Mapel</option>
                                        @foreach ($mapel as $m)
                                            <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                                        @endforeach
                                    </select>
                                    @error('mapel_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kelas</label>
                                    <select wire:model="kelas_id" class="form-control">
                                        <option value="">Pilih Kelas</option>
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hari</label>
                                    <select wire:model="hari" class="form-control">
                                        <option value="">Pilih Hari</option>
                                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
                                            <option value="{{ $h }}">{{ $h }}</option>
                                        @endforeach
                                    </select>
                                    @error('hari') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jam Mulai</label>
                                    <input type="time" wire:model="jam_mulai" class="form-control">
                                    @error('jam_mulai') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jam Selesai</label>
                                    <input type="time" wire:model="jam_selesai" class="form-control">
                                    @error('jam_selesai') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetInput">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('show-delete-confirmation', () => {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data jadwal akan dihapus dan tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.dispatch('delete-confirmed');
                }
            });
        });

        @this.on('close-modal', () => {
            $('#createModal').modal('hide');
            $('#editModal').modal('hide');
            location.reload();
        });

        @this.on('success', (event) => {
            Swal.fire({
                icon: 'success',
                title: event.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            Livewire.emit('refreshTable');
        });

        @this.on('error', (event) => {
            Swal.fire({
                icon: 'error',
                title: event.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            Livewire.emit('refreshTable');
        });

        @this.on('warning', (event) => {
            Swal.fire({
                icon: 'warning',
                title: event.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            location.reload();
        });

        @this.on('jadwal-deleted', () => {
            Swal.fire({
                icon: 'success',
                title: 'Jadwal berhasil dihapus!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            Livewire.emit('refreshTable');
        });
    });

    $('#createModal, #editModal').on('hidden.bs.modal', function() {
        Livewire.dispatch('resetInput');
    });

    Livewire.on('refresh-page', () => {
        location.reload();
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('refreshTable', () => {
            Livewire.dispatch('$refresh');
        });
    });

    window.Livewire.on('refreshTable', () => {
        setTimeout(() => {
            $('#tableJadwal').DataTable().destroy();
            $('#tableJadwal').DataTable();
        }, 300);
    });
</script>