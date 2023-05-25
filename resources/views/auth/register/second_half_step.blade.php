@extends('layouts.app')
@section('meta')
    <title> Deek Objects | Kaydol | Yeni Bir Numara</title>
    <meta name="keywords" content="">
    <meta name="description" content="" />
@endsection

@section('content')

    @include('layouts.breadcrumb', ['title' => "Yeni Numara Belirle"])

    <div id="content" class="site-content" role="main">
        <div class="section-padding">
            <div class="section-container p-l-r">
                <div class="page-login-register">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="box-form-login">
                                <h2 class="register">Yeni Numara Belirle</h2>
                                <div class="box-content">
                                    <div class="form-register">
                                        <form method="POST" action="{{ url('kayit-ol/yeni-numara') }}" class="register">
                                            @csrf
                                            <div class="field">
                                                <label>Telefon Numaran*</label>
                                                <div class="username control has-validation {{ $errors->has('phone') ? ' has-error' : '' }}">
                                                    <input type="hidden" name="new_phone" value="1">
                                                    <input type="text" name="phone" class="input input-text required phone" placeholder="Yeni telefon numaranı gir." value="{{ old('phone') ?: (@\Illuminate\Support\Facades\Auth::user()->phone ? : '') }}" maxlength="14">
                                                    @error('phone')
                                                    <p class="text-error">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="rememberme-lost">
                                                <div class="lost-password">
                                                    <a href="{{url()->previous()}}">Geri Dön</a>
                                                </div>
                                            </div>
                                            <div class="button-register">
                                                <input
                                                    data-action="{{route('auth.check')}}"
                                                    class="button buttonDisable is-solid accent-button raised  is-fullwidth btn-register"
                                                    type="button"
                                                    value="Onay Kodu Gönder"/>
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
