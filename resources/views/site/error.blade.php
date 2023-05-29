@extends('layouts.app')
@section('meta')
    <title>Deek Objects | Sayfa Bulunamadı</title>
    <meta name="keywords" content="">
    <meta name="description" content="" />
@endsection
@section('content')
    <div id="content" class="site-content" role="main">
        <div class="section-padding">
            <div class="section-container p-l-r">
                <div class="page-404">
                    <div class="content-page-404">
                        <div class="title-error">
                            404
                        </div>
                        <div class="sub-title">
                            Oops! Aradığın sayfa bulunamadı.
                        </div>
                        <div class="sub-error">
                            Üzgünüz, istediğin sayfayı bulamadık!
                        </div>
                        <a class="button" href="{{route('home')}}">
                           Anasayfaya Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

