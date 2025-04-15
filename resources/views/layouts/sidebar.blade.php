<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                {{-- Dashboard - Available for all authenticated users --}}
                <li>
                    <a href="{{ route('dashboard') }}">
                        <img src="assets/img/icons/dashboard.svg" alt="img">
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Admin & Kesiswaan Only Menu --}}
                {{-- @if (auth()->user()->hasRole(['admin'])) --}}
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="assets/img/icons/users1.svg" alt="img">
                        <span>Users</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        {{-- @if (auth()->user()->isAdmin()) --}}
                        <li><a href="{{ route('users') }}">Users List</a></li>
                        {{-- @endif --}}
                        <li><a href="{{ route('siswa') }}">Users Siswa</a></li>
                        <li><a href="{{ route('karyawan') }}">Users karyawan</a></li>
                    </ul>
                </li>
                {{-- @endif --}}
                <li>
                    <a href="{{ route('kelas') }}">
                        <img src="assets/img/icons/dashboard.svg" alt="img">
                        <span>Kelas</span>
                    </a>
                </li>
                <li>
                    <li class="submenu">
                        <a href="javascript:void(0);">
                            <img src="assets/img/icons/users1.svg" alt="img">
                            <span>Mapel</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            {{-- @if (auth()->user()->isAdmin()) --}}
                            <li><a href="{{ route('mapel') }}">Mapel</a></li>
                            {{-- @endif --}}
                            <li><a href="{{ route('guruMapel') }}">Mapel & Pengampu</a></li>
                            <li><a href="{{ route('jadwal') }}">Jadwal Mapel</a></li>
                        </ul>
                    </li>

                </li>
                <li>
                    <li class="submenu">
                        <a href="">
                            <img src="assets/img/icons/users1.svg" alt="img">
                            <span></span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            {{-- @if (auth()->user()->isAdmin()) --}}
                            <li><a href="{{ route('AbsenGerbang') }}">Absen Gerbang</a></li>
                            {{-- @endif --}}
                            <li><a href="{{ route('AbsenKelas') }}">Absen Kelas</a></li>
                            
                        </ul>
                    </li>

                </li>

            </ul>
        </div>
    </div>
</div>
