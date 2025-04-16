<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AbsenGerbang;
use App\Models\AbsenKelas;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardComponent extends Component
{
    public function getStatisticsProperty()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;
        
        // Initialize statistics array with default values
        $stats = [
            'totalHadir' => 0,
            'tepatWaktu' => 0,
            'terlambat' => 0,
            'alpha' => 0,
            'persentaseKehadiran' => 0,
            'hadirKelas' => 0,
            'izinKelas' => 0,
            'alphaKelas' => 0,
            'totalAbsenKelas' => 0
        ];
        
        // Get absensi gerbang statistics
        $absenGerbang = AbsenGerbang::where('user_id', $user->id)
            ->whereMonth('tanggal', $thisMonth);
            
        $stats['totalHadir'] = $absenGerbang->count();
        $stats['tepatWaktu'] = $absenGerbang->where('status', 'Tepat Waktu')->count();
        $stats['terlambat'] = $absenGerbang->where('status', 'Terlambat')->count();
        
        // Calculate alpha (tidak hadir)
        $workDays = $this->getWorkDaysInMonth();
        $stats['alpha'] = $workDays - $stats['totalHadir'];
        
        // Calculate persentase kehadiran
        $stats['persentaseKehadiran'] = $stats['totalHadir'] > 0 ? 
            round(($stats['tepatWaktu'] / $stats['totalHadir']) * 100) : 0;

        // Add student-specific statistics if user is a student
        if ($user->role === 'siswa' && $user->siswa) {
            $absenKelas = AbsenKelas::where('siswa_id', $user->siswa->id)
                ->whereMonth('tanggal', $thisMonth);
                
            $stats['totalAbsenKelas'] = $absenKelas->count();
            $stats['hadirKelas'] = $absenKelas->where('status', 'Hadir')->count();
            $stats['izinKelas'] = $absenKelas->where('status', 'Izin')->count();
            $stats['alphaKelas'] = $absenKelas->where('status', 'Alpha')->count();
        }

        return $stats;
    }

    private function getWorkDaysInMonth()
    {
        $now = Carbon::now();
        $daysInMonth = $now->daysInMonth;
        $workDays = 0;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($now->year, $now->month, $day);
            // Exclude weekends (Saturday and Sunday)
            if ($date->dayOfWeek !== Carbon::SATURDAY && $date->dayOfWeek !== Carbon::SUNDAY) {
                $workDays++;
            }
        }

        return $workDays;
    }

    public function render()
    {
        return view('livewire.dashboard-component', [
            'statistics' => $this->statistics,
            'user' => Auth::user()
        ]);
    }
}
