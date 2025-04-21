<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            font-size: 13px;
        }

        .kop-surat {
            text-align: center;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-surat h1 {
            margin: 0;
            font-size: 22px;
        }

        .kop-surat p {
            margin: 2px 0;
            font-size: 14px;
        }

        .periode {
            text-align: right;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .tabel-absensi {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .tabel-absensi,
        .tabel-absensi th,
        .tabel-absensi td {
            border: 1px solid black;
        }

        .tabel-absensi th {
            background-color: #f0f0f0;
            padding: 8px 4px;
        }

        .tabel-absensi td {
            padding: 6px 4px;
            text-align: center;
        }

        .ttd {
            width: 100%;
            margin-top: 40px;
        }

        .ttd td {
            text-align: center;
            padding-top: 60px;
        }
    </style>
</head>

<body>

    <table style="width: 100%;">
        <tr>
            <td style="width: 100px;">
                <img src="assets/img/logo-igsr.png" alt="Logo Sekolah" style="width: 80px;">
            </td>
            <td style="text-align: left;">
                <h1 style="margin: 0;">SMK IGASAR PINDAD BANDUNG</h1>
                <p>Jl. Cisaranten Kulon No.17, Cisaranten Kulon, Kec. Arcamanik, Kota Bandung, Jawa Barat 40293</p>
                <p>Telp. (021) 12345678990 | Email: info@smkigasarpindad.sch.id</p>
            </td>
        </tr>
    </table>

    <hr style="border: 1px solid #000; margin: 10px 0;">

    <div style="margin-top: 10px; margin-bottom: 10px;">
        <strong>Laporan Absensi Kelas</strong><br>
        <strong>Tanggal:</strong> {{ $tanggal }}<br>
        <strong>Kelas:</strong> {{ $kelas }}<br>
        <strong>Filter:</strong> {{ ucfirst($filter) }}
    </div>

    <table class="tabel-absensi">
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                @php
                    $dates = collect($absen)
                        ->flatMap(function ($student) {
                            return array_keys($student['attendance']->toArray());
                        })
                        ->unique()
                        ->sort()
                        ->values()
                        ->toArray();
                @endphp
                @foreach ($dates as $date)
                    <th>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($absen as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student['nis'] }}</td>
                    <td>{{ $student['nama'] }}</td>
                    <td>{{ $student['kelas'] }}</td>
                    @foreach ($dates as $date)
                        <td class="text-center">
                            @if (isset($student['attendance'][$date]))
                                @foreach ($student['attendance'][$date]['status'] as $attendance)
                                    <div class="d-flex flex-column align-items-center">
                                        <span
                                            class="badge bg-{{ $attendance['status'] === 'Hadir' ? 'success' : 'danger' }}">
                                            {{ $attendance['status'] }}
                                        </span>
                                        <small class="d-block">
                                            {{ $attendance['jam_absen'] }}
                                        </small>
                                        <small class="d-block text-muted">
                                            {{ $attendance['mapel'] }}
                                        </small>
                                    </div>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 4 + count($dates) }}" class="text-center">Tidak ada data absensi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="ttd" border="0">
        <tr>
            <td width="50%">
                Mengetahui,<br>
                Kepala Sekolah<br><br><br><br>
                <u><strong>Drs. Ahmad Surya</strong></u><br>
                NIP. 19650412 199001 1 001
            </td>
            <td width="50%">
                Mengetahui,<br>
                Wali Kelas {{ $kelas }}<br><br><br><br>
                <u><strong>{{ $walikelas ? $walikelas['nama'] : '______________________' }}</strong></u><br>
                NIP. {{ $walikelas ? $walikelas['nip'] : '______________________' }}
            </td>
        </tr>
    </table>

</body>

</html>
