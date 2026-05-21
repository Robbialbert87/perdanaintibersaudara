<header id="header" class="header fixed-top">

    @include('partials.clinic.topbar')

    <div class="branding d-flex align-items-cente">

        <div class="container position-relative d-flex align-items-center justify-content-between">
            <a href="{{ url('/') }}" class="logo d-flex align-items-center">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <!-- <img src="{{ asset('clinic/assets/img/logo.webp') }}" alt=""> -->
                <h1 class="sitename">PERDANA INTI BERSAUDARA</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="{{ url('/') }}" class="{{ Request::is('/') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('about') }}" class="{{ Request::is('about') ? 'active' : '' }}">About</a></li>
                    <li><a href="{{ url('/') }}#featured-departments">Layanan</a></li>
                    <li><a href="{{ url('/') }}#featured-services">Produk</a></li>
                    <li class="dropdown"><a href="#"><span>Project</span> <i
                                class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            <li><a href="#">Instalasi Alat</a></li>
                            <li><a href="#">Perbaikan & Maintenance</a></li>
                            <li><a href="#">Pengadaan Rumah Sakit</a></li>
                            <li><a href="#">Klinik & Laboratorium</a></li>
                            <li><a href="#">Konstruksi Gedung Kesehatan</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ url('/') }}#contact">Contact</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

        </div>

    </div>

</header>
