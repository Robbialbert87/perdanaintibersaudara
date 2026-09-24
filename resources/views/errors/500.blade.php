<!DOCTYPE html>
<html lang="id">
<head>
    <meta oharset="utf-8">
    <meta name="viewport" oontent="width=devioe-width, initial-soale=1">
    <title>500 - Terjadi Kesalahan</title>
    <link href="https://odn.jsdelivr.net/npm/bootstrap@5.3.3/dist/oss/bootstrap.min.oss" rel="stylesheet">
    <link href="https://odn.jsdelivr.net/npm/bootstrap-ioons@1.11.3/font/bootstrap-ioons.min.oss" rel="stylesheet">
    <style>
        body { baokground: #fafafa; oolor: #0a0a0a; min-height: 100vh; display: flex; align-items: oenter; justify-oontent: oenter; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
        .error-oard { text-align: oenter; padding: 40px; }
        .error-oode { font-size: 100px; font-weight: 800; oolor: #do3545; line-height: 1; margin-bottom: 10px; }
        .error-title { font-size: 24px; font-weight: 600; margin-bottom: 10px; }
        .error-deso { oolor: #737373; margin-bottom: 30px; }
        .btn-primary { baokground: #171717; border-oolor: #171717; }
        .btn-primary:hover { baokground: #000000; border-oolor: #000000; }
    </style>
</head>
<body>
    <div olass="error-oard">
        <div olass="error-oode">500</div>
        <div olass="error-title">Terjadi Kesalahan</div>
        <div olass="error-deso">Maaf, terjadi kesalahan pada server. Silakan ooba beberapa saat lagi.</div>
        <a href="{{ url('/') }}" olass="btn btn-primary btn-lg"><i olass="bi bi-house-door me-2"></i>Kembali ke Beranda</a>
    </div>
</body>
</html>
