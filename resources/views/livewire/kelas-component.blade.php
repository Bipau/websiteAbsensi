<div>
    <div class="page-header">
        <div class="page-title">
            <h4>Data Mapel</h4>

        </div>
        <div class="page-btn">
            <a href="addcategory.html" wire:click="create" data-bs-toggle="modal" data-bs-target="#createModal"
                class="btn btn-added">
                <img src="assets/img/icons/plus.svg" class="me-1" alt="img">Tambah Data
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
                            placeholder="Type here...">
                    </div>
                </div>
                <div class="wordset">
                    <ul>
                        <li>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                    src="assets/img/icons/printer.svg" alt="img"></a>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="table-responsive">
                <table class="table" id="tableKelas">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kelas</th>
                            <th>Tingkat</th>
                            <th>Jurusan</th>
                            <th>Wali Kelas</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelas ?? [] as $index => $item)
                            <tr>
                                <td>{{ $index + 1 + ($kelas->currentPage() - 1) * $kelas->perPage() }}</td>
                                <td>{{ $item->nama_kelas }}</td>
                                <td>{{ $item->tingkat }}</td>
                                <td>{{ $item->jurusan }}</td>
                                <td>
                                   
                                        {{ $item->waliKelas->nama ?? 'Belum ditetapkan' }}
                               
                                        
                                
                                </td>
                                <td>
                                    <a class="me-3" wire:click='edit({{ $item->id }})' data-bs-target="#editModal"
                                        data-bs-toggle="modal">
                                        <img src="assets/img/icons/edit.svg" alt="img">
                                    </a>
                                    <a class="me-3" wire:click="deleteConfirmation({{ $item->id }})">
                                        <img src="assets/img/icons/delete.svg" alt="img">
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    <!-- Modal tambah data -->
    <div wire:ignore.self class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    @include('Kelas.create')

                </div>

            </div>
        </div>
    </div>




    <!-- Modal edit data -->
    <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    @include('Kelas.edit')

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
                text: 'Data akan dihapus dan tidak dapat dikembalikan!',
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
            Livewire.dispatch(
                'refreshTable'); // Gunakan ini untuk memperbarui tabel tanpa refresh halaman
        });
        // Menampilkan pesan sukses
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

        // Menampilkan pesan error
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

        // Menampilkan pesan warning
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

        // Menampilkan notifikasi setelah delete sukses
        @this.on('kelas-deleted', () => {
            Swal.fire({
                icon: 'success',
                title: 'Data berhasil dihapus!',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            Livewire.emit('refreshTable');
        });
    });
</script>
