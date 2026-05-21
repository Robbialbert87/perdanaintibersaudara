@extends('layouts.clinic')

@section('title', 'Clinic - Home')

@section('content')

    @include('partials.clinic.home.hero')
    @include('partials.clinic.home.about')
    @include('partials.clinic.home.layanan')
    @include('partials.clinic.home.produk')

    @include('partials.clinic.home.edukasi')
    @include('partials.clinic.home.contact')

@endsection
