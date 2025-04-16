<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AbsenGerbang;
use Carbon\Carbon;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
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

    public function getDataAbsenProperty()
    {
        $query = AbsenGerbang::with(['user', 'user.siswa'])
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

        return $query->latest('tanggal')->paginate($this->perPage);
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

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Role');
        $sheet->setCellValue('D1', 'NIS/NIP');
        $sheet->setCellValue('E1', 'Nama');
        $sheet->setCellValue('F1', 'Jam Masuk');
        $sheet->setCellValue('G1', 'Status');
        $sheet->setCellValue('H1', 'Jam Keluar');

        // Data
        $row = 2;
        foreach ($this->dataAbsen as $index => $data) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, Carbon::parse($data->tanggal)->format('d/m/Y'));
            $sheet->setCellValue('C' . $row, ucfirst($data->user->role));
            $sheet->setCellValue('D' . $row, $data->user->role === 'siswa' ? 
                ($data->user->siswa->nis ?? '-') : 
                ($data->user->karyawan->nip ?? '-'));
            $sheet->setCellValue('E' . $row, $data->user->nama);
            $sheet->setCellValue('F' . $row, Carbon::parse($data->jam_masuk)->format('H:i'));
            $sheet->setCellValue('G' . $row, $data->status);
            $sheet->setCellValue('H' . $row, $data->jam_keluar ? 
                Carbon::parse($data->jam_keluar)->format('H:i') : '-');
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Style the header
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E0E0E0',
                ],
            ],
        ]);

        // Output to browser
        $writer = new Xlsx($spreadsheet);
        $filename = 'absensi_gerbang_' . now()->format('Y-m-d') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
