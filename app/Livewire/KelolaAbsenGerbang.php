<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AbsenGerbang;
use Carbon\Carbon;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KelolaAbsenGerbang extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $filter = 'harian';
    public $tanggalAwal;
    public $tanggalAkhir;
    public $search = '';
    public $perPage = 10;
    public $roleFilter = 'all';

    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => 'harian'],
        'roleFilter' => ['except' => 'all'],
        'page' => ['except' => 1],
        'perPage' => ['except' => 10],

    ];

    public function mount()
    {
        $this->tanggalAwal = now()->format('Y-m-d');
        $this->tanggalAkhir = now()->format('Y-m-d');
    }

    public function updating($name)
    {
        if ($name === 'search' || $name === 'perPage') {
            $this->resetPage();
        }
    }

    public function updatedFilter()
    {
        if ($this->filter === 'manual') {
            $this->tanggalAwal = now()->format('Y-m-d');
            $this->tanggalAkhir = now()->format('Y-m-d');
        }
        $this->resetPage();
    }

    public function exportPDF()
    {
        try {
            $data = [
                'absen' => $this->dataAbsen,
                'tanggal' => now()->translatedFormat('d F Y'),
                'filter' => $this->filter,
                'dates' => collect($this->dataAbsen)->flatMap(function($user) {
                    return array_keys($user['attendance']->toArray());
                })->unique()->sort()->values()->toArray()
            ];

            $pdf = PDF::loadView('exports.absen-gerbang-pdf', $data)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isPhpEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isFontSubsettingEnabled' => true,
                    'defaultFont' => 'sans-serif',
                    'chroot' => public_path() // Add this line
                ]);

            return response()->streamDownload(
                function() use ($pdf) {
                    echo $pdf->output();
                },
                'absensi-gerbang-' . now()->format('Y-m-d') . '.pdf'
            );

        } catch (\Exception $e) {
            // Log error
            Log::error('PDF Export Error: ' . $e->getMessage());
            
            // Show error message to user
            session()->flash('error', 'Gagal mengekspor PDF: ' . $e->getMessage());
            
            return null;
        }
    }

    public function getDataAbsenProperty()
    {
        $query = AbsenGerbang::with(['user', 'user.siswa', 'user.karyawan'])
            ->when($this->search, function ($q) {
                $q->whereHas('user', function ($query) {
                    $query->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhereHas('siswa', function ($q) {
                            $q->where('nis', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->roleFilter !== 'all', function ($q) {
                $q->whereHas('user', function ($query) {
                    $query->where('role', $this->roleFilter);
                });
            });

        // Apply date filters
        switch ($this->filter) {
            case 'harian':
                $query->whereDate('tanggal', Carbon::today());
                break;

            case 'mingguan':
                $query->whereBetween('tanggal', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;

            case 'bulanan':
                $query->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year);
                break;

            case 'semester1':
                $query->whereBetween('tanggal', [
                    Carbon::create(Carbon::now()->year, 7, 1),
                    Carbon::create(Carbon::now()->year, 12, 31)
                ]);
                break;

            case 'semester2':
                $query->whereBetween('tanggal', [
                    Carbon::create(Carbon::now()->year, 1, 1),
                    Carbon::create(Carbon::now()->year, 6, 30)
                ]);
                break;

            case 'manual':
                if ($this->tanggalAwal && $this->tanggalAkhir) {
                    $query->whereBetween('tanggal', [
                        Carbon::parse($this->tanggalAwal)->startOfDay(),
                        Carbon::parse($this->tanggalAkhir)->endOfDay()
                    ]);
                }
                break;
        }

        // Get all records
        $records = $query->get();

        // Group by user
        $groupedData = $records->groupBy('user_id')->map(function ($group) {
            $firstRecord = $group->first();
            return [
                'user_id' => $firstRecord->user_id,
                'nama' => $firstRecord->user->nama,
                'role' => $firstRecord->user->role,
                'identifier' => $firstRecord->user->role === 'siswa' ?
                    ($firstRecord->user->siswa->nis ?? '-') : ($firstRecord->user->karyawan->nip ?? '-'),
                'attendance' => $group->groupBy(function ($item) {
                    return Carbon::parse($item->tanggal)->format('Y-m-d');
                })->map(function ($dayGroup) {
                    return [
                        'jam_masuk' => $dayGroup->first()->jam_masuk,
                        'jam_keluar' => $dayGroup->first()->jam_keluar,
                        'status' => $dayGroup->first()->status,
                        'foto' => $dayGroup->first()->foto
                    ];
                })
            ];
        })->values();

        return $groupedData;
    }

    public function render()
    {
        return view('livewire.kelola-absen-gerbang', [
            'dataAbsen' => $this->dataAbsen
        ]);
    }

    public function exportExcelManual()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Get unique dates
        $dates = collect($this->dataAbsen)->flatMap(function($user) {
            return array_keys($user['attendance']->toArray());
        })->unique()->sort()->values()->toArray();

        // Headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Role');
        $sheet->setCellValue('C1', 'NIS/NIP');
        $sheet->setCellValue('D1', 'Nama');

        $column = 'E';
        foreach($dates as $date) {
            $sheet->setCellValue($column.'1', Carbon::parse($date)->format('d/m/Y'));
            $column++;
        }

        // Data
        $row = 2;
        foreach($this->dataAbsen as $index => $user) {
            $sheet->setCellValue('A'.$row, $index + 1);
            $sheet->setCellValue('B'.$row, ucfirst($user['role']));
            $sheet->setCellValue('C'.$row, $user['identifier']);
            $sheet->setCellValue('D'.$row, $user['nama']);

            $column = 'E';
            foreach($dates as $date) {
                if(isset($user['attendance'][$date])) {
                    $attendance = $user['attendance'][$date];
                    $value = $attendance['status'] . "\n" .
                            Carbon::parse($attendance['jam_masuk'])->format('H:i');
                    if($attendance['jam_keluar']) {
                        $value .= "\n" . Carbon::parse($attendance['jam_keluar'])->format('H:i');
                    }
                    $sheet->setCellValue($column.$row, $value);
                } else {
                    $sheet->setCellValue($column.$row, '-');
                }
                $column++;
            }
            $row++;
        }

        // Styling
        foreach(range('A', $column) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle('A1:' . $column . '1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E0E0E0'],
            ],
        ]);

        // Output
        $writer = new Xlsx($spreadsheet);
        $filename = 'absensi_gerbang_' . now()->format('Y-m-d') . '.xlsx';

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
