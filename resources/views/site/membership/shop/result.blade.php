@extends('layouts.app')
@section('meta')
    <title>Deek Objects | Sonuç</title>
    <meta name="keywords" content="">
    <meta name="description" content="" />
@endsection
@section('content')
    <div id="content" class="site-content" role="main">
        <div class="section-padding">
            <div class="section-container p-l-r">
                <div class="page-404">
                    <div class="content-page-404">

                        <div class="sub-title">
                            Tebrikler! Siparişiniz başarılı bir şekilde gerçekleşti.
                        </div>
                        <div class="sub-error">
                            Siparişlerinizi görüntülemek ya da takip etmek için lütfen aşağıdaki linki tıklayın.
                        </div>
                        <a class="button" href="{{route('my_order')}}">
                            Siparişlerime Git
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
