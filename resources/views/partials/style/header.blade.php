<header id="header" class="header fixed-top bg-white"
    style="box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1); height: 70px;">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between h-100">

        <a href="{{ url('/') }}" class="logo d-flex align-items-center text-decoration-none h-100">
            <img src="{{ asset('style/assets/img/PIBnew.png') }}" alt="PIB Logo"
                style="max-height: 40px; margin-right: 10px;">

        </a>

        <nav class="navbar navbar-expand-lg navbar-light p-0 h-100">
            <button class="navbar-toggler border-0 pe-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation" style="outline: none; box-shadow: none;">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav align-items-lg-center gap-lg-3">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-0 px-lg-2 {{ Request::is('/') ? 'active' : '' }}"
                            href="{{ Request::is('/') ? '#hero' : url('/#hero') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-0 px-lg-2 {{ Request::is('about') ? 'active' : '' }}"
                            href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-0 px-lg-2 {{ Request::is('layanan') ? 'active' : '' }}"
                            href="{{ route('layanan.page') }}">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-0 px-lg-2 {{ Request::is('produk') ? 'active' : '' }}"
                            href="{{ route('produk.page') }}">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold px-0 px-lg-2 {{ Request::is('kegiatan') ? 'active' : '' }}"
                            href="{{ route('kegiatan.page') }}">Kegiatan</a>
                    </li>
                    <li class="nav-item d-lg-none">
                        <a href="{{ route('contact.page') }}"
                            class="btn btn-primary w-100 rounded-pill py-2 fw-semibold mt-2"
                            style="background-color: #00a5e5; border: none; color: white;">Kontak PIB</a>
                    </li>
                </ul>
            </div>
        </nav>

        <a href="{{ route('contact.page') }}"
            class="btn btn-primary rounded-pill px-4 py-2 fw-semibold d-none d-lg-flex"
            style="background-color: #00a5e5; border: none; color: white; white-space: nowrap;">Kontak PIB</a>

    </div>
</header>

<style>
    .header {
        height: 70px;
    }

    .index-page {
        padding-top: 0;
    }

    body:not(.index-page) {
        padding-top: 70px;
    }

    .navbar-nav .nav-link {
        font-family: "Open Sans", sans-serif;
        font-size: 15px;
        transition: 0.3s;
        color: #13447f !important;
        position: relative;
        white-space: nowrap;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
        color: #00a5e5 !important;
    }

    .nav-link.active::after {
        display: none !important;
    }

    .navbar-nav .nav-link.active::after {
        content: '';
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: #00a5e5;
        border-radius: 2px;
    }

    .navbar-nav .dropdown-menu {
        border-radius: 8px;
        margin-top: 10px;
        border: 1px solid #f1f1f1;
    }

    .navbar-nav .dropdown-item {
        padding: 8px 16px;
        font-size: 14px;
        color: #13447f;
        transition: 0.2s;
    }

    .navbar-nav .dropdown-item:hover {
        background-color: #f0f7ff;
        color: #00a5e5;
    }

    @media (max-width: 991.98px) {
        .navbar-collapse {
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            background: #ffffff;
            padding: 1rem 1.5rem;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
            border-top: 1px solid #f1f1f1;
            z-index: 9999;
        }

        .navbar-nav {
            width: 100%;
        }

        .navbar-nav .nav-item {
            width: 100%;
        }

        .navbar-nav .nav-link {
            padding: 14px 0;
            font-size: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .navbar-nav .nav-link.active::after {
            display: none;
        }

        .navbar-nav .nav-link.active {
            color: #00a5e5 !important;
        }

        .navbar-nav .dropdown-menu {
            background: #f8fafc;
            margin: 0;
            padding: 4px 0;
            border: none;
            box-shadow: none !important;
        }

        .navbar-nav .dropdown-item {
            padding: 10px 16px;
            font-size: 14px;
        }

        .navbar-nav .dropdown-item:first-child {
            margin-top: 4px;
        }

        .navbar-nav .dropdown-item:last-child {
            margin-bottom: 4px;
        }

        .navbar-toggler {
            padding: 4px 0;
        }
    }

    @media (min-width: 992px) {
        .navbar-nav .nav-link {
            color: #13447f !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            line-height: 70px;
        }

        .navbar-nav .nav-link.active::after {
            bottom: 16px;
        }

        .navbar-nav .dropdown-menu {
            margin-top: 0;
        }

        .navbar .dropdown:hover .dropdown-menu {
            display: block;
        }
    }
</style>

<script>
    document.addEventListener('click', function(event) {
        var navbar = document.getElementById('navbarNav');
        var toggler = document.querySelector('.navbar-toggler');
        if (navbar.classList.contains('show') && !navbar.contains(event.target) && !toggler.contains(event
                .target)) {
            var bsCollapse = bootstrap.Collapse.getInstance(navbar);
            if (bsCollapse) bsCollapse.hide();
        }
    });
</script>
