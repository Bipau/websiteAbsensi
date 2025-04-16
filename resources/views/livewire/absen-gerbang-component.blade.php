<!-- resources/views/livewire/absen-gerbang-component.blade.php -->
<div>
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <h4>Absensi Gerbang</h4>
            <h6>Sistem Absensi Masuk dan Keluar</h6>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column - Absen Form -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Form Absensi</h5>
                    
                    <form wire:submit.prevent="absenMasuk" class="needs-validation" novalidate>
                        <!-- Camera Section -->
                        <div class="mb-4">
                            <label class="form-label">Foto Absensi</label>
                            <div class="d-flex flex-column align-items-center">
                                <div class="position-relative mb-3">
                                    @if($foto)
                                        @if(is_string($foto))
                                            <img src="{{ Storage::url($foto) }}" class="img-fluid rounded" 
                                                style="max-width: 320px; max-height: 240px;">
                                        @else
                                            <img src="{{ $foto->temporaryUrl() }}" class="img-fluid rounded" 
                                                style="max-width: 320px; max-height: 240px;">
                                        @endif
                                    @else
                                        <div id="camera-preview" class="bg-light rounded d-flex align-items-center justify-content-center" 
                                            style="width: 320px; height: 240px;">
                                            <video id="video" width="320" height="240" autoplay playsinline style="display: none;"></video>
                                            <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
                                            <i class="fas fa-camera fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary" id="startCamera">
                                        <i class="fas fa-video me-2"></i>Buka Kamera
                                    </button>
                                    <button type="button" class="btn btn-success" id="takePhoto" style="display: none;">
                                        <i class="fas fa-camera me-2"></i>Ambil Foto
                                    </button>
                                    @if($foto)
                                        <button type="button" class="btn btn-danger" wire:click="resetFoto">
                                            <i class="fas fa-times me-2"></i>Hapus
                                        </button>
                                    @endif
                                </div>
                                
                                @error('foto') 
                                    <small class="text-danger mt-2">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="mb-4">
                            <label class="form-label">Lokasi</label>
                            <div class="d-flex flex-column gap-2">
                                <button type="button" class="btn btn-info" wire:click="getLocation">
                                    <i class="fas fa-map-marker-alt me-2"></i>Ambil Lokasi
                                </button>
                                
                                @if($latitude && $longitude)
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-check-circle me-2"></i>Lokasi berhasil diambil
                                        <div class="small text-muted mt-1">
                                            Latitude: {{ $latitude }}, Longitude: {{ $longitude }}
                                        </div>
                                    </div>
                                @endif
                                
                                <input type="hidden" wire:model="latitude">
                                <input type="hidden" wire:model="longitude">
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fas fa-sign-in-alt me-2"></i>Absen Masuk
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Memproses...
                                </span>
                            </button>
                            
                            <button type="button" wire:click="absenKeluar" class="btn btn-danger" 
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="absenKeluar">
                                    <i class="fas fa-sign-out-alt me-2"></i>Absen Keluar
                                </span>
                                <span wire:loading wire:target="absenKeluar">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Status -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Status Absensi</h5>
                    
                    <div class="text-center">
                        <div class="mb-4">
                            <h3 id="current-time" class="display-6 fw-bold text-primary"></h3>
                            <p class="text-muted">{{ now()->format('l, d F Y') }}</p>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            <div class="p-3 border rounded bg-light">
                                <h6 class="mb-2">Jam Masuk</h6>
                                <p class="mb-0 fs-5">{{ $jamMasuk ?? '-' }}</p>
                            </div>

                            <div class="p-3 border rounded bg-light">
                                <h6 class="mb-2">Status</h6>
                                <span class="badge bg-{{ $status === 'Tepat Waktu' ? 'success' : ($status === 'Terlambat' ? 'warning' : 'secondary') }} fs-6">
                                    {{ $status ?? 'Belum Absen' }}
                                </span>
                            </div>

                            <div class="p-3 border rounded bg-light">
                                <h6 class="mb-2">Jam Keluar</h6>
                                <p class="mb-0 fs-5">{{ $jamKeluar ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let video = null;
    let canvas = null;
    let stream = null;
    
    document.getElementById('startCamera').addEventListener('click', async function() {
        try {
            video = document.getElementById('video');
            canvas = document.getElementById('canvas');
            const preview = document.getElementById('camera-preview');
            const startButton = document.getElementById('startCamera');
            const takePhotoButton = document.getElementById('takePhoto');
            const iconPlaceholder = preview.querySelector('i');
    
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: 'user',
                    width: { ideal: 320 },
                    height: { ideal: 240 }
                } 
            });
    
            video.srcObject = stream;
            video.style.display = 'block';
            iconPlaceholder.style.display = 'none';
            startButton.style.display = 'none';
            takePhotoButton.style.display = 'block';
    
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal mengakses kamera',
                text: 'Mohon izinkan akses kamera untuk melakukan absensi'
            });
            console.error('Error:', err);
        }
    });
    
    document.getElementById('takePhoto').addEventListener('click', function() {
        if (video && canvas) {
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            canvas.toBlob(function(blob) {
                const file = new File([blob], "photo.jpg", { type: "image/jpeg" });
                
                // Create a DataTransfer object and add the file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                
                // Dispatch to Livewire
                Livewire.dispatch('foto-captured', { foto: canvas.toDataURL('image/jpeg') });
                
                // Stop camera
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    video.style.display = 'none';
                    document.getElementById('startCamera').style.display = 'block';
                    document.getElementById('takePhoto').style.display = 'none';
                }
            }, 'image/jpeg');
        }
    });
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    });
    </script>