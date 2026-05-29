<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

        <a href="{{ url('/') }}" class="logo d-flex align-items-center">
            <!-- Header displays logo only on small screens when sidebar is hidden -->
            <img src="{{ asset('style/assets/img/PIBnew.png') }}" alt="PIB Logo" class="d-xl-none">
        </a>

        <nav id="navmenu" class="navmenu">

            <div class="profile-img">
                <img src="{{ asset('style/assets/img/PIBnew.png') }}" alt="(PIB) Perdana Inti Bersaudara" class="img-fluid rounded-circle" style="background-color: white; padding: 10px;">
            </div>

            <a href="{{ url('/') }}" class="logo d-flex align-items-center justify-content-center">
                <h1 class="sitename" style="font-size: 18px; text-align: center; font-weight: bold; margin-top: 10px;">(PIB) Perdana Inti Bersaudara</h1>
            </a>

            <div class="social-links text-center">
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="mailto:perdanaintibersaudara@gmail.com" class="email"><i class="bi bi-envelope"></i></a>
            </div>

            <ul>
                <li><a href="{{ Request::is('/') ? '#hero' : url('/#hero') }}" class="{{ Request::is('/') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('about') }}" class="{{ Request::is('about') ? 'active' : '' }}">About</a></li>
                <li><a href="{{ Request::is('/') ? '#layanan' : url('/#layanan') }}">Layanan</a></li>
                <li><a href="{{ Request::is('/') ? '#produk' : url('/#produk') }}">Produk</a></li>
                <li class="dropdown"><a href="#"><span>Project</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="#">Instalasi Alat</a></li>
                        <li><a href="#">Perbaikan & Maintenance</a></li>
                        <li><a href="#">Pengadaan Rumah Sakit</a></li>
                        <li><a href="#">Klinik & Laboratorium</a></li>
                        <li><a href="#">Konstruksi Gedung Kesehatan</a></li>
                    </ul>
                </li>
                <li><a href="{{ Request::is('/') ? '#contact' : url('/#contact') }}">Contact</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

    </div>
</header>
