@extends('layouts.app')
@section('meta')
    <title> Deek Objects | Kaydol</title>
    <meta name="keywords" content="">
    <meta name="description" content="" />
@endsection


@section('content')
    @include('layouts.breadcrumb', ['title' => "Kaydol"])
    <div id="content" class="site-content" role="main">
        <div class="section-padding">
            <div class="section-container p-l-r">
                <div class="page-login-register">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="box-form-login">
                                <h2 class="register">Kaydol</h2>
                                <div class="box-content">
                                    <div class="form-register">
                                        <form method="POST" action="{{ url('kayit-ol') }}" class="register">
                                            @csrf
                                            <div class="field">
                                                <div class="username control has-validation {{ $errors->has('name') ? ' has-error' : '' }}">
                                                    <label>Adın <span class="required">*</span></label>
                                                    <input type="text" name="name" class=" input-text input required" placeholder="Adın*" value="{{ old('name') ?: '' }}" maxlength="25">
                                                    @error('name')
                                                    <p class="text-error">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="field">
                                                <label>Soyadın*</label>
                                                <div class="email control has-validation {{ $errors->has('surname') ? ' has-error' : '' }}">
                                                    <input type="text" name="surname" class="input-text input required" placeholder="Soyadın*" value="{{ old('surname') ?: '' }}" maxlength="30">
                                                    @error('surname')
                                                    <p class="text-error">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="field wrapper-info">
                                                <label>Telefon Numaran*</label>
                                                <div class="email control has-validation {{ $errors->has('phone') ? ' has-error' : '' }}">
                                                    <input type="text" name="phone" class="input-text input required phone" placeholder="Onay Kodu Gelecektir." value="{{ old('phone') ?: '' }}" maxlength="14">
                                                    @error('phone')
                                                    <p class="text-error">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="field">
                                                <label>Şifren*</label>
                                                <div class="password control">
                                                    <input type="password" class="input-text input required" placeholder="Şifren*" name="password" value="{{ old('password') ?: '' }}">
                                                    @error('password')
                                                    <p class="text-error">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="warning-wrapper">
                                                <p>Kaydolarak, <a href="{{route('contract.sub', ['url' => 'kullanici-sozlesmesi'])}}" target="_blank">Koşullar'ı</a> ve
                                                    <a href="{{route('contract.sub', ['url' => 'kisisel-verilerin-korunmasi'])}}" target="_blank">Çerez İlkeleri'ni</a> kabul etmiş olursun.</p>
                                            </div>

                                            <div class="rememberme-lost">
                                                <div class="lost-password">
                                                    Bir hesabın var mı? <a href="{{ url('giris-yap') }}">Giriş Yap</a>
                                                </div>
                                            </div>

                                            <div class="button-register">
                                                <input
                                                    name="register"
                                                    data-action="{{route('auth.check')}}"
                                                    class="button buttonDisable btn-register"
                                                    type="button" value="Kaydol" />
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
