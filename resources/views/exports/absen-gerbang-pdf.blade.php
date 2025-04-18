<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi Gerbang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            font-size: 13px;
            color: #000;
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

        .table-absensi {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .table-absensi th, .table-absensi td {
            border: 1px solid #000;
            padding: 6px 5px;
            text-align: center;
            vertical-align: middle;
        }

        .table-absensi th {
            background-color: #e6f2ff;
            font-weight: bold;
        }

        .table-absensi td {
            background-color: #fff;
        }

        .status-tepat {
            color: green;
            font-weight: bold;
        }

        .status-terlambat {
            color: red;
            font-weight: bold;
        }

        .ttd {
            width: 100%;
            margin-top: 50px;
            font-size: 14px;
        }

        .ttd td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .ttd .nama {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }

        .ttd .nip {
            margin-top: 4px;
        }

        hr {
            border: 1px solid #000;
            margin: 10px 0;
        }

        .section-title {
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 15px;
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

<hr>

<div class="section-title">
    <strong>Laporan Absensi Gerbang</strong><br>
    <strong>Tanggal:</strong> {{ $tanggal }}<br>
    <strong>Filter:</strong> {{ ucfirst($filter) }}
</div>

<table class="table-absensi">
    <thead>
        <tr>
            <th>No</th>
            <th>Role</th>
            <th>NIS/NIP</th>
            <th>Nama</th>
            @foreach($dates as $date)
                <th>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($absen as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ ucfirst($user['role']) }}</td>
                <td>{{ $user['identifier'] }}</td>
                <td>{{ $user['nama'] }}</td>
                @foreach($dates as $date)
                    <td>
                        @if(isset($user['attendance'][$date]))
                            <span class="{{ $user['attendance'][$date]['status'] === 'Tepat Waktu' ? 'status-tepat' : 'status-terlambat' }}">
                                {{ $user['attendance'][$date]['status'] }}
                            </span><br>
                            <small>
                                {{ \Carbon\Carbon::parse($user['attendance'][$date]['jam_masuk'])->format('H:i') }}
                                @if($user['attendance'][$date]['jam_keluar'])
                                    - {{ \Carbon\Carbon::parse($user['attendance'][$date]['jam_keluar'])->format('H:i') }}
                                @endif
                            </small>
                        @else
                            -
                        @endif
                    </td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ 4 + count($dates) }}" style="text-align: center">Tidak ada data absensi.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<table class="ttd">
    <tr>
        <td>
            Mengetahui,<br>
            Kepala Sekolah<br>
            <div class="nama">Drs. Ahmad Surya</div>
            <div class="nip">NIP. 19650412 199001 1 001</div>
        </td>
        <td>
            Bandung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
            Petugas Absensi<br>
            <div class="nama">______________________</div>
            <div class="nip">NIP. ______________________</div>
        </td>
    </tr>
</table>

</body>
</html>
