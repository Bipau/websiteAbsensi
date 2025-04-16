<div>
    <h4>Scan QR Code untuk Absen</h4>

    <div id="reader" style="width: 300px; margin-bottom: 1rem;"></div>

    <hr>

    <h5>Atau Masukkan Token QR Secara Manual</h5>
    <form wire:submit.prevent="prosesAbsenManual">
        <input type="text" wire:model="manualToken" class="form-control mb-2" placeholder="Masukkan Token QR">
        <button type="submit" class="btn btn-primary">Absen Manual</button>
    </form>

    @if ($message)
        <div class="alert alert-info mt-3">
            {{ $message }}
        </div>
    @endif
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let scanned = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (!scanned) {
                scanned = true;
                @this.call('prosesAbsen', decodedText);
            }
        }

        const html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            onScanSuccess
        );
    });
</script>
