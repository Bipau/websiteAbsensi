@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\User;
@endphp


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Ekstrakurikuler Sekolah</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/wavify@1.0.0/wavify.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --orange-primary: #FF6B00;
            --orange-secondary: #FFA500;
            --orange-light: #FFD580;
            --dark-text: #333333;
        }

        html {
            scroll-behavior: smooth;
        }


        body {
            font-family: 'Poppins', sans-serif;
        }

        .bg-orange-primary {
            background-color: var(--orange-primary);
        }

        .bg-orange-secondary {
            background-color: var(--orange-secondary);
        }

        .bg-orange-light {
            background-color: var(--orange-light);
        }

        .text-orange-primary {
            color: var(--orange-primary);
        }

        .btn-orange {
            background-color: var(--orange-primary);
            color: white;
            transition: all 0.3s ease;
        }

        .btn-orange:hover {
            background-color: var(--orange-secondary);
            transform: translateY(-2px);
        }

        .eskul-card {
            transition: all 0.3s ease;
            border-left: 4px solid var(--orange-primary);
        }

        .eskul-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .hero-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .hero-wave svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 80px;
        }

        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: var(--orange-primary);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        * Dropdown styles */ .dropdown-transition {
            transition: all 0.2s ease-in-out;
        }

        .dropdown-enter {
            opacity: 0;
            transform: scale(0.95);
        }

        .dropdown-enter-active {
            opacity: 1;
            transform: scale(1);
        }

        .dropdown-leave {
            opacity: 1;
            transform: scale(1);
        }

        .dropdown-leave-active {
            opacity: 0;
            transform: scale(0.95);
        }
    </style>
    @livewireStyles
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-md fixed w-full z-10">
        <div class="container mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="" class="text-2xl font-bold text-orange-primary">EduEkskul</a>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:items-center md:space-x-4">
                        <a href="{{ route('home') }}" class="nav-link px-3 py-2 text-dark-text font-medium">Home</a>
                        <a href="{{ route('home') }}#about" class="nav-link px-3 py-2 text-dark-text font-medium">About</a>
                        <a href="{{ route('home') }}#eskul" class="nav-link px-3 py-2 text-dark-text font-medium">Eskuls</a>
                        <a href="{{ route('pendaftaranEskul') }}"
                            class="nav-link px-3 py-2 text-dark-text font-medium">Pendaftaran</a>
                        @auth

                        @endauth
                    </div>
                </div>
                <div class="flex items-center">
                    @guest
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 text-dark-text font-medium hover:text-orange-primary">Login</a>
                        <a href="" class="ml-3 px-4 py-2 rounded-md btn-orange font-medium">Register</a>
                    @else
                        <div class="relative hidden md:block" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false"
                                class="flex items-center space-x-2 focus:outline-none" id="profileButton">
                                <img class="object-cover w-8 h-8 border-2 rounded-full border-orange-primary"
                                    src="https://ui-avatars.com/api/?name={{ Auth::user()->nama }}&color=FF6B00&background=FFD580"
                                    alt="User avatar">
                            </button>

                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1"
                                style="display: none;">
                                <a href="profile.html" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    My Profile
                                </a>
                                <a href="{{ route('home') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Halaman Utama
                                </a>
                                @role('admin|pembina|kesiswaan')
                                    <a href="{{ route('users') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        Dashboard
                                    </a>
                                @endrole
                                <form action="{{ route('login.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-orange-primary hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-primary"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('home') }}"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-primary hover:bg-gray-1000">Home</a>
                <a href="{{ route('home') }}#about"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-primary hover:bg-gray-100">About</a>
                <a href="{{ route('home') }}#eskul"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-primary hover:bg-gray-100">Eskuls</a>
                <a href="#"
                    class="block px-3 py-2 rounded-md text-base font-medium text-orange-primary bg-gray-100">Pendaftaran</a>
                @auth
                    @role('admin|pembina|kesiswaan')
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium
                         text-gray-700 hover:text-orange-primary hover:bg-gray-100">
                            Dashboard
                        </a>
                    @endrole
                    <a href=""
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-primary hover:bg-gray-100">Profile</a>
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-primary hover:bg-gray-100">Logout</a>
                    <form id="logout-form-mobile" action="{{ route('login.logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @endauth
            </div>
            @guest
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <a href="{{ route('login') }}"
                                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-primary hover:bg-gray-100">Login</a>
                        </div>
                        <div class="ml-3">
                            <a href=""
                                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-primary hover:bg-gray-100">Register</a>
                        </div>
                    </div>
                </div>
            @endguest
        </div>
    </nav>



    <!-- Eskul Section -->
    <section class="py-16" id="eskul">
        <div class="container mx-auto px-4">
            <!-- Livewire Component untuk Eskul Cards -->
            @livewire('component.pendaftaran-form-component')
        </div>
    </section>



    <!-- Call to Action -->
    <section class="py-16 bg-orange-primary text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">Siap Untuk Bergabung?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Jangan lewatkan kesempatan untuk mengembangkan bakat dan
                membangun jaringan pertemanan dengan minat yang sama.</p>
            <a href=""
                class="px-8 py-4 bg-white text-orange-primary font-bold rounded-lg shadow-lg hover:bg-gray-100 transition duration-300 text-lg">Daftar
                 Sekarang</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300">
        <div class="container mx-auto px-4 py-12">
            <div class="flex flex-wrap">
                <div class="w-full md:w-1/3 mb-8 md:mb-0">
                    <h3 class="text-2xl font-bold mb-4 text-white">EduEkskul</h3>
                    <p class="mb-4">Portal ekstrakurikuler untuk mengembangkan <br> bakat dan minat siswa.</p>
                    <div class="flex space-x-4">
                        <a href="{{ route('pendaftaranEskul') }}" class="text-gray-300 hover:text-orange-primary">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z">
                                </path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-orange-primary">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z">
                                </path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-orange-primary">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="w-full md:w-1/3 mb-8 md:mb-0">
                    <h4 class="text-xl font-semibold mb-4 text-white">Navigasi</h4>
                    <ul>
                        <li class="mb-2"><a href="{{ route('home') }}#" class="hover:text-orange-primary">Home</a></li>
                        <li class="mb-2"><a href="{{ route('home') }}#about" class="hover:text-orange-primary">About</a></li>
                        <li class="mb-2"><a href="{{ route('home') }}#eskul" class="hover:text-orange-primary">Eskuls</a></li>
                        <li class="mb-2"><a href="{{ route('pendaftaranEskul') }}" class="hover:text-orange-primary">Pendaftaran</a>
                        </li>
                        <li><a href="#" class="hover:text-orange-primary">Profile</a></li>
                    </ul>
                </div>
                <div class="w-full md:w-1/3">
                    <h4 class="text-xl font-semibold mb-4 text-white">Kontak</h4>
                    <p class="mb-2">Email: info@eduekskul.com</p>
                    <p class="mb-2">Phone: (021) 1234-5678</p>
                    <p>Alamat: Jl. Pendidikan No. 123, Jakarta</p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p>&copy; 2025 EduEkskul. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
    <script>
        // Toggle mobile menu
        document.querySelector('[aria-controls="mobile-menu"]').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('hidden');
            } else {
                mobileMenu.classList.add('hidden');
            }
        });

        $(document).ready(function() {
            $('#wave').wavify({
                height: 50, // Ketinggian gelombang
                bones: 5, // Jumlah lengkungan
                amplitude: 40, // Kedalaman gelombang
                color: '#FFA500', // Warna gelombang
                speed: 0.2 // Kecepatan animasi
            });
        });
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                Alpine.store('dropdown').open = false;
            }
        });
    </script>
</body>

</html>
