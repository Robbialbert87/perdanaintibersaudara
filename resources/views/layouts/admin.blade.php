<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Dashboard') - (PIB) Perdana Inti Bersaudara</title>
<link href="{{ asset('logo1.png') }}" rel="icon">
<link href="{{ asset('logo1.png') }}" rel="apple-touch-icon">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{
  --sidebar-w:250px;
  --primary:#00a76f;
  --primary-bg:rgba(0,167,111,.12);
  --primary-text:#00a76f;
}
*{box-sizing:border-box}
html,body{height:100%;background:#141a21;font-family:'Public Sans',sans-serif;-webkit-font-smoothing:antialiased}

/* ===== SIDEBAR ===== */
.sidebar{position:fixed;top:0;left:0;width:var(--sidebar-w);height:100vh;z-index:1050;background:#141a21;border-right:1px dashed rgba(255,255,255,.08);display:flex;flex-direction:column;transition:transform .3s ease}
.sidebar-brand{padding:20px 20px 16px;display:flex;align-items:center;gap:10px}
.sidebar-brand .brand-icon{width:36px;height:36px;border-radius:10px;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#fff;flex-shrink:0}
.sidebar-brand div{line-height:1.2}
.sidebar-brand .brand-name{font-size:15px;font-weight:700;color:#f4f6f8;letter-spacing:-.3px}
.sidebar-brand .brand-sub{font-size:10px;color:#919eab;letter-spacing:.5px;text-transform:uppercase}

.sidebar-nav{flex:1;overflow-y:auto;padding:12px;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.08) transparent}
.sidebar-nav::-webkit-scrollbar{width:4px}
.sidebar-nav::-webkit-scrollbar-track{background:transparent}
.sidebar-nav::-webkit-scrollbar-thumb{background:#454f5b;border-radius:10px}
.sidebar-label{font-size:11px;font-weight:700;color:#637381;text-transform:uppercase;letter-spacing:.8px;padding:16px 12px 6px}
.sidebar-link{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:8px;color:#c4cdd5;text-decoration:none;font-size:13px;font-weight:500;transition:all .2s;margin-bottom:2px}
.sidebar-link:hover{background:rgba(255,255,255,.06);color:#f4f6f8}
.sidebar-link.active{background:var(--primary-bg);color:var(--primary-text)}
.sidebar-link i{width:18px;text-align:center;font-size:15px;flex-shrink:0}
.sidebar-link .arr{margin-left:auto;font-size:12px;color:#637381;transition:transform .2s}
.sidebar-link:hover .arr{color:#c4cdd5;transform:translateX(3px)}
.sidebar-link.active .arr{color:var(--primary-text)}

.sidebar-footer{border-top:1px dashed rgba(255,255,255,.08);padding:12px}
.sidebar-footer .sidebar-link{font-size:12px;padding:8px 12px}
.sidebar-footer form{margin:0}

/* overlay */
.sidebar-overlay{position:fixed;inset:0;z-index:1049;background:rgba(0,0,0,.5);-webkit-backdrop-filter:blur(4px);backdrop-filter:blur(4px);opacity:0;visibility:hidden;transition:all .3s}
.sidebar-overlay.show{opacity:1;visibility:visible}

/* toggle mobile */
.sidebar-toggle{position:fixed;top:12px;right:12px;z-index:1060;width:36px;height:36px;border-radius:8px;background:#1c252e;border:1px solid #454f5b;color:#c4cdd5;display:none;align-items:center;justify-content:center;font-size:18px;cursor:pointer}

/* ===== MAIN ===== */
.layout{position:relative;z-index:1;margin-left:var(--sidebar-w);min-height:100vh}
.layout-inner{padding:24px}

/* topbar */
.topbar{display:flex;align-items:center;justify-content:space-between;padding:16px 28px;background:rgba(20,26,33,.8);-webkit-backdrop-filter:blur(10px);backdrop-filter:blur(10px);border-bottom:1px dashed rgba(255,255,255,.08);position:sticky;top:0;z-index:1020;gap:12px}
.topbar-left{display:flex;align-items:center;gap:12px}
.topbar-title{font-size:16px;font-weight:600;color:#f4f6f8;letter-spacing:-.3px}
.topbar-title small{font-weight:400;font-size:11px;color:#637381;letter-spacing:.5px;text-transform:uppercase;margin-left:8px}

.content-area{padding:28px}

/* ===== CARDS ===== */
.card{background:#1c252e!important;border:1px solid #454f5b!important;border-radius:12px!important;box-shadow:0 4px 8px rgba(0,0,0,.2)!important}
.card-header{background:transparent!important;border-bottom:1px dashed #454f5b!important;padding:16px 20px!important}
.card-header h5,.card-header h6{margin:0;font-weight:600;font-size:14px;color:#f4f6f8!important;letter-spacing:-.2px}
.card-body{padding:20px!important}
.card-footer{background:rgba(255,255,255,.02)!important;border-top:1px dashed #454f5b!important;padding:12px 20px!important}

/* ===== BUTTONS ===== */
.btn{border-radius:8px;font-size:13px;font-weight:600;padding:8px 16px;transition:all .2s;display:inline-flex;align-items:center;gap:6px}
.btn-sm{border-radius:6px;padding:5px 12px;font-size:12px}
.btn-primary{background:var(--primary)!important;border-color:var(--primary)!important;color:#fff!important}
.btn-primary:hover{background:#009c64!important;border-color:#009c64!important;box-shadow:0 4px 12px rgba(0,167,111,.3)!important}
.btn-secondary{background:#454f5b!important;border-color:#454f5b!important;color:#f4f6f8!important}
.btn-secondary:hover{background:#555f6b!important}
.btn-success{background:rgba(34,197,94,.12)!important;border-color:rgba(34,197,94,.3)!important;color:#22c55e!important}
.btn-success:hover{background:rgba(34,197,94,.22)!important}
.btn-danger{background:rgba(239,68,68,.12)!important;border-color:rgba(239,68,68,.3)!important;color:#ef4444!important}
.btn-danger:hover{background:rgba(239,68,68,.22)!important}
.btn-warning{background:rgba(255,171,0,.12)!important;border-color:rgba(255,171,0,.3)!important;color:#ffab00!important}
.btn-warning:hover{background:rgba(255,171,0,.22)!important}
.btn-info{background:rgba(0,184,217,.12)!important;border-color:rgba(0,184,217,.3)!important;color:#00b8d9!important}
.btn-info:hover{background:rgba(0,184,217,.22)!important}
.btn-outline-secondary{background:transparent!important;border:1px solid #454f5b!important;color:#c4cdd5!important}
.btn-outline-secondary:hover{background:#454f5b!important;color:#f4f6f8!important}
.btn-outline-light{background:transparent!important;border:1px solid #454f5b!important;color:#c4cdd5!important}
.btn-outline-light:hover{background:rgba(255,255,255,.06)!important;color:#f4f6f8!important}
.btn-outline-success{background:transparent!important;border:1px solid rgba(34,197,94,.3)!important;color:#22c55e!important}
.btn-outline-success:hover{background:rgba(34,197,94,.12)!important}

/* ===== FORMS ===== */
.form-label{font-size:12px;font-weight:500;color:#c4cdd5;margin-bottom:4px}
.form-control,.form-select{background:#141a21!important;border:1px solid #454f5b!important;border-radius:8px!important;color:#f4f6f8!important;font-size:13px;padding:8px 12px;transition:all .2s}
.form-control:focus,.form-select:focus{background:#141a21!important;border-color:var(--primary)!important;box-shadow:0 0 0 3px rgba(0,167,111,.15)!important}
.form-control::placeholder{color:#637381}
.form-select option{background:#1c252e;color:#f4f6f8}
.input-group-text{background:#141a21!important;border:1px solid #454f5b!important;color:#c4cdd5!important;border-radius:8px!important;font-size:13px}
.input-group>.form-control{border-radius:8px!important}
.input-group>:not(:first-child){border-top-left-radius:0!important;border-bottom-left-radius:0!important}
.input-group>:not(:last-child){border-top-right-radius:0!important;border-bottom-right-radius:0!important}
.form-text{color:#637381!important;font-size:11px}
.invalid-feedback{color:#ef4444!important;font-size:11px}
.form-control.is-invalid,.form-select.is-invalid{border-color:#ef4444!important;box-shadow:0 0 0 3px rgba(239,68,68,.15)!important}
.form-check-input{background:#141a21!important;border:1px solid #454f5b!important;transition:all .2s}
.form-check-input:checked{background:var(--primary)!important;border-color:var(--primary)!important}

/* ===== TABLES ===== */
.table{color:#c4cdd5!important;font-size:13px;margin-bottom:0}
.table>:not(caption)>*>*{background:transparent!important;border-bottom:1px dashed rgba(255,255,255,.06)!important;padding:10px 14px;vertical-align:middle}
.table thead th{font-weight:600;font-size:11px;color:#637381!important;text-transform:uppercase;letter-spacing:.5px;border-bottom:1px dashed rgba(255,255,255,.1)!important}
.table-hover tbody tr:hover>*{background:rgba(255,255,255,.03)!important}
.table-bordered>:not(caption)>*{border-color:rgba(255,255,255,.06)!important}
.table-light{--bs-table-bg:transparent!important}
.table-light th{color:#637381!important}
.table-borderless>:not(caption)>*>*{border-bottom:none!important}
.table-responsive{border-radius:8px}

/* ===== ALERTS ===== */
.alert{border-radius:10px;font-size:13px;padding:12px 16px;position:relative;border:1px solid transparent}
.alert-success{background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.2);color:#22c55e}
.alert-danger{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.2);color:#ef4444}
.btn-close{filter:invert(1);opacity:.3;background-size:.6em;position:absolute;top:50%;right:14px;transform:translateY(-50%)}
.btn-close:hover{opacity:.6}

/* ===== BADGES ===== */
.badge{font-weight:500;font-size:11px;padding:3px 10px;border-radius:100px;border:1px solid transparent}
.badge.bg-secondary{background:#454f5b!important;color:#c4cdd5!important}
.badge.bg-info{background:rgba(0,184,217,.12)!important;color:#00b8d9!important;border-color:rgba(0,184,217,.2)!important}
.badge.bg-success{background:rgba(34,197,94,.12)!important;color:#22c55e!important;border-color:rgba(34,197,94,.2)!important}
.badge.bg-danger{background:rgba(239,68,68,.12)!important;color:#ef4444!important;border-color:rgba(239,68,68,.2)!important}
.badge.bg-warning{background:rgba(255,171,0,.12)!important;color:#ffab00!important;border-color:rgba(255,171,0,.2)!important}
.badge.bg-primary{background:var(--primary-bg)!important;color:var(--primary-text)!important;border-color:rgba(0,167,111,.2)!important}

/* ===== PAGINATION ===== */
.pagination{--bs-pagination-bg:transparent;--bs-pagination-border-color:#454f5b;--bs-pagination-color:#c4cdd5;--bs-pagination-hover-bg:#454f5b;--bs-pagination-hover-color:#f4f6f8;--bs-pagination-hover-border-color:#454f5b;--bs-pagination-active-bg:var(--primary);--bs-pagination-active-border-color:var(--primary);--bs-pagination-active-color:#fff;--bs-pagination-disabled-bg:transparent;--bs-pagination-disabled-color:#637381;gap:4px;font-size:13px}
.pagination .page-link{border-radius:8px!important;padding:5px 11px}

/* ===== TEXT ===== */
.text-primary{color:var(--primary-text)!important}
.text-danger{color:#ef4444!important}
.text-success{color:#22c55e!important}
.text-muted{color:#637381!important}
.border-bottom{border-bottom-color:rgba(255,255,255,.08)!important}
a{color:var(--primary-text);text-decoration:none}
a:hover{color:#00c884}
hr{border-color:rgba(255,255,255,.08);margin:16px 0}
.fw-bold{font-weight:600!important}
.fw-semibold{font-weight:500!important}
.bg-light{background:#141a21!important;border-radius:8px;border:1px solid #454f5b!important}
.card-img-top{border-radius:8px 8px 0 0;width:100%;object-fit:cover}
ol,ul{padding-left:1.2rem}
li{color:#c4cdd5;font-size:13px}
kbd{background:#454f5b;border-radius:4px;padding:2px 6px;font-size:11px;color:#c4cdd5}

/* ===== RESPONSIVE ===== */
@media(max-width:991px){
  .sidebar{transform:translateX(-100%)}
  .sidebar.show{transform:translateX(0)}
  .sidebar-toggle{display:flex}
  .layout{margin-left:0}
  .content-area{padding:16px}
  .topbar{padding:12px 16px}
  .topbar-title small{display:none}
}
@media(max-width:576px){
  .content-area{padding:12px}
  .topbar{padding:10px 12px}
  .topbar-title{font-size:14px}
  .btn{font-size:12px;padding:6px 12px}
  .form-control,.form-select{font-size:12px;padding:7px 10px}
  .table thead th,.table tbody td{font-size:11px;padding:6px 8px}
  .card-body{padding:14px!important}
  .card-header{padding:12px 14px!important}
}
</style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
  <i class="bi bi-list"></i>
</button>

<nav class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-icon">P</div>
    <div>
      <div class="brand-name">PIB Admin</div>
      <div class="brand-sub">Perdana Inti Bersaudara</div>
    </div>
  </div>

  <div class="sidebar-nav">
    <div class="sidebar-label">Menu</div>
    <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
      <i class="bi bi-speedometer2"></i><span>Dashboard</span><span class="arr">&rarr;</span>
    </a>
    <a class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
      <i class="bi bi-people"></i><span>Master Customer</span><span class="arr">&rarr;</span>
    </a>
    <a class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
      <i class="bi bi-box-seam"></i><span>Master Produk</span><span class="arr">&rarr;</span>
    </a>
    <a class="sidebar-link {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">
      <i class="bi bi-briefcase"></i><span>Master Layanan</span><span class="arr">&rarr;</span>
    </a>
    <a class="sidebar-link {{ request()->routeIs('quotations.*') ? 'active' : '' }}" href="{{ route('quotations.index') }}">
      <i class="bi bi-file-earmark-text"></i><span>Penawaran</span><span class="arr">&rarr;</span>
    </a>
    <a class="sidebar-link {{ request()->routeIs('activities.*') ? 'active' : '' }}" href="{{ route('activities.index') }}">
      <i class="bi bi-card-image"></i><span>Kegiatan</span><span class="arr">&rarr;</span>
    </a>
  </div>

  <div class="sidebar-footer">
    <a class="sidebar-link" href="{{ route('home') }}" target="_blank">
      <i class="bi bi-globe2"></i><span>Lihat Website</span><span class="arr">&rarr;</span>
    </a>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="sidebar-link" style="width:100%;border:none;background:none;cursor:pointer;text-align:left">
        <i class="bi bi-box-arrow-right"></i><span>Logout</span><span class="arr">&rarr;</span>
      </button>
    </form>
  </div>
</nav>

<div class="layout">
  <div class="topbar">
    <div class="topbar-left">
      <div class="topbar-title">
        @yield('title', 'Dashboard') <small>PIB / Admin</small>
      </div>
    </div>
    <div>
      <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-secondary" style="font-size:12px;padding:5px 12px">
        <i class="bi bi-globe2"></i> Website
      </a>
    </div>
  </div>

  <div class="content-area">
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
  var s=document.getElementById('sidebar'),o=document.getElementById('sidebarOverlay'),t=document.getElementById('sidebarToggle');
  function show(){s.classList.add('show');o.classList.add('show')}
  function hide(){s.classList.remove('show');o.classList.remove('show')}
  if(t)t.addEventListener('click',show);
  if(o)o.addEventListener('click',hide);
})();
</script>
</body>
</html>
