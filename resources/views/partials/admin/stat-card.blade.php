{{-- Usage:
    @include('partials.admin.stat-card', [
        'label' => 'Invoice',
        'value' => $totalInvoice,
        'sub'   => 'Lunas: X · Belum: Y',       // optional
        'icon'  => 'receipt',                    // lucide icon name
        'tone'  => 'primary',                    // primary|teal|sky|amber|purple|rose
        'footerText' => 'Lihat Detail',          // optional
        'link'  => route('invoices.index'),      // optional
    ])
--}}
<div class="col-sm-6 col-xl-3">
    <div class="card h-100 stat-card">
        <div class="card-body d-flex flex-column gap-3">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <p class="stat-label mb-0">{{ $label }}</p>
                <div class="stat-icon icon-{{ $tone ?? 'primary' }}">
                    <i data-lucide="{{ $icon ?? 'chart-bar' }}"></i>
                </div>
            </div>
            <h3 class="stat-value mb-0">{{ $value }}</h3>
            @if (!empty($sub))
                <p class="text-muted mb-0" style="font-size:12px">{{ $sub }}</p>
            @endif
        </div>
        @if (!empty($link))
            <a href="{{ $link }}" class="card-footer stat-footer d-flex justify-content-between align-items-center"
                style="border-top:1px solid var(--border);padding:12px 20px">
                <span>{{ $footerText ?? 'Lihat Detail' }}</span>
                <i data-lucide="arrow-right"></i>
            </a>
        @endif
    </div>
</div>