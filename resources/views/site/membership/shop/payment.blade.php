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
                    <form name="checkout" method="post" class="checkout" autocomplete="off" action="{{route('shop.save_payment')}}">
                        @csrf
                        <div class="row">
                            <div class="col-xl-8 col-lg-7 col-md-12 col-12">
                                Buraya Ödeme Iyzico Gelecek
                            </div>
                            <div class="col-xl-4 col-lg-5 col-md-12 col-12">
                                <div class="checkout-review-order">
                                    <div class="checkout-review-order-table">
                                        <div class="review-order-title">Ürünler</div>
                                        <div class="cart-items">
                                            @foreach($carts as $cart)
                                            <div class="cart-item">
                                                <div class="info-product">
                                                    <div class="product-thumbnail">
                                                        <img width="600" height="600" src="{{$cart->product->image()}}" alt="">
                                                    </div>
                                                    <div class="product-name">
                                                        {{$cart->product->name}}
                                                        <strong class="product-quantity">Adet : {{$cart->quantity}}</strong>
                                                    </div>
                                                </div>
                                                <div class="product-total">
                                                    <span>{{$cart->readableTotalDiscountPrice()}}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="cart-subtotal">
                                            <h2>Toplam Tutar</h2>
                                            <div class="subtotal-price">
                                                <span>{{$totalPrice}}</span>
                                            </div>
                                        </div>
                                        <div class="shipping-totals shipping">
                                            <h2>Kargolama</h2>
                                            <div data-title="Shipping">
                                                <ul class="shipping-methods custom-radio">
                                                    <li>
                                                        <input type="radio" name="shipping_method" data-index="0" value="free_shipping" class="shipping_method" checked="checked"><label>Ücretsiz Kargo</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="order-total">
                                            <h2>İndirim Tutarı</h2>
                                            <div class="total-price">
                                                <strong>
                                                    <span>{{$discountPrice}}</span>
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="order-total">
                                            <h2>Ödenecek Tutar</h2>
                                            <div class="total-price">
                                                <strong>
                                                    <span>{{$totalDiscountPrice}}</span>
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="payment" class="checkout-payment">
                                        <p class="form-row form-row-wide">
                                            <label class="checkbox">
                                                <input class="input-checkbox" type="checkbox" name="confirm" value="1">
                                                <span>
                                                    Ön Bilgilendirme Koşulları'nı ve Mesafeli Satış Sözleşmesi'ni okudum, onaylıyorum.</span>
                                            </label>
                                        </p>
                                        <div class="form-row place-order">
                                            <div class="terms-and-conditions-wrapper">
                                                <div class="privacy-policy-text"></div>
                                            </div>
                                            <button
                                                class="button alt"
                                                type="submit"
                                                value="Ödeme Yap">Ödeme Yap</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
