@extends('layouts.style')

@section('title', 'Verifikasi Dokumen - (PIB) Perdana Inti Bersaudara')

@section('body-class', 'verify-page')

@push('styles')
<meta name="robots" content="noindex, nofollow">
<style>
    .verify-section {
        padding: 60px 0;
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
    }
    .verify-card {
        max-width: 600px;
        margin: 0 auto;
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .verify-header {
        background: linear-gradient(135deg, #13447f 0%, #065cc2 100%);
        padding: 30px;
        text-align: center;
        color: #fff;
    }
    .verify-header h4 {
        font-family: 'Quicksand', sans-serif;
        font-weight: 700;
        margin-bottom: 4px;
        font-size: 1.1rem;
        letter-spacing: 1px;
    }
    .verify-header h2 {
        font-family: 'Quicksand', sans-serif;
        font-weight: 800;
        margin-bottom: 0;
        font-size: 1.6rem;
    }
    .verify-body {
        padding: 30px;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #e8f5e9;
        color: #2e7d32;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 25px;
        border: 2px solid #a5d6a7;
    }
    .status-badge i {
        font-size: 1.3rem;
    }
    .verify-detail-table {
        width: 100%;
        border-collapse: collapse;
    }
    .verify-detail-table td {
        padding: 8px 0;
        vertical-align: top;
    }
    .verify-detail-table .label {
        color: #6c757d;
        font-weight: 600;
        width: 140px;
        padding-right: 12px;
    }
    .verify-detail-table .value {
        color: #1a2332;
        font-weight: 500;
    }
    .verify-detail-table .divider td {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 8px;
    }
    .verify-footer-text {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
        text-align: center;
        color: #6c757d;
        font-size: 0.85rem;
    }
    .verify-footer-text strong {
        color: #1a2332;
    }
</style>
@endpush

@section('content')
<section class="verify-section">
    <div class="container">
        <div class="verify-card card">
            <div class="verify-header">
                <h4>CV. PERDANA INTI BERSAUDARA</h4>
                <h2>VERIFIKASI DOKUMEN</h2>
            </div>
            <div class="verify-body text-center">
                <div class="status-badge">
                    <i class="bi bi-check-circle-fill"></i>
                    Dokumen Terdaftar
                </div>

                <table class="verify-detail-table text-start">
                    <tr class="divider">
                        <td class="label">No Invoice</td>
                        <td class="value">: {{ $invoice->nomor_invoice }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal Invoice</td>
                        <td class="value">: {{ \Carbon\Carbon::parse($invoice->tanggal)->locale('id')->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Customer</td>
                        <td class="value">: {{ $invoice->customer->nama_instansi }}</td>
                    </tr>
                    <tr>
                        <td class="label">Total Invoice</td>
                        <td class="value">: Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Status</td>
                        <td class="value">: {{ ucfirst($invoice->status) }}</td>
                    </tr>
                    <tr class="divider">
                        <td class="label">Dibuat Oleh</td>
                        <td class="value">: Erwin Darmawan</td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal Generate</td>
                        <td class="value">: {{ $tanggalGenerate }}</td>
                    </tr>
                </table>

                <div class="verify-footer-text">
                    Dokumen ini terverifikasi dan tercatat di sistem <strong>CV. Perdana Inti Bersaudara</strong>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
