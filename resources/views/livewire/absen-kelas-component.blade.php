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
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            @php
                                $dates = collect($absen)->flatMap(function($student) {
                                    return array_keys($student['attendance']->toArray());
                                })->unique()->sort()->values()->toArray();
                            @endphp
                            @foreach($dates as $date)
                                <th>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absen as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student['nis'] }}</td>
                                <td>{{ $student['nama'] }}</td>
                                <td>{{ $student['kelas'] }}</td>
                                @foreach($dates as $date)
                                    <td class="text-center">
                                        @if(isset($student['attendance'][$date]))
                                            @foreach($student['attendance'][$date]['status'] as $attendance)
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="badge bg-{{ $attendance['status'] === 'Hadir' ? 'success' : ($attendance['status'] === 'Izin' ? 'warning' : 'danger') }}">
                                                        {{ $attendance['status'] }}
                                                    </span>
                                                    <small class="d-block">
                                                        {{ $attendance['jam_absen'] }}
                                                    </small>
                                                    <small class="d-block text-muted">
                                                        {{ $attendance['mapel'] }}
                                                    </small>
                                                </div>
                                            @endforeach
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
                    padding: 0.3em 0.6em;
                }
                .badge small {
                    font-size: 0.75em;
                }
                .text-muted {
                    font-size: 0.8rem;
                }
            </style>
        </div>
    </div>
</div>
