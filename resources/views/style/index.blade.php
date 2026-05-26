@extends('layouts.style')

@section('title', 'CV. Perdana Inti Bersaudara - Solusi Digital Radiography & Alat Kesehatan')

@section('body-class', 'index-page')

@section('content')

    @include('partials.style.home.hero')
    @include('partials.style.home.about')
    @include('partials.style.home.layanan')
    @include('partials.style.home.produk')
    @include('partials.style.home.kegiatan')
    @include('partials.style.home.contact')

@endsection
