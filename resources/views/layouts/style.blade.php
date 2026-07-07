<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', '(PIB) Perdana Inti Bersaudara')</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('logo1.png') }}" rel="icon">
    <link href="{{ asset('icon-192x192.png') }}" rel="apple-touch-icon">

    <!-- PWA -->
    <meta name="theme-color" content="#141a21">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="PIB">
    <link rel="manifest" href="{{ route('manifest') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('style/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('style/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('style/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('style/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('style/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('style/assets/css/main.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body class="@yield('body-class', 'index-page')" style="display: flex; flex-direction: column; min-height: 100vh;">

    @include('partials.style.header')

    <main class="main" style="flex: 1 0 auto;">
        @yield('content')
    </main>

    @if(!Request::is('about'))
        @include('partials.style.footer')
    @endif

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/6281274044912?text=Halo%20(PIB)%20Perdana%20Inti%20Bersaudara%2C%0A%0ASaya%20ingin%20menanyakan%20informasi%20lebih%20lanjut." 
       target="_blank" 
       class="whatsapp-float d-flex align-items-center justify-content-center" 
       title="Hubungi via WhatsApp"
       aria-label="Hubungi via WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>

    <style>
        .whatsapp-float {
            position: fixed;
            bottom: 20px;
            left: 20px;
            width: 56px;
            height: 56px;
            background-color: #25D366;
            color: #fff;
            border-radius: 50%;
            font-size: 28px;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
            z-index: 99999;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .whatsapp-float:hover {
            background-color: #1da851;
            color: #fff;
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.5);
        }
        @keyframes whatsapp-pulse {
            0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.6); }
            70% { box-shadow: 0 0 0 15px rgba(37, 211, 102, 0); }
            100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
        }
        .whatsapp-float {
            animation: whatsapp-pulse 2s infinite;
        }
        @media (max-width: 576px) {
            .whatsapp-float {
                bottom: 15px;
                left: 15px;
                width: 48px;
                height: 48px;
                font-size: 24px;
            }
        }

        .hover-primary:hover {
            color: #065cc2 !important;
        }
    </style>

    <!-- Vendor JS Files -->
    <script src="{{ asset('style/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('style/assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('style/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('style/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('style/assets/vendor/typed.js/typed.umd.js') }}"></script>
    <script src="{{ asset('style/assets/vendor/waypoints/noframework.waypoints.js') }}"></script>
    <script src="{{ asset('style/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('style/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('style/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('style/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('style/assets/js/main.js') }}"></script>

    @stack('scripts')

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ route('sw') }}');
        }
        let installPrompt = null;
        window.addEventListener('beforeinstallprompt', e => {
            e.preventDefault();
            installPrompt = e;
        });
    </script>

</body>

</html>
