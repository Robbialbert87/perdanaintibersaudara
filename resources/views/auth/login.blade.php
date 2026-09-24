@extends('layouts.auth')

@section('title', 'Masuk Admin')

@section('content')
<div class="card">
    <div class="card-body" style="padding:32px !important">
        <div class="brand-top">
            <div class="brand-icon"><img src="{{ asset('style/assets/img/pib-logo.png') }}" alt="PIB"></div>
            <h1>Welcome Back</h1>
            <p>Masuk ke panel admin PIB</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
            </div>
            <div class="mb-4 d-flex align-items-center justify-content-between">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100" style="height:42px">
                <i data-lucide="log-in" style="width:16px;height:16px"></i> Sign In
            </button>
        </form>
    </div>
</div>
@endsection