@php $sbUser = auth()->user(); @endphp

<aside class="app-sidebar" id="appSidebar">
    <div class="sb-brand">
        <div class="sb-brand-icon"><img src="{{ asset('style/assets/img/pib-logo.png') }}" alt="PIB"></div>
        <div class="hide-when-collapsed">
            <div class="sb-brand-name">PIB</div>
            <div class="sb-brand-sub">Perdana Inti Bersaudara</div>
        </div>
    </div>

    <nav class="sb-nav">
        <div class="sb-group">
            <div class="sb-label hide-when-collapsed">Umum</div>
            <a class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i data-lucide="layout-dashboard"></i><span class="hide-when-collapsed">Dashboard</span>
            </a>
        </div>

        <div class="sb-group">
            <div class="sb-label hide-when-collapsed">Master</div>
            <a class="sb-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                <i data-lucide="users"></i><span class="hide-when-collapsed">Customer</span>
            </a>
            <a class="sb-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                <i data-lucide="package"></i><span class="hide-when-collapsed">Produk</span>
            </a>
            <a class="sb-link {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">
                <i data-lucide="briefcase"></i><span class="hide-when-collapsed">Layanan</span>
            </a>
            @if ($sbUser && $sbUser->isAdmin())
                <a class="sb-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i data-lucide="user-cog"></i><span class="hide-when-collapsed">User Management</span>
                </a>
            @endif
        </div>

        <div class="sb-group">
            <div class="sb-label hide-when-collapsed">Transaksi</div>
            <a class="sb-link {{ request()->routeIs('quotations.*') ? 'active' : '' }}" href="{{ route('quotations.index') }}">
                <i data-lucide="file-text"></i><span class="hide-when-collapsed">Penawaran</span>
            </a>
            <a class="sb-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}" href="{{ route('purchase-orders.index') }}">
                <i data-lucide="shopping-cart"></i><span class="hide-when-collapsed">Purchase Order</span>
            </a>
            <a class="sb-link {{ request()->routeIs('warranty-cards.*') ? 'active' : '' }}" href="{{ route('warranty-cards.index') }}">
                <i data-lucide="shield-check"></i><span class="hide-when-collapsed">Kartu Garansi</span>
            </a>
            <a class="sb-link {{ request()->routeIs('berita-acaras.*') ? 'active' : '' }}" href="{{ route('berita-acaras.index') }}">
                <i data-lucide="clipboard-check"></i><span class="hide-when-collapsed">Berita Acara</span>
            </a>
            <a class="sb-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
                <i data-lucide="receipt"></i><span class="hide-when-collapsed">Invoice</span>
            </a>
            <a class="sb-link {{ request()->routeIs('kwitansis.*') ? 'active' : '' }}" href="{{ route('kwitansis.index') }}">
                <i data-lucide="banknote"></i><span class="hide-when-collapsed">Kwitansi</span>
            </a>
        </div>

        <div class="sb-group">
            <div class="sb-label hide-when-collapsed">Lainnya</div>
            <a class="sb-link {{ request()->routeIs('activities.*') ? 'active' : '' }}" href="{{ route('activities.index') }}">
                <i data-lucide="calendar-days"></i><span class="hide-when-collapsed">Kegiatan</span>
            </a>
        </div>
    </nav>

    <div class="sb-footer">
        <div class="sb-group">
            <a class="sb-link" href="{{ route('home') }}" target="_blank">
                <i data-lucide="globe"></i><span class="hide-when-collapsed">Lihat Website</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sb-link sb-link-btn" title="Logout">
                    <i data-lucide="log-out"></i><span class="hide-when-collapsed">Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<div class="app-overlay" id="appOverlay"></div>