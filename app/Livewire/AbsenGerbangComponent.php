<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\AbsenGerbang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AbsenGerbangComponent extends Component
{
    use WithFileUploads;

    public $foto;
    public $latitude;
    public $longitude;
    public $status;
    public $jamMasuk;
    public $jamKeluar;

    protected $rules = [
        'foto' => 'required|image|max:2048',
    ];

    protected $messages = [
        'foto.required' => 'Foto absen harus diambil',
        'foto.image' => 'File harus berupa gambar',
        'foto.max' => 'Ukuran foto maksimal 2MB',
    ];

    public function mount()
    {
        $this->refreshAbsenStatus();
    }

    private function refreshAbsenStatus()
    {
        $today = Carbon::today();
        $batasWaktu = Carbon::today()->setTime(7, 0, 0); // Batas waktu jam 7 pagi
    
        $absen = AbsenGerbang::where('user_id', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();
    
        if ($absen) {
            $this->jamMasuk = Carbon::parse($absen->jam_masuk)->format('H:i');
            $this->jamKeluar = $absen->jam_keluar ? Carbon::parse($absen->jam_keluar)->format('H:i') : null;
    
            // Compare timestamps instead of just times
            $jamMasuk = Carbon::parse($absen->tanggal . ' ' . $absen->jam_masuk);
            $this->status = $jamMasuk->lt($batasWaktu) ? 'Tepat Waktu' : 'Terlambat';
        } else {
            $this->resetDailyStatus();
        }
    }
    private function resetDailyStatus()
    {
        $this->status = 'Belum Absen';
        $this->jamMasuk = null;
        $this->jamKeluar = null;
        // $this->foto = null;
        $this->latitude = null;
        $this->longitude = null;
    }

    public function getLocation()
    {
        // Replace dispatchBrowserEvent with dispatch
        $this->dispatch('getLocation');
    }
    public function setLocation($location)
    {
        $this->latitude = $location['lat'];
        $this->longitude = $location['lng'];<div class="mb-3">
        <label for="jurusan" class="form-label">Jurusan</label>
        <select class="form-control" id="jurusan" wire:model="jurusan">
            <option value="">Pilih Jurusan</option>
            <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
            <option value="Teknik Komputer Jaringan">Teknik Komputer Jaringan</option>
            <option value="Teknik Kendaraan Ringan">Teknik Kendaraan Ringan</option>
            <option value="Teknik Permesinan">Teknik Permesinan</option>
            <option value="Teknik Sepede Motor">Teknik Sepede Motor</option>
        </select>
        @error('jurusan') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    }


    protected $listeners = ['foto-captured' => 'handleFotoCapture'];

    public function handleFotoCapture($foto)
    {
        try {
            // Extract the base64 data
            $base64Image = explode(',', $foto)[1];
            $decodedImage = base64_decode($base64Image);<div class="mb-3"><div class="mb-3">
            <label for="jurusan" class="form-label">Jurusan</label><div class="mb-3"><div <div class="mb-3">
            <label for="jurusan" class="form-label">Jurusan</label>
            <select class="form-control" id="jurusan" wire:model="jurusan">
                <option value="">Pilih Jurusan</option>
                <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                <option value="Teknik Komputer Jaringan">Teknik Komputer Jaringan</option>
                <option value="Teknik Kendaraan Ringan">Teknik Kendaraan Ringan</option>
                <option value="Teknik Permesinan">Teknik Permesinan</option>
                <option value="Teknik Sepede Motor">Teknik Sepede Motor</option>
            </select>
            @error('jurusan') <span class="text-danger">{{ $message }}</span> @enderror
        </div>class="mb-3">
            <label for="jurusan" class="form-label">Jurusan</label>
            <select class="form-control" id="jurusan" wire:model="jurusan">
                <option value="">Pilih Jurusan</option>
                <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                <option value="Teknik Komputer Jaringan">Teknik Komputer Jaringan</option>
                <option value="Teknik Kendaraan Ringan">Teknik Kendaraan Ringan</option>
                <option value="Teknik Permesinan">Teknik Permesinan</option>
                <option value="Teknik Sepede Motor">Teknik Sepede Motor</option>
            </select>
            @error('jurusan') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
            <label for="jurusan" class="form-label">Jurusan</label>
            <select class="form-control" id="jurusan" wire:model="jurusan">
                <option value="">Pilih Jurusan</option>
                <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                <option value="Teknik Komputer Jaringan">Teknik Komputer Jaringan</option>
                <option value="Teknik Kendaraan Ringan">Teknik Kendaraan Ringan</option>
                <option value="Teknik Permesinan">Teknik Permesinan</option>
                <option value="Teknik Sepede Motor">Teknik Sepede Motor</option>
            </select>
            @error('jurusan') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
            <select class="form-control" id="jurusan" wire:model="jurusan">
                <option value="">Pilih Jurusan</option>
                <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                <option value="Teknik Komputer Jaringan">Teknik Komputer Jaringan</option>
                <option value="Teknik Kendaraan Ringan">Teknik Kendaraan Ringan</option>
                <option value="Teknik Permesinan">Teknik Permesinan</option>
                <option value="Teknik Sepede Motor">Teknik Sepede Motor</option>
            </select>
            @error('jurusan') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
            <label for="jurusan" class="form-label">Jurusan</label>
            <select class="form-control" id="jurusan" wire:model="jurusan">
                <option value="">Pilih Jurusan</option>
                <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                <option value="Teknik Komputer Jaringan">Teknik Komputer Jaringan</option>
                <option value="Teknik Kendaraan Ringan">Teknik Kendaraan Ringan</option>
                <option value="Teknik Permesinan">Teknik Permesinan</option>
                <option value="Teknik Sepede Motor">Teknik Sepede Motor</option>
            </select>
            @error('jurusan') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
            
            $filename = 'absensi-' . time() . '.jpg';
            $path = 'absen-gerbang/' . $filename;
            
            // Ensure the directory exists
            Storage::disk('public')->makeDirectory('absen-gerbang');
            
            // Save the file
            if (Storage::disk('public')->put($path, $decodedImage)) {
                $this->foto = $path;
                return true;
            }
            
            throw new \Exception('Failed to save file');
            
        } catch (\Exception $e) {
            Log::error('Photo capture error: ' . $e->getMessage());
            session()->flash('error', 'Gagal menyimpan foto: ' . $e->getMessage());
            return false;
        }
    }

 
public function absenMasuk()
{
    $existingAbsen = AbsenGerbang::where('user_id', Auth::id())
        ->whereDate('tanggal', Carbon::today())
        ->first();

    if ($existingAbsen) {
        session()->flash('error', 'Anda sudah melakukan absen masuk hari ini.');
        return;
    }

    $path = '';
    if (is_string($this->foto)) {
        $path = $this->foto;
    } else {
        $this->validate();
        if (is_object($this->foto)) {
            $path = $this->foto->store('absen-gerbang', 'public');
        } else {
            session()->flash('error', 'Foto tidak valid untuk disimpan.');
            return;
        }
    }

    $now = Carbon::now();
    $batasWaktu = Carbon::today()->setTime(7, 0, 0);
    
    // Compare full timestamps for accurate comparison
    $status = $now->lt($batasWaktu) ? 'Tepat Waktu' : 'Terlambat';

    AbsenGerbang::create([
        'user_id' => Auth::id(),
        'tanggal' => $now->toDateString(),
        'jam_masuk' => $now->toTimeString(),
        'foto' => $path,
        'status' => $status,
    ]);

    $this->resetFoto();
    $this->refreshAbsenStatus();
    session()->flash('success', 'Absen masuk berhasil!');
}

    public function absenKeluar()
    {
        $absen = AbsenGerbang::where('user_id', Auth::id())
            ->whereDate('tanggal', Carbon::today())
            ->first();

        if ($absen && !$absen->jam_keluar) {
            $absen->update([
                'jam_keluar' => Carbon::now()->format('H:i:s'),
            ]);

            $this->refreshAbsenStatus();
            session()->flash('success', 'Absen keluar berhasil!');
        } else if (!$absen) {
            session()->flash('error', 'Anda belum melakukan absen masuk hari ini.');
        } else {
            session()->flash('error', 'Anda sudah melakukan absen keluar hari ini.');
        }
    }

    public function resetFoto()
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            try {
                Storage::disk('public')->delete($this->foto);
            } catch (\Exception $e) {
                Log::error('Failed to delete photo: ' . $e->getMessage());
            }
        }
        $this->foto = null;
    }

    public function render()
    {
        // Always refresh status when rendering to ensure it's current
        $this->refreshAbsenStatus();

        return view('livewire.absen-gerbang-component');
    }
}
