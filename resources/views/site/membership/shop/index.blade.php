@extends('layouts.app')
@section('meta')
    <title> Deek Objects | Sepet</title>
    <meta name="keywords" content="">
    <meta name="description" content="" />
@endsection
@section('content')
    @include('layouts.breadcrumb', ['title' => "Alışveriş"])

    <div id="content" class="site-content" role="main">
        <div class="section-padding">
            <div class="section-container p-l-r">
                <div class="shop-checkout">
                    <form name="checkout" method="post" class="checkout" autocomplete="off" action="{{route('shop.save_address')}}">
                        @csrf
                        <div class="row">
                            <div class="col-xl-8 col-lg-7 col-md-12 col-12">
                                <div class="customer-details">
                                    <div class="billing-fields">
                                        <h3>Teslimat Bilgileri</h3>
                                        <div class="billing-fields-wrapper">
                                            <p class="form-row form-row-first validate-required">
                                                <label>Ad <span class="required" title="required">*</span></label>
                                                <span class="input-wrapper control">
                                                    <input type="text" class="input-text" name="name" value="{{old("name") ?:$user->name}}">
                                                </span>
                                            </p>
                                            <p class="form-row form-row-last validate-required">
                                                <label>Soyad <span class="required" title="required">*</span></label>
                                                <span class="input-wrapper control">
                                                    <input type="text" class="input-text" name="surname" value="{{old("surname") ?: $user->surname}}">
                                                </span>
                                            </p>
                                            <p class="form-row form-row-wide validate-required">
                                                <label>Şehir <span class="required" title="required">*</span></label>
                                                <span class="input-wrapper control">
                                                    <select name="city_id" class="country-select custom-select city">
                                                        <option>Seç</option>
                                                        @foreach($cities as $city)
                                                            <option value="{{$city->uuid}}" {{old('city_id')==$city->uuid ? 'selected': (@$oldAddress->city_id == $city->id ? 'selected' : '')}}>{{$city->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('city_id')
                                                      <p class="text-error">{{$message}}</p>
                                                      @enderror
                                                </span>
                                            </p>

                                            <p class="form-row form-row-wide validate-required">
                                                <label>İlçe <span class="required" title="required">*</span></label>
                                                <span class="input-wrapper control">
                                                    <select name="town_id" class="country-select custom-select town">
                                                       <option>Seç</option>
                                                        @foreach($towns as  $town)
                                                            <option value="{{$town->uuid}}" {{old('town_id')==$town->uuid ? 'selected': (@$oldAddress->town_id == $town->id ? 'selected' : '')}}>{{$town->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('town_id')
                                                  <p class="text-error">{{$message}}</p>
                                                  @enderror
                                                </span>
                                            </p>
                                            <p class="form-row address-field validate-required form-row-wide">
                                                <label>Detay <span class="required" title="required">*</span></label>
                                                <span class="input-wrapper control">
                                                    <input type="text" class="input-text" name="address" placeholder="Mahalle, sokak ev numarası ve daha fazla detay..." maxlength="130" value="{{old('address') ? : @$oldAddress->address}}">
                                                @error('address')
                                                  <p class="text-error">{{$message}}</p>
                                                  @enderror
                                                </span>
                                            </p>
                                            <p class="form-row form-row-wide validate-required validate-phone">
                                                <label>Telefon <span class="required" title="required">*</span></label>
                                                <span class="input-wrapper control">
                                                    <input type="tel" class="input-text" name="phone" value="{{old('phone') ?: $user->phone}}">
                                                    @error('phone')
                                                      <p class="text-error">{{$message}}</p>
                                                    @enderror
                                                </span>
                                            </p>
                                            <p class="form-row form-row-wide validate-required validate-email">
                                                <label>Email Adresi <span class="required" title="required">*</span></label>
                                                <span class="input-wrapper control">
                                                    <input type="email" class="input-text" name="email" value="{{old('email') ?: @$oldAddress->email}}" autocomplete="off">
                                                    @error('email')
                                                      <p class="text-error">{{$message}}</p>
                                                    @enderror
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="additional-fields">
                                    <p class="form-row notes">
                                        <label>Fatura Bilgileri <span class="optional">(Varsa)</span></label>
                                        <span class="input-wrapper">
                                            <textarea name="billing_note" class="input-text" placeholder="Fatura için diğer bilgiler. (Tc, Firma bilgisi, Vergi numarası, Vergi dairesi, İl, İlçe vb.)" rows="2" cols="5" maxlength="255">{{old('note')}}</textarea>
                                            @error('billing_note')
                                                      <p class="text-error">{{$message}}</p>
                                            @enderror
                                        </span>
                                    </p>
                                </div>

                                <div class="additional-fields">
                                    <p class="form-row notes">
                                        <label>Sipariş Notu <span class="optional">(Varsa)</span></label>
                                        <span class="input-wrapper">
                                            <textarea name="note" class="input-text" placeholder="Sipariş hakkında özel notunuz..." rows="2" cols="5" maxlength="255">{{old('note')}}</textarea>
                                            @error('note')
                                                <p class="text-error">{{$message}}</p>
                                            @enderror
                                        </span>
                                    </p>
                                </div>
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
                                                <input class="input-checkbox" type="checkbox" name="confirm_document" value="1">
                                                <span>
                                                    Ön Bilgilendirme Koşulları'nı ve Mesafeli Satış Sözleşmesi'ni okudum, onaylıyorum.</span>
                                            </label>
                                        </p>
                                        <div class="form-row place-order">
                                            <div class="terms-and-conditions-wrapper">
                                                <div class="privacy-policy-text"></div>
                                            </div>
                                            <button
                                                data-action="{{route('shop.check')}}"
                                                class="button alt is-solid accent-button raised btn-payment is-fullwidth"
                                                type="button"
                                                value="Ödeme Aşamasına Geç">Ödeme Aşamasına Geç</button>
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
