@extends('layouts.app')
@section('meta')
  <title>Deek Objects | Hesabım</title>
  <meta name="keywords" content="">
  <meta name="description" content="" />
@endsection
@section('content')
    @include("layouts.breadcrumb", ["title" => "Hesabım"])

    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="page-my-account">
                <div class="my-account-wrap clearfix">
                    @include("layouts.lef_menu")
                    <div class="my-account-content tab-content">
                        <div class="tab-pane fade show active">
                            <div class="my-account-dashboard">
                                <p>
                                    Merhaba <strong>{{$user->full_name()}}</strong>, ( <strong>{{$user->full_name()}}</strong> değil misin?
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Çıkış Yap</a>)
                                </p>
                                <p>
                                    Hesap kontrol panelinizden
                                    <a href="{{route('my_order')}}">son siparişlerinizi görüntüleyebilir</a>,
                                    <a href="{{route('security')}}">şifrenizi</a> ve
                                    <a href="{{route('profile.edit')}}">hesap ayrıntılarınızı</a>
                                    düzenleyebilirsiniz.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
