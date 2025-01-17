@extends('layouts.app')
@section('meta')
    <title>Deek Objects | Ödeme Sonuç</title>
    <meta name="keywords" content="">
    <meta name="description" content="" />
@endsection
@section('facebookAction')
    fbq('track', 'Purchase', {value: {{$order->total_discount_price}}, currency: "TRY"});
@endsection
@section('content')
    <div id="content" class="site-content" role="main">
        <div class="section-padding">
            <div class="section-container p-l-r">
                <div class="page-404">
                    <div class="content-page-404">
                        @if($order->status == \App\Model\Order::STATUS_NEW)
                            <script>
                                gtag('event', 'purchase', {
                                    'order_id': '{{$order->number}}',
                                    'total_price': '{{$order->total_discount_price}}',
                                    'currency': 'TRY'
                                });
                            </script>
                            <div class="sub-title">
                                Tebrikler! Siparişiniz başarılı bir şekilde gerçekleşti.
                            </div>
                            <div class="sub-error">
                                Siparişlerinizi görüntülemek ya da takip etmek için lütfen aşağıdaki linki tıklayın.
                            </div>
                            @if(auth()->id())
                                <a class="button" href="{{route('my_order')}}">
                                    Siparişlerime Git
                                </a>
                            @else
                                <a class="button" href="{{route('guest.shop.order', ['uuid' => $order->uuid])}}">
                                    Siparişe Git
                                </a>
                            @endif
                        @else
                            <div class="sub-title">
                                {{$order->getPaymentMessage()}}
                            </div>

                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
