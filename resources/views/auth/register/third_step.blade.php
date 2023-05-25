@extends('layouts.app')
@section('meta')
    <title> Deek Objects | Kullanıcı Adı Belirle</title>
    <meta name="keywords" content="">
    <meta name="description" content="" />
@endsection

@section('content')
    @include('layouts.breadcrumb', ['title' => "Kullanıcı Adı Belirle"])
    <div id="content" class="site-content" role="main">
        <div class="section-padding">
            <div class="section-container p-l-r">
                <div class="page-login-register">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="box-form-login">
                                <h2 class="register">Kullanıcı Adı Belirle</h2>
                                <div class="box-content">
                                    <div class="form-register">
                                        <form method="POST" action="{{ url('kayit-ol/kullanici-adi-belirle') }}" class="register">
                                            @csrf
                                            <div class="title-wrap">
                                                <p>Süper gidiyorsun. Şimdi son adımdasın. (Ürün yorumlarında kullanıcı adın görünecektir!)</p>
                                            </div>

                                            <div class="field">
                                                <label>Kullanıcı Adın*</label>
                                                <div class="username control has-validation {{ $errors->has('username') ? ' has-error' : '' }}">
                                                    <input type="text" name="username" class="input input-text required" placeholder="Kısaca sana nasıl hitap edelim?" value="{{ old('username') ?: '' }}">
                                                    @error('username')
                                                    <p class="text-error">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="button-register">
                                                <input
                                                    data-action="{{route('auth.check')}}"
                                                    class="button buttonDisable is-solid accent-button raised  is-fullwidth btn-register"
                                                    type="button"
                                                    value="Tamamla"/>
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
