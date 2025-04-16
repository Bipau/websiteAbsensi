<div class="container py-5">
    <div class="row">
        <!-- Profile Card - Enhanced with better styling -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">Profil Pengguna</h4>
                </div>
                <div class="card-body text-center">
                    {{-- <div class="position-relative mb-4">
                        <img src="{{ asset('assets/img/avatar.png') }}" 
                            class="rounded-circle border border-3 border-primary shadow" 
                            style="width: 160px; height: 160px; object-fit: cover;"
                            alt="Foto Profil">
                        <div class="position-absolute bottom-0 end-0">
                            <button class="btn btn-sm btn-outline-primary rounded-circle" title="Ubah Foto">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                    </div> --}}
                    <h4 class="card-title fw-bold mb-1">{{ $user->nama }}</h4>
                    
                    @if($user->role === 'siswa' && $user->siswa)
                        <p class="text-muted mb-3">{{ $user->siswa->nis }}</p>
                        <div class="d-flex justify-content-center mb-3">
                            <span class="badge bg-primary px-3 py-2">Siswa</span>
                        </div>
                        <div class="text-start mt-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3 text-primary">
                                    <i class="fas fa-graduation-cap fa-fw fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 small">Kelas</p>
                                    <p class="mb-0 fw-medium">{{ $user->siswa->kelas->nama_kelas }}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3 text-primary">
                                    <i class="fas fa-envelope fa-fw fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 small">Email</p>
                                    <p class="mb-0 fw-medium">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="me-3 text-primary">
                                    <i class="fas fa-phone fa-fw fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 small">No. Telepon</p>
                                    <p class="mb-0 fw-medium">{{ $user->nomor }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-3">{{ $user->karyawan->nip ?? 'Staff' }}</p>
                        <div class="d-flex justify-content-center mb-3">
                            <span class="badge bg-success px-3 py-2">{{ ucfirst($user->role) }}</span>
                        </div>
                        <div class="text-start mt-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3 text-success">
                                    <i class="fas fa-user-tie fa-fw fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 small">Jabatan</p>
                                    <p class="mb-0 fw-medium">{{ ucfirst($user->role) }}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3 text-success">
                                    <i class="fas fa-envelope fa-fw fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 small">Email</p>
                                    <p class="mb-0 fw-medium">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="me-3 text-success">
                                    <i class="fas fa-phone fa-fw fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 small">No. Telepon</p>
                                    <p class="mb-0 fw-medium">{{ $user->nomor }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                {{-- <div class="card-footer bg-light p-3">
                    <button class="btn btn-sm btn-outline-primary w-100">
                        <i class="fas fa-edit me-2"></i>Edit Profil
                    </button>
                </div> --}}
            </div>
        </div>

        <!-- Statistics & Activity Section -->
        <div class="col-lg-8">
            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="card border-0 shadow h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-primary fw-bold text-uppercase mb-0">Kehadiran Bulan Ini</h6>
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                            </div>
                            <h3 class="fw-bold mb-0">{{ $statistics['totalHadir'] }} <small class="text-muted fs-6">Hari</small></h3>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Tingkat Kehadiran</small>
                                    <small class="text-success fw-bold">{{ $statistics['persentaseKehadiran'] }}%</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" 
                                        role="progressbar" 
                                        style="width: {{ $statistics['persentaseKehadiran'] }}%"
                                        aria-valuenow="{{ $statistics['persentaseKehadiran'] }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Absensi Kelas Stats (Only for students) -->
                @if($user->role === 'siswa' && $user->siswa)
                    <div class="col-md-6">
                        <div class="card border-0 shadow h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-success fw-bold text-uppercase mb-0">Absensi Kelas</h6>
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-2">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                </div>
                                <h3 class="fw-bold mb-0">{{ $statistics['hadirKelas'] }}<small class="text-muted fs-6">/{{ $statistics['totalAbsenKelas'] }}</small></h3>
                                <div class="mt-3">
                                    <div class="d-flex gap-2">
                                        <div class="px-3 py-2 bg-success bg-opacity-10 rounded">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-success me-2">H</span>
                                                <span>{{ $statistics['hadirKelas'] }}</span>
                                            </div>
                                        </div>
                                        <div class="px-3 py-2 bg-warning bg-opacity-10 rounded">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-warning me-2">I</span>
                                                <span>{{ $statistics['izinKelas'] }}</span>
                                            </div>
                                        </div>
                                        <div class="px-3 py-2 bg-danger bg-opacity-10 rounded">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-danger me-2">A</span>
                                                <span>{{ $statistics['alphaKelas'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Stats for non-students -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-success fw-bold text-uppercase mb-0">Status Hari Ini</h6>
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-2">
                                        <i class="fas fa-clipboard-check"></i>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="bg-success bg-opacity-25 p-3 rounded-circle">
                                            <i class="fas fa-check text-success fa-2x"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="fw-bold mb-0">Hadir</h3>
                                        <p class="text-muted mb-0">Masuk pukul 07:30</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Attendance Chart -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">Grafik Kehadiran</h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownPeriod" data-bs-toggle="dropdown" aria-expanded="false">
                                Bulan Ini
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownPeriod">
                                <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
                                <li><a class="dropdown-item" href="#">3 Bulan Terakhir</a></li>
                                <li><a class="dropdown-item" href="#">Semester Ini</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="attendanceChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Riwayat Kehadiran Terkini -->
            <div class="card border-0 shadow">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">Riwayat Kehadiran Terkini</h6>
                        <a href="#" class="btn btn-sm btn-link">Lihat Semua</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Jam Masuk</th>
                                    <th scope="col">Jam Keluar</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ now()->format('d M Y') }}</td>
                                    <td>07:30</td>
                                    <td>15:00</td>
                                    <td><span class="badge bg-success">Tepat Waktu</span></td>
                                </tr>
                                <tr>
                                    <td>{{ now()->subDay()->format('d M Y') }}</td>
                                    <td>07:45</td>
                                    <td>15:00</td>
                                    <td><span class="badge bg-warning">Terlambat</span></td>
                                </tr>
                                <tr>
                                    <td>{{ now()->subDays(2)->format('d M Y') }}</td>
                                    <td>07:25</td>
                                    <td>15:00</td>
                                    <td><span class="badge bg-success">Tepat Waktu</span></td>
                                </tr>
                                <tr>
                                    <td>{{ now()->subDays(3)->format('d M Y') }}</td>
                                    <td>07:30</td>
                                    <td>15:00</td>
                                    <td><span class="badge bg-success">Tepat Waktu</span></td>
                                </tr>
                                <tr>
                                    <td>{{ now()->subDays(4)->format('d M Y') }}</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><span class="badge bg-secondary">Hari Libur</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:load', function() {
    // Improved chart with monthly data
    var options = {
        series: [{
            name: 'Tepat Waktu',
            data: [{{ $statistics['tepatWaktu'] }}, 18, 20, 22, 19, 21, 17]
        }, {
            name: 'Terlambat',
            data: [{{ $statistics['terlambat'] }}, 2, 1, 0, 3, 1, 2]
        }, {
            name: 'Tidak Hadir',
            data: [{{ $statistics['alphaKelas'] }}, 1, 0, 0, 0, 0, 2]
        }],
        chart: {
            type: 'bar',
            height: 300,
            stacked: true,
            toolbar: {
                show: false
            },
            fontFamily: 'Nunito, sans-serif',
        },
        plotOptions: {
            bar: {
                horizontal: false,
                borderRadius: 5,
                columnWidth: '60%',
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 2,
            colors: ['#fff']
        },
        xaxis: {
            categories: ['Apr', 'Mar', 'Feb', 'Jan', 'Des', 'Nov', 'Okt'],
        },
        yaxis: {
            title: {
                text: 'Jumlah Hari'
            },
        },
        fill: {
            opacity: 1
        },
        colors: ['#28a745', '#ffc107', '#dc3545'],
        legend: {
            position: 'top',
            horizontalAlign: 'right',
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " hari"
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#attendanceChart"), options);
    chart.render();
});
</script>
@endpush