@extends('layouts.app')
@section('meta')
  <title>Deek Objects | Giriş Yap</title>
  <meta name="keywords" content="">
  <meta name="description" content="" />
@endsection
@section('content')
    @include('layouts.breadcrumb', ['title' => "Giriş Yap"])
    <div id="content" class="site-content" role="main">
        <div class="section-padding">
            <div class="section-container p-l-r">
                <div class="page-login-register">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-lg-6 col-md-6 col-sm-12 sm-m-b-50">
                            <div class="box-form-login">
                                <h2>Giriş Yap</h2>
                                <div class="box-content">
                                    <div class="form-login">
                                        <form method="POST" action="{{ url('giris-yap') }}" class="login">
                                            @csrf
                                            <div class="field">
                                                <div class="username control has-validation {{ $errors->has('username') || $errors->has('phone') ? ' has-error' : '' }}">
                                                    <label>Kullanıcı adı ya da telefon numarası <span class="required">*</span></label>
                                                    <input type="text" class="input-text input required username" name="login" value="{{ old('username') ?: old('phone') }}">
                                                    @error('username')
                                                    <p class="text-error">{{$message}}</p>
                                                    @enderror
                                                    @error('phone')
                                                    <p class="text-error">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="password control">
                                                    <label for="password">Şifre <span class="required">*</span></label>
                                                    <input class="input-text input required" type="password" name="password" value="{{ old('password') ?: '' }}">
                                                </div>
                                            </div>

                                            <div class="rememberme-lost">
                                                <div class="lost-password">
                                                    <a href="{{ url('sifremi-unuttum') }}">Şifreni Mi Unuttun?</a>
                                                </div>
                                                <div class="lost-password">
                                                    Henüz bir hesabın yok mu? <a href="{{ url('kayit-ol') }}">Kaydol</a>
                                                </div>
                                            </div>
                                            <div class="button-login">
                                                <input type="submit" class="button buttonDisable" value="Giriş Yap">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
