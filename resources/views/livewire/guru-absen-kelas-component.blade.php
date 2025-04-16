<div>
        <div class="page-header">
            <div class="page-title">
                <h4>Form Absensi Siswa</h4>
                <h6>Kelola Absensi Siswa</h6>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session()->has('message'))
            <div class="alert alert-success" role="alert">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Kelas <span class="text-danger">*</span></label>
                            <select wire:model.live="kelas_id" class="form-select">
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                            @error('kelas_id') 
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jadwal Pelajaran <span class="text-danger">*</span></label>
                            <select wire:model.live="jadwal_id" class="form-select" {{ empty($kelas_id) ? 'disabled' : '' }}>
                                <option value="">Pilih Jadwal</option>
                                @foreach ($jadwalList as $jadwal)
                                    <option value="{{ $jadwal->id }}">
                                        {{ $jadwal->nama_mapel}} - {{ $jadwal->hari }} 
                                        ({{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }})
                                    </option>
                                @endforeach
                            </select>
                            @error('jadwal_id') 
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tanggal <span class="text-danger">*</span></label>
                            <input type="date" wire:model.live="tanggal" class="form-control">
                            @error('tanggal') 
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div wire:loading wire:target="kelas_id" class="text-center my-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted">Memuat data siswa...</p>
                </div>

                <!-- Students List Table -->
                @if(count($siswaList) > 0)
                    <div class="table-top mb-3">
                        <div class="text-end">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="selectAll"
                                    wire:click="$set('hadir', @js(array_fill_keys($siswaList->pluck('id')->toArray(), true)))">
                                <label class="form-check-label" for="selectAll">Pilih Semua</label>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th class="text-center">Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswaList as $index => $siswa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $siswa->nis }}</td>
                                        <td>{{ $siswa->user->nama }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <input type="radio" 
                                                    class="btn-check" 
                                                    name="status_{{ $siswa->id }}" 
                                                    id="hadir_{{ $siswa->id }}" 
                                                    wire:model.live="status.{{ $siswa->id }}" 
                                                    value="Hadir">
                                                <label class="btn btn-outline-success btn-sm" for="hadir_{{ $siswa->id }}">
                                                    <i class="fas fa-check me-1"></i>Hadir
                                                </label>

                                                <input type="radio" 
                                                    class="btn-check" 
                                                    name="status_{{ $siswa->id }}" 
                                                    id="izin_{{ $siswa->id }}" 
                                                    wire:model.live="status.{{ $siswa->id }}" 
                                                    value="Izin">
                                                <label class="btn btn-outline-warning btn-sm" for="izin_{{ $siswa->id }}">
                                                    <i class="fas fa-envelope me-1"></i>Izin
                                                </label>

                                                <input type="radio" 
                                                    class="btn-check" 
                                                    name="status_{{ $siswa->id }}" 
                                                    id="alpha_{{ $siswa->id }}" 
                                                    wire:model.live="status.{{ $siswa->id }}" 
                                                    value="Alpha">
                                                <label class="btn btn-outline-danger btn-sm" for="alpha_{{ $siswa->id }}">
                                                    <i class="fas fa-times me-1"></i>Alpha
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-end mt-3">
                        <button wire:click="simpan" class="btn btn-primary" 
                            {{ empty($kelas_id) || empty($jadwal_id) || empty($tanggal) ? 'disabled' : '' }}>
                            <span wire:loading.remove wire:target="simpan">
                                <i class="fas fa-save me-1"></i> Simpan Absen
                            </span>
                            <span wire:loading wire:target="simpan">
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                @elseif($kelas_id)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Tidak ada siswa yang terdaftar dalam kelas ini.
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Silahkan pilih kelas terlebih dahulu untuk menampilkan daftar siswa.
                    </div>
                @endif
            </div>
        </div>
    </div>
