<div>
    <div class="page-header">
        <div class="page-title">
            <h4>Kelola Absen Gerbang</h4>
            <h6>Daftar Absensi Gerbang Siswa</h6>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Search and Filter Section -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Filter Periode</label>
                        <select wire:model.live="filter" class="form-select">
                            <option value="harian">Hari Ini</option>
                            <option value="mingguan">Minggu Ini</option>
                            <option value="bulanan">Bulan Ini</option>
                            <option value="semester1">Semester 1</option>
                            <option value="semester2">Semester 2</option>
                            <option value="manual">Pilih Tanggal</option>
                        </select>
                    </div>
                </div>

                @if ($filter === 'manual')
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Dari Tanggal</label>
                            <input type="date" wire:model.live="tanggalAwal" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Sampai Tanggal</label>
                            <input type="date" wire:model.live="tanggalAkhir" class="form-control">
                        </div>
                    </div>
                @endif

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Cari</label>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                            placeholder="Cari nama/NIS...">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tampilkan</label>
                        <select wire:model.live="perPage" class="form-select">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Filter Role</label>
                        <select wire:model.live="roleFilter" class="form-select">
                            <option value="all">Semua</option>
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                        </select>
                    </div>
                </div>
            </div>

             <!-- Export Buttons -->
             <div class="mb-3">
                <button wire:click="exportExcelManual" class="btn btn-success">
                    <i class="fas fa-file-excel me-2"></i>Export Excel
                </button>
                <button onclick="window.print()" class="btn btn-primary ms-2">
                    <i class="fas fa-print me-2"></i>Print
                </button>
            </div>
            
            <!-- Table Section -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Role</th>
                            <th>NIS/NIP</th>
                            <th>Nama</th>
                            <th>Jam Masuk</th>
                            <th>Status</th>
                            <th>Jam Keluar</th>
                            {{-- <th>Foto</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataAbsen as $index => $absen)
                            <tr>
                                <td>{{ $dataAbsen->firstItem() + $index }}</td>
                                <td>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $absen->user->role === 'siswa' ? 'info' : 'primary' }}">
                                        {{ ucfirst($absen->user->role) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($absen->user->role === 'siswa')
                                        {{ $absen->user->siswa->nis ?? '-' }}
                                    @else
                                        {{ $absen->user->karyawan->nip ?? '-' }}
                                    @endif
                                </td>
                                <td>{{ $absen->user->nama }}</td>
                                <td>{{ \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $absen->status === 'Tepat Waktu' ? 'success' : 'warning' }}">
                                        {{ $absen->status }}
                                    </span>
                                </td>
                                <td>{{ $absen->jam_keluar ? \Carbon\Carbon::parse($absen->jam_keluar)->format('H:i') : '-' }}
                                </td>
                                {{-- <td>
                                    @if($absen->foto)
                                        <img src="{{ Storage::url($absen->foto) }}" 
                                            alt="Foto Absen" 
                                            class="rounded view-photo"
                                            style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                            data-foto="{{ Storage::url($absen->foto) }}"
                                            data-nama="{{ $absen->user->nama }}"
                                            data-tanggal="{{ Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}"
                                            data-jam="{{ Carbon\Carbon::parse($absen->jam_masuk)->format('H:i') }}"
                                            data-status="{{ $absen->status }}"
                                        >
                                    @else
                                        <span class="text-muted">Tidak ada foto</span>
                                    @endif
                                </td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Menampilkan {{ $dataAbsen->firstItem() ?? 0 }} sampai {{ $dataAbsen->lastItem() ?? 0 }}
                        dari {{ $dataAbsen->total() }} data
                    </div>
                    <div>
                        {{ $dataAbsen->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Modal -->
    <div wire:ignore.self class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">Foto Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Foto Absensi" class="img-fluid rounded">
                    <div class="mt-3">
                        <p class="mb-1"><strong>Nama:</strong> <span id="modalNama"></span></p>
                        <p class="mb-1"><strong>Tanggal:</strong> <span id="modalTanggal"></span></p>
                        <p class="mb-1"><strong>Jam:</strong> <span id="modalJam"></span></p>
                        <p class="mb-0"><strong>Status:</strong> <span id="modalStatus"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showImage(url) {
            Swal.fire({
                imageUrl: url,
                imageWidth: 600,
                imageHeight: 400,
                imageAlt: 'Foto Absensi',
                showConfirmButton: false,
                showCloseButton: true
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Get the modal
            const photoModal = new bootstrap.Modal(document.getElementById('photoModal'));
            
            // Add click event to all photos
            document.querySelectorAll('.view-photo').forEach(photo => {
                photo.addEventListener('click', function() {
                    // Get data from data attributes
                    const foto = this.dataset.foto;
                    const nama = this.dataset.nama;
                    const tanggal = this.dataset.tanggal;
                    const jam = this.dataset.jam;
                    const status = this.dataset.status;
                    
                    // Update modal content
                    document.getElementById('modalImage').src = foto;
                    document.getElementById('modalNama').textContent = nama;
                    document.getElementById('modalTanggal').textContent = tanggal;
                    document.getElementById('modalJam').textContent = jam;
                    document.getElementById('modalStatus').textContent = status;
                    
                    // Show modal
                    photoModal.show();
                });
            });
        });

        // Add styles for photo hover effect
        const style = document.createElement('style');
        style.textContent = `
            .view-photo {
                transition: transform 0.2s ease;
            }
            .view-photo:hover {
                transform: scale(1.1);
            }
            #modalImage {
                max-height: 70vh;
                object-fit: contain;
            }
            .modal-body {
                padding: 1rem;
            }
            .modal-body img {
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .modal-body p {
                font-size: 1rem;
            }
            .modal-body span {
                color: #666;
            }
        `;
        document.head.appendChild(style);
    </script>
    @endpush
</div>
