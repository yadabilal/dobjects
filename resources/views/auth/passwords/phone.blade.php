@extends('layouts.app')
@section('content')
    @include('layouts.breadcrumb', ['title' => "Şifremi Unuttum"])
    <div id="content" class="site-content" role="main">
        <div class="section-padding">
            <div class="section-container p-l-r">
                <div class="page-login-register">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="box-form-login">
                                <h2 class="register">Şifremi Unuttum</h2>
                                <div class="box-content">
                                    <div class="form-register">
                                        <form method="POST" action="{{ url('sifremi-unuttum') }}" class="register">
                                            @csrf
                                            <div class="title-wrap">
                                                <p>Kullanıcı adını veya telefon numaranı gir ve
                                                    hesabına yeniden girebilmen için
                                                    <a>{{\App\Model\Setting::by_key('phone')}}</a>
                                                    numaramızdan sana yeni bir şifre gönderelim.
                                                </p>
                                            </div>
                                            <div class="field">
                                                <label>Kullanıcı adın ya da telefon numaran <span class="required">*</span></label>
                                                <div class="username control has-validation {{ $errors->has('username') || $errors->has('phone') ? ' has-error' : '' }}">
                                                    <input type="text" name="login" class="input input-text required username" placeholder="Kullanıcı adın ya da telefon numaran" value="{{ old('username') ?: old('phone') }}">
                                                    @error('username')
                                                    <p class="text-error">{{$message}}</p>
                                                    @enderror
                                                    @error('phone')
                                                    <p class="text-error">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="button-register">
                                                <input
                                                    class="button buttonDisable is-solid accent-button raised  is-fullwidth" type="submit" value="Şifremi Sıfırla"/>
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

