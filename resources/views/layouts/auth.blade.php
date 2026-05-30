<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Login - (PIB) Perdana Inti Bersaudara</title>
<link href="{{ asset('logo1.png') }}" rel="icon">
<link href="{{ asset('logo1.png') }}" rel="apple-touch-icon">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{--primary:#00a76f;--primary-bg:rgba(0,167,111,.12)}
*{box-sizing:border-box}
html,body{height:100%;background:#141a21;font-family:'Public Sans',sans-serif;-webkit-font-smoothing:antialiased}
body{display:flex;align-items:center;justify-content:center;padding:20px}

.brand-top{text-align:center;margin-bottom:32px}
.brand-top .brand-icon{width:48px;height:48px;border-radius:12px;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700;color:#fff;margin:0 auto 12px}
.brand-top h1{font-weight:700;font-size:22px;color:#f4f6f8;letter-spacing:-.4px;margin:0}
.brand-top p{font-size:14px;color:#637381;margin:4px 0 0}

.card{background:#1c252e!important;border:1px solid #454f5b!important;border-radius:12px!important;box-shadow:0 4px 8px rgba(0,0,0,.2)!important;width:100%;max-width:420px}
.card-body{padding:28px!important}

.form-label{font-size:13px;font-weight:500;color:#c4cdd5;margin-bottom:5px}
.form-control{background:#141a21!important;border:1px solid #454f5b!important;border-radius:8px!important;color:#f4f6f8!important;font-size:14px;padding:10px 14px;transition:all .2s}
.form-control:focus{background:#141a21!important;border-color:var(--primary)!important;box-shadow:0 0 0 3px rgba(0,167,111,.15)!important}
.form-control::placeholder{color:#637381}
.form-check-input{background:#141a21!important;border:1px solid #454f5b!important;margin-top:.3em}
.form-check-input:checked{background:var(--primary)!important;border-color:var(--primary)!important}
.form-check-label{font-size:13px;color:#919eab}

.btn{border-radius:8px;font-size:14px;font-weight:600;padding:10px 20px;transition:all .2s}
.btn-primary{background:var(--primary)!important;border-color:var(--primary)!important;color:#fff!important}
.btn-primary:hover{background:#009c64!important;box-shadow:0 4px 12px rgba(0,167,111,.3)!important}

.alert{border-radius:8px;font-size:13px;padding:12px 16px;border:1px solid transparent;margin-bottom:16px}
.alert-success{background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.2);color:#22c55e}
.alert-danger{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.2);color:#ef4444}
.alert-danger ul{margin:0;padding-left:16px}
.alert-danger li{color:#ef4444;font-size:13px}

a{color:var(--primary);text-decoration:none;font-size:13px;font-weight:500}
a:hover{color:#00c884}
.text-muted{color:#637381!important}
</style>
</head>
<body>
<div class="container" style="display:flex;justify-content:center">
  @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
