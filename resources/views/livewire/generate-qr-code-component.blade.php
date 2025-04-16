<div>
    <h4>Generate QR Absen</h4>

    <div class="mb-3">
        <label>Kelas:</label>
        <select wire:model="kelas_id" class="form-control">
            <option value="">-- Pilih Kelas --</option>
            @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Jadwal Pelajaran:</label>
        <select wire:model="jadwal_id" class="form-control">
            <option value="">-- Pilih Jadwal --</option>
            @foreach($jadwalList as $jadwal)
                <option value="{{ $jadwal->id }}">{{ $jadwal->mapel->nama_mapel ?? 'Mapel' }} - {{ $jadwal->hari }}</option>
            @endforeach
        </select>
    </div>

    <button wire:click="generate" class="btn btn-primary">Generate QR</button>

    @if ($token)
        <div class="mt-4">
            <p><strong>QR Token:</strong> {{ $token }}</p>
            <p><strong>Kadaluarsa:</strong> {{ $expiredAt }}</p>
            <div>
                <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ $token }}&size=200x200" alt="QR Code">
            </div>
        </div>
    @endif
</div>
