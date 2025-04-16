<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AbsenKelas;
use App\Models\Kelas;
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
    }

    public function getAbsenDataProperty()
    {
        $query = AbsenKelas::with(['siswa.user', 'kelas', 'jadwal.mapel'])
            ->when($this->kelasId, function ($q) {
                return $q->where('kelas_id', $this->kelasId);
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



    public function exportPDF()
    {
        $data = [
            'absen' => $this->absenData,
            'tanggal' => now()->format('d F Y'),
            'filter' => $this->filter,
            'kelas' => $this->kelasId ? Kelas::find($this->kelasId)->nama_kelas : 'Semua Kelas'
        ];

        $pdf = PDF::loadView('exports.absen-kelas-pdf', $data);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'absensi-kelas-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcelManual()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'Nama Siswa');
        $sheet->setCellValue('C1', 'Kelas');
        $sheet->setCellValue('D1', 'Mapel');
        $sheet->setCellValue('E1', 'Status');

        // Data
        $row = 2;
        foreach ($this->absenData->items() as $data) {
            $sheet->setCellValue('A' . $row, \Carbon\Carbon::parse($data->tanggal)->format('Y-m-d'));
            $sheet->setCellValue('B' . $row, $data->siswa->user->nama ?? '-');
            $sheet->setCellValue('C' . $row, $data->kelas->nama_kelas ?? '-');
            $sheet->setCellValue('D' . $row, $data->jadwal->mapel->nama_mapel ?? '-');
            $sheet->setCellValue('E' . $row, $data->status);
            $row++;
        }
        

        // Simpan sementara ke file
        $fileName = 'absensi-kelas-' . now()->format('Y-m-d') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        // Download response
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
    public function render()
    {
        return view('livewire.absen-kelas-component', [
            'absen' => $this->absenData
        ]);
    }
}
