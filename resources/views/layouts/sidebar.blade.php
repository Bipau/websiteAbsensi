<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                {{-- Dashboard - Tampil untuk semua yang login --}}
                <li>
                    <a href="{{ route('dashboard') }}">
                        <img src="assets/img/icons/dashboard.svg" alt="img">
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Menu Users - ADMIN SAJA --}}
                @if (auth()->user()->role === 'admin')
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="assets/img/icons/users1.svg" alt="img">
                        <span>Users</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('users') }}">Users List</a></li>
                        <li><a href="{{ route('siswa') }}">Users Siswa</a></li>
                        <li><a href="{{ route('karyawan') }}">Users Karyawan</a></li>
                    </ul>
                </li>
                @endif

                {{-- Kelas - ADMIN SAJA --}}
                @if (auth()->user()->role === 'admin')
                <li>
                    <a href="{{ route('kelas') }}">
                        <img src="assets/img/icons/dashboard.svg" alt="img">
                        <span>Kelas</span>
                    </a>
                </li>
                @endif

                {{-- Mapel, Guru Mapel, Jadwal - ADMIN SAJA --}}
                @if (auth()->user()->role === 'admin')
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="assets/img/icons/users1.svg" alt="img">
                        <span>Mapel</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('mapel') }}">Mapel</a></li>
                        <li><a href="{{ route('guruMapel') }}">Mapel & Pengampu</a></li>
                        <li><a href="{{ route('jadwal') }}">Jadwal Mapel</a></li>
                    </ul>
                </li>
                @endif

                {{-- Absensi --}}
                @if (in_array(auth()->user()->role, ['admin', 'guru', 'karyawan']))
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="assets/img/icons/users1.svg" alt="img">
                        <span>Absensi</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('AbsenGerbang') }}">Absen Gerbang</a></li>
                        @if(auth()->user()->role === 'admin')
                        <li><a href="{{ route('kelolaAbsenGerbang') }}">Kelola Absen Gerbang</a></li>
                        @endif
                        <li><a href="{{ route('AbsenKelas') }}">Absen Kelas</a></li>
                        <li><a href="{{ route('generateQr') }}">QR Absen</a></li>
                    </ul>
                </li>
                @endif

                {{-- Absen Kelas Siswa - SISWA SAJA --}}
                @if (auth()->user()->role === 'siswa')
                <li>
                    <a href="{{ route('AbsenGerbang') }}">
                        <img src="assets/img/icons/dashboard.svg" alt="img">
                        <span>Absen Gerbang</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa-absen-kelas') }}">
                        <img src="assets/img/icons/dashboard.svg" alt="img">
                        <span>Absen Kelas</span>
                    </a>
                </li>
                @endif

            </ul>
        </div>
    </div>
</div>
