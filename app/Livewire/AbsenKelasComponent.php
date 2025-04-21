<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AbsenKelas;
use App\Models\Kelas;
use App\Models\User;
use Carbon\Carbon;
use Livewire\WithPagination;
use App\Exports\AbsenKelasExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;


class AbsenKelasComponent extends Component
{
    use WithPagination;

    public $kelasId;
    public $SiswaList;
    public $filter = 'harian';
    public $tanggalAwal;
    public $tanggalAkhir;
    public $kelasList;
    public $perPage = 10;

    protected $queryString = [
        'kelasId' => ['except' => ''],
        'filter' => ['except' => 'harian'],
        'tanggalAwal' => ['except' => ''],
        'tanggalAkhir' => ['except' => ''],
    ];

    public function mount()
    {
        $this->tanggalAwal = now()->format('Y-m-d');
        $this->tanggalAkhir = now()->format('Y-m-d');
        $this->kelasList = Kelas::all();
        $this->SiswaList = User::where('role', 'siswa')->get();
    }

    public function getAbsenDataProperty()
    {
        $query = AbsenKelas::with(['user', 'kelas', 'jadwal.mapel'])
            ->when($this->kelasId, function ($q) {
                return $q->where('kelas_id', $this->kelasId);
            });

        // Apply filters
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

        $records = $query->get();

        // Group by student
        $groupedData = $records->groupBy('siswa_id')->map(function($group) {
            $firstRecord = $group->first();
            return [
                'nis' => $firstRecord->user->siswa->nis ?? '-',
                'nama' => $firstRecord->user->nama,
                'kelas' => $firstRecord->kelas->nama_kelas ?? '-',
                'attendance' => $group->groupBy(function($item) {
                    return $item->tanggal->format('Y-m-d');
                })->map(function($dayGroup) {
                    return [
                        'status' => $dayGroup->map(function($record) {
                            return [
                                'status' => $record->status,
                                'jam_absen' => $record->jam_absen->format('H:i'),
                                'mapel' => $record->jadwal->mapel->nama_mapel ?? '-'
                            ];
                        })->toArray()
                    ];
                })
            ];
        })->values();

        return $groupedData;
    }

    public function exportPDF()
    {

        $walikelas = null;
        if ($this->kelasId) {
            $kelas = Kelas::with('walikelas.user')->find($this->kelasId);
            $walikelas = $kelas->walikelas ? [
                'nama' => $kelas->walikelas->user->nama ?? '-',
                'nip' => $kelas->walikelas->nip ?? '-'
            ] : null;
        }

        $data = [
            'absen' => $this->absenData,
            'tanggal' => now()->format('d F Y'),
            'filter' => $this->filter,
            'dates' => collect($this->absenData)->flatMap(function($student) {
                return array_keys($student['attendance']->toArray());
            })->unique()->sort()->values()->toArray(),
            'kelas' => $this->kelasId ? Kelas::find($this->kelasId)->nama_kelas : 'Semua Kelas',
            'walikelas' => $walikelas
        ];

        $pdf = PDF::loadView('exports.absen-kelas-pdf', $data)->setPaper('a4', 'landscape');
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'absensi-kelas-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcelManual()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Get unique dates
        $dates = collect($this->absenData)->flatMap(function($student) {
            return array_keys($student['attendance']->toArray());
        })->unique()->sort()->values()->toArray();

        // Headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIS');
        $sheet->setCellValue('C1', 'Nama Siswa');
        $sheet->setCellValue('D1', 'Kelas');

        $column = 'E';
        foreach($dates as $date) {
            $sheet->setCellValue($column.'1', Carbon::parse($date)->format('d/m/Y'));
            $column++;
        }

        // Data
        $row = 2;
        foreach($this->absenData as $index => $student) {
            $sheet->setCellValue('A'.$row, $index + 1);
            $sheet->setCellValue('B'.$row, $student['nis']);
            $sheet->setCellValue('C'.$row, $student['nama']);
            $sheet->setCellValue('D'.$row, $student['kelas']);

            $column = 'E';
            foreach($dates as $date) {
                if(isset($student['attendance'][$date])) {
                    // Fix: Convert array to collection and extract statuses
                    $statuses = collect($student['attendance'][$date]['status'])
                        ->map(function($item) {
                            return $item['status'] . ' (' . $item['jam_absen'] . ' - ' . $item['mapel'] . ')';
                        })
                        ->join(', ');
                    $sheet->setCellValue($column.$row, $statuses);
                } else {
                    $sheet->setCellValue($column.$row, '-');
                }
                $column++;
            }
            $row++;
        }

        // Styling
        $sheet->getStyle('A1:' . $column . '1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E0E0E0'],
            ],
        ]);

        // Auto-size columns
        foreach(range('A', $column) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'absensi-kelas-' . now()->format('Y-m-d') . '.xlsx';

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function render()
    {
        return view('livewire.absen-kelas-component', [
            'absen' => $this->absenData
        ]);

    }
}
