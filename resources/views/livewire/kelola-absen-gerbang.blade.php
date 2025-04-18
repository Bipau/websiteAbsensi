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

            {{-- pilih export --}}
            <div class="mb-3">
                <button wire:click="exportExcelManual" class="btn btn-success">
                    <i class="fas fa-file-excel me-2"></i>Export Excel
                </button>
                <button wire:click="exportPDF" class="btn btn-danger ms-2">
                    <i class="fas fa-file-pdf me-2"></i>Export PDF
                </button>
                <button onclick="window.print()" class="btn btn-primary ms-2">
                    <i class="fas fa-print me-2"></i>Print
                </button>
            </div>
            <!-- Table Section -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Role</th>
                            <th>NIS/NIP</th>
                            <th>Nama</th>
                            @php
                                $dates = collect($dataAbsen)->flatMap(function($user) {
                                    return array_keys($user['attendance']->toArray());
                                })->unique()->sort()->values()->toArray();
                            @endphp
                            @foreach($dates as $date)
                                <th class="text-center">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataAbsen as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-{{ $user['role'] === 'siswa' ? 'info' : 'primary' }}">
                                        {{ ucfirst($user['role']) }}
                                    </span>
                                </td>
                                <td>{{ $user['identifier'] }}</td>
                                <td>{{ $user['nama'] }}</td>
                                @foreach($dates as $date)
                                    <td class="text-center">
                                        @if(isset($user['attendance'][$date]))
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="badge bg-{{ $user['attendance'][$date]['status'] === 'Tepat Waktu' ? 'success' : 'warning' }}">
                                                    {{ $user['attendance'][$date]['status'] }}
                                                </span>
                                                <small class="d-block">
                                                    {{ Carbon\Carbon::parse($user['attendance'][$date]['jam_masuk'])->format('H:i') }}
                                                </small>
                                                @if($user['attendance'][$date]['jam_keluar'])
                                                    <small class="d-block text-muted">
                                                        {{ Carbon\Carbon::parse($user['attendance'][$date]['jam_keluar'])->format('H:i') }}
                                                    </small>
                                                @endif
                                             
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 4 + count($dates) }}" class="text-center">Tidak ada data absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <style>
                .table td {
                    vertical-align: middle;
                    font-size: 0.9rem;
                }
                .badge {
                    font-size: 0.8rem;
                }
                small {
                    font-size: 0.75rem;
                }
                .img-thumbnail {
                    transition: transform 0.2s;
                }
                .img-thumbnail:hover {
                    transform: scale(1.1);
                }
            </style>
        </div>
    </div>

    <!-- Photo Modal -->
    <div wire:ignore.self class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel"
        aria-hidden="true">
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
