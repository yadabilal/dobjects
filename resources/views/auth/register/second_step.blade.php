@extends('layouts.app')
@section('meta')
<title> Deek Objects | Kaydol | Telefon Onayla</title>
<meta name="keywords" content="">
<meta name="description" content="" />
@endsection
@section('content')
    @include('layouts.breadcrumb', ['title' => "Telefon Onayla"])

    <div id="content" class="site-content" role="main">
        <div class="section-padding">
            <div class="section-container p-l-r">
                <div class="page-login-register">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="box-form-login">
                                <h2 class="register">Telefon Onayla</h2>
                                <div class="box-content">
                                    <div class="form-register">
                                        <form method="POST" action="{{ url('kayit-ol/telefon-onayla') }}" class="register">
                                            @csrf
                                            <div class="title-wrap">
                                                <p><a>{{\App\Model\Setting::by_key('phone')}}</a>
                                                    numaramızdan gelen kodu girerek
                                                    telefonunu onayla. <a id="timeBack"></a></p>
                                            </div>

                                            <div class="field">
                                                <label>Onay Kodun*</label>
                                                <div class="username control has-validation {{ $errors->has('code') ? ' has-error' : '' }}">
                                                    <input type="text" name="code" class="input input-text required" placeholder="05** *** **{{\Illuminate\Support\Str::substr($user->phone, -2)}} numarasına gelen kodu gir." value="{{ old('code') ?: '' }}" maxlength="4">
                                                    @error('code')
                                                    <p class="text-error">{{$message}}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="rememberme-lost">
                                                <div class="remember-me">
                                                </div>
                                                <div class="lost-password">
                                                    Kod almadıysan, <a class="new-code isDisabled" data-action="{{route('auth.second')}}"  href="javascript:void(0)" disabled> Yeni Kod Gönder!</a> ya da <a href="{{url('kayit-ol/yeni-numara')}}">Yeni Numara Gir!</a>
                                                </div>
                                            </div>

                                            <div class="button-register">
                                                <input
                                                    data-action="{{route('auth.check')}}"
                                                    class="button buttonDisable is-solid accent-button raised  is-fullwidth btn-register"
                                                    type="button"
                                                    value="Onayla"/>
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

@push('page-scripts')
<script>
  send_at = new Date("{{$time}}");
</script>
@endpush
