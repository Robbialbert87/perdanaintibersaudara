<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between header-container">
        <a href="{{ url('/') }}" class="logo d-flex align-items-center text-decoration-none">
            <img src="{{ asset('style/assets/img/PIBnew.png') }}" alt="PIB Logo">
        </a>

        <nav class="navmenu">
            <ul>
                <li>
                    <a href="{{ Request::is('/') ? '#hero' : url('/#hero') }}" class="{{ Request::is('/') ? 'active' : '' }}">
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('about') }}" class="{{ Request::is('about') ? 'active' : '' }}">
                        About
                    </a>
                </li>
                <li>
                    <a href="{{ route('layanan.page') }}" class="{{ Request::is('layanan') ? 'active' : '' }}">
                        Layanan
                    </a>
                </li>
                <li>
                    <a href="{{ route('produk.page') }}" class="{{ Request::is('produk') ? 'active' : '' }}">
                        Produk
                    </a>
                </li>
                <li>
                    <a href="{{ route('kegiatan.page') }}" class="{{ Request::is('kegiatan') ? 'active' : '' }}">
                        Kegiatan
                    </a>
                </li>
                <li class="d-xl-none">
                    <a href="{{ route('contact.page') }}" class="btn-getstarted">Kontak PIB</a>
                </li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a href="{{ route('contact.page') }}" class="btn-getstarted d-none d-xl-flex">Kontak PIB</a>
    </div>
</header>

<style>
/* === OVERRIDE main.css lama === */
.header .navmenu {
    position: static;
    right: auto;
    width: auto;
    bottom: auto;
    overflow: visible;
    z-index: auto;
    background: none;
}

.header .mobile-nav-toggle {
    display: none !important;
    position: static;
    top: auto;
    right: auto;
    font-size: 30px;
    z-index: auto;
}

@media (max-width: 1199.98px) {
    .header .mobile-nav-toggle {
        display: block !important;
    }
}
/* === END OVERRIDE === */

.header {
    --background-color: rgba(255, 255, 255, 0);
    color: #314862;
    background-color: var(--background-color);
    padding: 18px 0;
    transition: all 0.5s;
    z-index: 997;
}

.header .header-container {
    background: #ffffff;
    border-radius: 50px;
    padding: 10px 25px 10px 30px;
    box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.08);
}

.scrolled .header {
    padding: 10px 0;
}

.scrolled .header .header-container {
    box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
}

.header .logo {
    line-height: 1;
}

.header .logo img {
    max-height: 40px;
    margin-right: 8px;
}

.header .btn-getstarted {
    color: #ffffff;
    background: #00a5e5;
    font-size: 15px;
    padding: 10px 28px;
    border-radius: 50px;
    transition: 0.3s;
    text-decoration: none;
    font-weight: 600;
    white-space: nowrap;
    font-family: "Open Sans", sans-serif;
    border: none;
}

.header .btn-getstarted:hover {
    background: #0095cc;
    color: #ffffff;
}

@media (max-width: 1199.98px) {
    .header {
        padding-top: 12px;
    }

    .header .header-container {
        margin-left: 10px;
        margin-right: 10px;
        padding: 8px 12px 8px 18px;
    }

    .header .logo {
        order: 1;
    }

    .header .btn-getstarted {
        order: 2;
        margin: 0 10px 0 0;
        padding: 8px 18px;
        font-size: 14px;
    }

    .header .navmenu {
        order: 3;
    }
}

@media (min-width: 1200px) {
    .navmenu {
        padding: 0;
    }

    .navmenu ul {
        margin: 0;
        padding: 0;
        display: flex;
        list-style: none;
        align-items: center;
    }

    .navmenu li {
        position: relative;
    }

    .navmenu a {
        color: #13447f;
        padding: 12px 18px;
        font-size: 16px;
        font-family: "Open Sans", sans-serif;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: space-between;
        white-space: nowrap;
        transition: 0.3s;
        text-decoration: none;
    }

    .navmenu a:hover,
    .navmenu .active {
        color: #00a5e5;
    }
}

.mobile-nav-toggle {
    color: #13447f;
    font-size: 30px;
    line-height: 0;
    margin-right: 0;
    cursor: pointer;
    transition: color 0.3s;
    display: none;
}

@media (max-width: 1199.98px) {
    .mobile-nav-toggle {
        display: block;
    }

    .navmenu {
        padding: 0;
        z-index: 9997;
    }

    .navmenu ul {
        display: none;
        list-style: none;
        position: fixed;
        top: 95px;
        left: 15px;
        right: 15px;
        width: auto;
        padding: 15px 0;
        margin: 0;
        border-radius: 12px;
        background-color: #ffffff;
        overflow-y: auto;
        transition: 0.3s;
        z-index: 9998;
        box-shadow: 0px 10px 40px rgba(0, 0, 0, 0.15);
    }

    .navmenu a {
        color: #13447f;
        padding: 14px 24px;
        font-family: "Open Sans", sans-serif;
        font-size: 17px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: space-between;
        white-space: nowrap;
        transition: 0.3s;
        text-decoration: none;
    }

    .navmenu a:hover,
    .navmenu .active {
        color: #00a5e5;
        background-color: #f0f9ff;
    }

    .navmenu .btn-getstarted {
        color: #ffffff !important;
        background: #00a5e5;
        font-size: 16px;
        padding: 12px 24px;
        border-radius: 50px;
        text-align: center;
        margin: 10px 24px;
        font-weight: 600;
    }

    .navmenu .btn-getstarted:hover {
        background: #0095cc;
        color: #ffffff !important;
    }

    .mobile-nav-active {
        overflow: hidden;
    }

    .mobile-nav-active .mobile-nav-toggle {
        color: #13447f;
    }

    .mobile-nav-active .navmenu {
        position: fixed;
        overflow: hidden;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        transition: 0.3s;
        z-index: 9996;
    }

    .mobile-nav-active .navmenu>ul {
        display: block;
    }
}

.index-page {
    padding-top: 0;
}

.index-page .hero {
    padding-top: 120px !important;
}

.index-page .hero-content {
    margin-top: 30px;
}

@media (max-width: 1199.98px) {
    .index-page .hero {
        padding-top: 80px !important;
    }

    .index-page .hero-content {
        margin-top: 10px;
    }
}

body:not(.index-page) {
    padding-top: 96px;
}

@media (max-width: 1199.98px) {
    body:not(.index-page) {
        padding-top: 75px;
    }

    body:not(.index-page) .section {
        padding-top: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var mobileNavToggle = document.querySelector('.mobile-nav-toggle');
    var body = document.body;

    if (mobileNavToggle) {
        // Clone & replace to remove old main.js event listeners
        var newToggle = mobileNavToggle.cloneNode(true);
        mobileNavToggle.parentNode.replaceChild(newToggle, mobileNavToggle);
        newToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            body.classList.toggle('mobile-nav-active');
            var icon = this;
            if (icon.classList.contains('bi-list')) {
                icon.classList.remove('bi-list');
                icon.classList.add('bi-x');
            } else {
                icon.classList.remove('bi-x');
                icon.classList.add('bi-list');
            }
        });
    }

    document.addEventListener('click', function(event) {
        var menuUl = document.querySelector('.navmenu > ul');
        var toggle = document.querySelector('.mobile-nav-toggle');
        if (menuUl && !menuUl.contains(event.target) &&
            toggle && !toggle.contains(event.target) &&
            (body.classList.contains('mobile-nav-active') || toggle.classList.contains('bi-x'))) {
            body.classList.remove('mobile-nav-active');
            if (toggle.classList.contains('bi-x')) {
                toggle.classList.remove('bi-x');
                toggle.classList.add('bi-list');
            }
        }
    });

    var navLinks = document.querySelectorAll('.navmenu a:not(.btn-getstarted)');
    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            body.classList.remove('mobile-nav-active');
            var toggle = document.querySelector('.mobile-nav-toggle');
            if (toggle && toggle.classList.contains('bi-x')) {
                toggle.classList.remove('bi-x');
                toggle.classList.add('bi-list');
            }
        });
    });

    var header = document.getElementById('header');
    if (header) {
        var headerHeight = header.offsetHeight;
        var heroSection = document.querySelector('#hero');
        if (heroSection) {
            heroSection.style.paddingTop = '0';
        }
    }
});

window.addEventListener('scroll', function() {
    var header = document.getElementById('header');
    if (header) {
        if (window.scrollY > 50) {
            document.body.classList.add('scrolled');
        } else {
            document.body.classList.remove('scrolled');
        }
    }
});
</script>