<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" id="metaThemeColor" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="PIB Admin">
    <link rel="manifest" href="{{ route('manifest') }}">
    <title>@yield('title', 'Dashboard') - (PIB) Perdana Inti Bersaudara</title>
    @stack('styles')
    <link href="{{ asset('logo1.png') }}" rel="icon">
    <link href="{{ asset('icon-192x192.png') }}" rel="apple-touch-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('style/admin-design.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300..800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    {{-- set tema sebelum paint agar tidak ada flash --}}
    <script>
        (function() {
            var t = 'light';
            try { t = localStorage.getItem('pib-theme') || 'light'; } catch (e) {}
            if (t === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                document.getElementById('metaThemeColor').setAttribute('content', '#0a0a0a');
            }
        })();
    </script>
</head>

<body>

    <div class="app-shell">
        @include('partials.admin.sidebar')

        <main class="app-main">
            @include('partials.admin.topbar')

            <div class="app-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom:16px">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom:16px">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('modals')

    <script>
        (function() {
            var sb = document.getElementById('appSidebar');
            var ov = document.getElementById('appOverlay');

            function isMobile() {
                return window.innerWidth < 992;
            }

            function closeDrawer() {
                if (sb) sb.classList.remove('show');
                if (ov) ov.classList.remove('show');
            }

            var toggle = document.getElementById('sidebarToggle');
            if (toggle) {
                toggle.addEventListener('click', function() {
                    if (!sb) return;
                    if (isMobile()) {
                        sb.classList.toggle('show');
                        if (ov) ov.classList.toggle('show');
                    } else {
                        document.body.classList.toggle('sidebar-collapsed');
                    }
                });
            }

            if (ov) ov.addEventListener('click', closeDrawer);
            if (sb) {
                sb.addEventListener('click', function(e) {
                    if (isMobile() && sb.classList.contains('show') && e.target.closest('.sb-link')) {
                        closeDrawer();
                    }
                });
            }

            // ---- tema (light/dark) ----
            function applyTheme(t) {
                var dark = t === 'dark';
                document.documentElement.classList.toggle('dark', dark);
                document.documentElement.setAttribute('data-bs-theme', t);
                var mc = document.getElementById('metaThemeColor');
                if (mc) mc.setAttribute('content', dark ? '#0a0a0a' : '#ffffff');
                try { localStorage.setItem('pib-theme', t); } catch (e) {}
            }

            var themeBtn = document.getElementById('themeToggle');
            if (themeBtn) {
                themeBtn.addEventListener('click', function() {
                    applyTheme(document.documentElement.classList.contains('dark') ? 'light' : 'dark');
                });
            }

            // ---- filter menu sidebar dari topbar search ----
            var filter = document.getElementById('navFilter');
            if (filter && sb) {
                filter.addEventListener('input', function() {
                    var q = filter.value.trim().toLowerCase();
                    var links = sb.querySelectorAll('.sb-link');
                    links.forEach(function(l) {
                        l.style.display = (!q || l.textContent.toLowerCase().indexOf(q) > -1) ? '' : 'none';
                    });
                    var groups = sb.querySelectorAll('.sb-group');
                    groups.forEach(function(g) {
                        var any = false;
                        g.querySelectorAll('.sb-link').forEach(function(l) {
                            if (l.style.display !== 'none') any = true;
                        });
                        g.style.display = any ? '' : 'none';
                    });
                });
            }
        })();

        // ---- ikon lucide ----
        if (typeof lucide !== 'undefined') {
            document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
        }

        // ---- PWA & share (dipertahankan) ----
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ route('sw') }}');
        }
        let installPrompt = null;
        window.addEventListener('beforeinstallprompt', e => {
            e.preventDefault();
            installPrompt = e;
        });
        function shareAdmin() {
            if (navigator.share) {
                navigator.share({
                    title: 'PIB Admin',
                    text: 'Panel administrasi (PIB) Perdana Inti Bersaudara',
                    url: window.location.href
                }).catch(() => {});
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Link disalin ke clipboard');
                });
            }
        }
    </script>
    @stack('scripts')
</body>

</html>