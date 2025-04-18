<!-- filepath: /home/bipau/dev/laravel-project/WebKelolAbsensi/resources/views/livewire/generate-qr-code-component.blade.php -->
<div>
    <div class="page-header">
        <div class="page-title">
            <h4>Generate QR Code Absensi</h4>
            <h6>Generate QR Code untuk Absensi Kelas</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Form Generate QR</h5>

                    <div class="mb-4">
                        <label class="form-label">Pilih Kelas</label>
                        <select wire:model="kelas_id" class="form-select">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                        @error('kelas_id') 
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Pilih Jadwal Pelajaran</label>
                        <select wire:model="jadwal_id" class="form-select">
                            <option value="">-- Pilih Jadwal --</option>
                            @foreach($jadwalList as $jadwal)
                                <option value="{{ $jadwal->id }}">
                                    {{ $jadwal->mapel->nama_mapel ?? 'Mapel' }} - 
                                    {{ $jadwal->hari }} 
                                    ({{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }})
                                </option>
                            @endforeach
                        </select>
                        @error('jadwal_id') 
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button wire:click="generate" class="btn btn-primary" wire:loading.attr="disabled">
                        <i class="fas fa-qrcode me-2"></i>
                        <span wire:loading.remove>Generate QR Code</span>
                        <span wire:loading>Generating...</span>
                    </button>
                </div>
            </div>
        </div>

        @if ($token)
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title mb-4">QR Code</h5>

                    <div class="qr-container p-4 bg-light rounded mb-4">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ $token }}&size=200x200" 
                            alt="QR Code" 
                            class="img-fluid mb-3"
                            style="max-width: 200px;">
                        
                        <div class="mt-3">
                            <p class="mb-2"><strong>Token:</strong> 
                                <span class="badge bg-primary">{{ $token }}</span>
                            </p>
                            <p class="mb-0"><strong>Kadaluarsa:</strong> 
                                <span class="badge bg-warning text-dark">
                                    {{ \Carbon\Carbon::parse($expiredAt)->format('d M Y H:i') }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-2">
                        <button onclick="window.print()" class="btn btn-info">
                            <i class="fas fa-print me-2"></i>Print QR
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>

// Add print styles
const style = document.createElement('style');
style.textContent = `
    @media print {
        body * {
            visibility: hidden;
        }
        .qr-container, .qr-container * {
            visibility: visible;
        }
        .qr-container {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush