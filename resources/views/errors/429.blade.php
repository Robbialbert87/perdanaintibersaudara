<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>429 - Terlalu Banyak Permintaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #141a21; color: #e0e0e0; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
        .error-card { text-align: center; padding: 40px; }
        .error-code { font-size: 100px; font-weight: 800; color: #fd7e14; line-height: 1; margin-bottom: 10px; }
        .error-title { font-size: 24px; font-weight: 600; margin-bottom: 10px; }
        .error-desc { color: #8899a6; margin-bottom: 30px; }
        .btn-primary { background: #00a76f; border-color: #00a76f; }
        .btn-primary:hover { background: #009b65; border-color: #009b65; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code">429</div>
        <div class="error-title">Terlalu Banyak Permintaan</div>
        <div class="error-desc">Anda terlalu sering mengirim permintaan. Silakan tunggu beberapa saat.</div>
        <a href="{{ url('/') }}" class="btn btn-primary btn-lg"><i class="bi bi-house-door me-2"></i>Kembali ke Beranda</a>
    </div>
</body>
</html>
