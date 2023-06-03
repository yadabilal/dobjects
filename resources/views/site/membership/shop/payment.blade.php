@extends('layouts.app')
@section('meta')
    <title> Deek Objects | Ödeme Yap</title>
    <meta name="keywords" content="">
    <meta name="description" content="" />
@endsection
@section('content')
    @include('layouts.breadcrumb', ['title' => "Ödeme Yap"])

    <div id="content" class="site-content" role="main">
        <div class="section-padding">
            <div class="section-container p-l-r">
                <div class="shop-checkout">
                    <form name="checkout">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-12" style="height: 500px">
                                <iframe src="{{$order->payment_url}}" frameborder="0" style="overflow:hidden;height:100%;width:100%" height="100%" width="100%"></iframe>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
