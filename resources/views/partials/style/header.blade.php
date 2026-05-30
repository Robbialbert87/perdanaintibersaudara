<header id="header" class="header fixed-top bg-white" style="box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between" style="height: 100%;">

        <a href="{{ url('/') }}" class="logo d-flex align-items-center text-decoration-none">
            <img src="{{ asset('style/assets/img/PIBnew.png') }}" alt="PIB Logo"
                style="max-height: 40px; margin-right: 10px;">
            <h1 class="sitename m-0" style="font-size: 24px; font-weight: bold; color: #13447f;"></h1>
        </a>

        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"
                style="border: none; outline: none; box-shadow: none;">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav align-items-center gap-2 gap-lg-4">
                    <li class="nav-item w-100 w-lg-auto">
                        <a class="nav-link fw-semibold {{ Request::is('/') ? 'active text-primary' : 'text-dark' }}"
                            href="{{ Request::is('/') ? '#hero' : url('/#hero') }}">Home</a>
                    </li>
                    <li class="nav-item w-100 w-lg-auto">
                        <a class="nav-link fw-semibold {{ Request::is('about') ? 'active text-primary' : 'text-dark' }}"
                            href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item w-100 w-lg-auto">
                        <a class="nav-link fw-semibold {{ Request::is('layanan') ? 'active text-primary' : 'text-dark' }}"
                            href="{{ route('layanan.page') }}">Layanan</a>
                    </li>
                    <li class="nav-item w-100 w-lg-auto">
                        <a class="nav-link fw-semibold {{ Request::is('produk') ? 'active text-primary' : 'text-dark' }}"
                            href="{{ route('produk.page') }}">Produk</a>
                    </li>
                    <li class="nav-item w-100 w-lg-auto">
                        <a class="nav-link fw-semibold {{ Request::is('kegiatan') ? 'active text-primary' : 'text-dark' }}"
                            href="{{ route('kegiatan.page') }}">Kegiatan</a>
                    </li>
                    <li class="nav-item dropdown w-100 w-lg-auto">
                        <a class="nav-link dropdown-toggle fw-semibold text-dark" href="#" id="navbarDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Project
                        </a>
                        <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Instalasi Alat</a></li>
                            <li><a class="dropdown-item" href="#">Perbaikan & Maintenance</a></li>
                            <li><a class="dropdown-item" href="#">Pengadaan Rumah Sakit</a></li>
                            <li><a class="dropdown-item" href="#">Klinik & Laboratorium</a></li>
                            <li><a class="dropdown-item" href="#">Konstruksi Gedung Kesehatan</a></li>
                        </ul>
                    </li>
                    <li class="nav-item d-none d-lg-block ms-3 w-100 w-lg-auto">
                        <a href="{{ route('contact.page') }}"
                            class="btn btn-primary rounded-pill px-4 py-2 fw-semibold"
                            style="background-color: #00a5e5; border: none; color: white;">Contact</a>
                    </li>
                </ul>
            </div>
        </nav>

    </div>
</header>

<style>
    /* Custom overrides for the new top navbar */
    body {
        @if (Request::is('/'))
            padding-top: 0px;
        @else
            padding-top: 80px;
        @endif
    }

    /* Reduce gap between navbar and content on subpages */
    body:not(.index-page) main .section:first-of-type {
        padding-top: 30px !important;
    }

    .header {
        height: 80px;
        padding: 0;
    }

    .navbar-nav .nav-link {
        font-family: var(--nav-font, "Open Sans", sans-serif);
        font-size: 15px;
        transition: 0.3s;
    }

    .navbar-nav .nav-link:hover {
        color: #00a5e5 !important;
    }

    .navbar-nav .nav-link.active {
        color: #00a5e5 !important;
    }

    .navbar-nav .dropdown-menu {
        border-radius: 8px;
        margin-top: 10px;
    }

    .navbar-nav .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #00a5e5;
    }

    @media (max-width: 991.98px) {
        .navbar-collapse {
            background-color: #ffffff !important;
            position: absolute;
            top: 80px;
            left: 0;
            right: 0;
            width: 100%;
            padding: 1rem 1.5rem;
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            border-top: 1px solid #f1f1f1;
        }

        .navbar-nav {
            align-items: flex-start !important;
            width: 100%;
        }

        .navbar-nav .nav-item {
            width: 100%;
        }

        .navbar-nav .nav-link {
            padding: 12px 0;
            width: 100%;
            border-bottom: 1px solid #f8f9fa;
            color: #13447f !important;
        }

        .navbar-nav .dropdown-item {
            color: #13447f !important;
        }

        .navbar-nav .nav-item:last-child .nav-link {
            border-bottom: none;
        }
    }
</style>
