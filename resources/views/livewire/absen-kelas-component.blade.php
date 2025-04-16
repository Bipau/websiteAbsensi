<div>
    <div class="card">
        <div class="card-body">
            <!-- Filter Section -->
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
                        <label>Kelas</label>
                        <select wire:model.live="kelasId" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Jadwal</th>
                            <th>Waktu Absen</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($absen as $i => $a)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $a->siswa->nama ?? '-' }}</td>
                                <td>{{ $a->kelas->nama_kelas ?? '-' }}</td>
                                <td>{{ $a->jadwal->mapel->nama_mapel ?? '-' }}</td>
                                <td>{{ $a->jam_absen }}</td>
                                <td>{{ $a->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data absen.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
