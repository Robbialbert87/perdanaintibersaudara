@extends('layouts.style')

@section('title', 'Dokumen Tidak Ditemukan - (PIB) Perdana Inti Bersaudara')

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
        max-width: 500px;
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
        padding: 50px 30px;
        text-align: center;
    }
    .not-found-icon {
        font-size: 4rem;
        color: #dc3545;
        margin-bottom: 20px;
    }
    .not-found-text {
        color: #6c757d;
        font-size: 1.1rem;
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
            <div class="verify-body">
                <div class="not-found-icon">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <h4 style="color: #dc3545; font-weight: 700;">Dokumen Tidak Ditemukan</h4>
                <p class="not-found-text">Dokumen yang Anda cari tidak terdaftar dalam sistem kami. Silakan hubungi pihak terkait untuk informasi lebih lanjut.</p>
            </div>
        </div>
    </div>
</section>
@endsection
