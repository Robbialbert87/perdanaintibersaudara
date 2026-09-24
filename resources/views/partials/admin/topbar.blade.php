@php
    $tbUser = auth()->user();
    $tbInitials = $tbUser ? collect(explode(' ', trim($tbUser->name)))->take(2)
        ->map(fn($w) => strtoupper(mb_substr($w, 0, 1)))->join('') : 'AD';
@endphp

<header class="app-topbar">
    <div class="tb-left">
        <button type="button" class="icon-btn" id="sidebarToggle" aria-label="Toggle sidebar" title="Toggle sidebar">
            <i data-lucide="panel-left"></i>
        </button>
        <div class="tb-title">
            @yield('title', 'Dashboard') <small>PIB / Admin</small>
        </div>
    </div>

    <div class="tb-right">
        <div class="tb-search">
            <i data-lucide="search" class="tb-search-icon"></i>
            <input type="text" id="navFilter" class="form-control" placeholder="Cari menu..." autocomplete="off">
        </div>

        <button type="button" class="icon-btn" id="themeToggle" aria-label="Toggle tema" title="Toggle tema">
            <i data-lucide="moon" class="tb-icon-dark"></i>
            <i data-lucide="sun" class="tb-icon-light"></i>
        </button>

        <button type="button" class="icon-btn" onclick="shareAdmin()" aria-label="Bagikan" title="Bagikan">
            <i data-lucide="share-2"></i>
        </button>

        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-secondary btn-sm d-none d-sm-inline-flex">
            <i data-lucide="globe"></i> Website
        </a>

        @if ($tbUser)
            <div class="dropdown">
                <button type="button" class="avatar-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="avatar">{{ $tbInitials }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="min-width:220px">
                    <li>
                        <div class="px-2 py-1">
                            <p class="fw-semibold mb-0 text-truncate" style="font-size:14px">{{ $tbUser->name }}</p>
                            <small class="text-muted text-truncate d-block">{{ $tbUser->email }}</small>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                        <i data-lucide="globe" style="width:16px;height:16px" class="me-2"></i>Lihat Website</a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item w-100">
                                <i data-lucide="log-out" style="width:16px;height:16px" class="me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</header>