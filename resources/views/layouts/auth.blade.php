<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#ffffff">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="PIB Admin">
<link rel="manifest" href="{{ route('manifest') }}">
<title>@yield('title', 'Masuk') - (PIB) Perdana Inti Bersaudara</title>
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
<style>
    body.auth-body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background:
            radial-gradient(1200px 600px at 10% -10%, color-mix(in srgb, var(--primary) 5%, transparent), transparent),
            var(--background);
    }

    .auth-card {
        width: 100%;
        max-width: 420px;
    }

    .brand-top {
        text-align: center;
        margin-bottom: 28px;
    }

    .brand-top .brand-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        border: 1px solid var(--border);
        background: #fff;
    }

    .brand-top .brand-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 14px;
    }

    .brand-top h1 {
        font-weight: 700;
        font-size: 22px;
        letter-spacing: -.4px;
        color: var(--foreground);
        margin: 0;
    }

    .brand-top p {
        font-size: 14px;
        color: var(--muted-foreground);
        margin: 4px 0 0;
    }
</style>
</head>
<body class="auth-body">
<div class="auth-card">
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    if (typeof lucide !== 'undefined') {
        document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
    }
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('{{ route('sw') }}');
    }
</script>
@stack('scripts')
</body>
</html>