@extends('layouts.app')
@section('meta')
  <title> Deek Objects | Sepetim</title>
  <meta name="keywords" content="">
  <meta name="description" content="" />
@endsection
@section('content')
    @include('layouts.breadcrumb', ['title' => "Sepet"])

    <div id="content" class="site-content" role="main">
        <div class="section-padding">
            <div class="section-container p-l-r">

                <div class="shop-cart {{$totalCount ? '': 'hidden'}}">
                    <div class="row">
                        <div class="col-xl-8 col-lg-12 col-md-12 col-12">
                            <form class="cart-form" action="{{route('basket.update')}}" method="post">
                                @csrf
                                <div class="table-responsive">
                                    <table class="cart-items table" cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th class="product-thumbnail">Ürün</th>
                                            <th class="product-price">Fiyat</th>
                                            <th class="product-quantity">Adet</th>
                                            <th class="product-subtotal">Ara Toplam</th>
                                            <th class="product-remove">&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($carts ?: [] as $cart)
                                            <tr class="cart-item" id="{{$cart->uuid}}">
                                                <td class="product-thumbnail">
                                                    <a href="{{$cart->product->detailUrl()}}">
                                                        <img width="600" height="600" src="{{$cart->product->image()}}" class="product-image" alt="">
                                                    </a>
                                                    <div class="product-name">
                                                        <a href="{{$cart->product->detailUrl()}}">{{$cart->product->name}}</a>
                                                    </div>
                                                </td>
                                                <td class="product-price">
                                                    <span class="price">{{$cart->readableDiscountPrice()}}</span>
                                                </td>
                                                <td class="product-quantity">
                                                    <div class="quantity">
                                                        <button type="button" class="minus">-</button>
                                                        <input type="number" class="qty" step="1" min="1" max="{{$cart->product->stock ?: \App\Model\Product::MAX_ORDER_COUNT}}" name="quantity[{{$cart->uuid}}]" value="{{$cart->quantity}}" title="Qty" size="4" placeholder="" inputmode="numeric" autocomplete="off">
                                                        <button type="button" class="plus">+</button>
                                                    </div>
                                                </td>
                                                <td class="product-subtotal">
                                                    <span>{{$cart->readableTotalDiscountPrice()}}</span>
                                                </td>
                                                <td class="product-remove">
                                                    <a href="javascript:void(0)" class="remove" data-id="{{$cart->uuid}}">×</a>
                                                </td>
                                            </tr>

                                        @endforeach

                                        <tr>
                                            <td colspan="6" class="actions">
                                                <div class="bottom-cart">
                                                    <h2><a href="{{route('home')}}">Alışverişe Devam Et</a></h2>
                                                    <button type="submit" name="update_cart" class="button" value="Sepeti Güncelle">Sepeti Güncelle</button>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                        <div class="col-xl-4 col-lg-12 col-md-12 col-12">
                            <div class="cart-totals">
                                <h2>Sepet Özeti</h2>
                                <div>
                                    <div class="cart-subtotal">
                                        <div class="title">Toplam Tutar</div>
                                        <div><span class="totalPrice">{{$totalPrice}}</span></div>
                                    </div>
                                    <div class="shipping-totals">
                                        <div class="title">Kargolama</div>
                                        <div>
                                            <ul class="shipping-methods custom-radio">
                                                <li>
                                                    <input type="radio" name="shipping_method" data-index="0" value="free_shipping" class="shipping_method" checked="checked"><label>Ücretsiz Kargo</label>
                                                </li>
                                            </ul>
                                            <p class="shipping-desc">
                                               Tüm alışverişlerinizde kargo ücretsiz!
                                            </p>
                                        </div>
                                    </div>
                                    <div class="order-total">
                                        <div class="title">İndirim Tutarı</div>
                                        <div><span class="discountPrice">{{$discountPrice}}</span></div>
                                    </div>
                                    <div class="order-total">
                                        <div class="title">Ödenecek Tutar</div>
                                        <div><span class="totalDiscountPrice">{{$totalDiscountPrice}}</span></div>
                                    </div>
                                </div>
                                <div class="proceed-to-checkout">
                                    <a href="{{auth()->id() ? route('shop') : route('shop').'?alisveris=devam-et'}}" class="checkout-button button">
                                        Sepeti Onayla
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="shop-cart-empty {{$totalCount ? 'hidden' : ''}}">
                        <div class="notices-wrapper">
                            <p class="cart-empty">Sepetinde hiç bir ürün bulunamadı!</p>
                        </div>
                        <div class="return-to-shop">
                            <a class="button" href="{{route('home')}}">
                                Alışverişe Devam Et
                            </a>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
