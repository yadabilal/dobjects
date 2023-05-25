@extends('layouts.app')

@section('content')
    <h3 class="account-title">{{ __('Şifremi Sıfırla') }}</h3>
    <div class="account-box">
        <div class="account-wrapper">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="account-logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('preadmin/img/logo/logo.png') }}" alt="Bir Kitap bul, İkinci El Kitap" title="Bir Kitap Bul - İkinci El Ücretsiz Kitap Okuma Platformu">
                </a>
            </div>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <div class="form-focus">
                        <label class="focus-label">{{ __('E-Mail Adresi') }}</label>
                        <input id="email" type="email" class="form-control floating @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    </div>
                    @error('email')
                    <small class="form-text text-muted">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-primary btn-block account-btn" type="submit">{{ __('Sıfırlama Linki Gönder') }}</button>
                </div>
                <div class="text-center">
                    <a href="{{ route('login') }}">{{ __('Giriş Yap') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
