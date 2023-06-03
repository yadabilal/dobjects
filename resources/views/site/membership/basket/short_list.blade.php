@if($count)
    <div class="cart-list-wrap">

        <ul class="cart-list ">
            @foreach($carts as $cart)
                <li class="mini-cart-item">
                    <a href="#" class="remove" title="Sepetten Sil" data-id="{{$cart->uuid}}"><i class="icon_close"></i></a>
                    <a href="{{$cart->product->detailUrl()}}" class="product-image">
                        <img width="600" height="600" src="{{$cart->product->image()}}" alt="">
                    </a>
                    <a href="{{$cart->product->detailUrl()}}" class="product-name">{{$cart->product->name}}</a>
                    <div class="quantity">Adet: {{$cart->quantity}}</div>
                    <div class="price">{{$cart->readableDiscountPrice()}}</div>
                </li>
            @endforeach
        </ul>
        <div class="total-cart">
            <div class="title-total">Toplam Tutar: </div>
            <div class="total-price"><span>{{$totalPrice}}</span></div>
        </div>
        <div class="free-ship">
            <div class="title-ship">Her Siparişe özel <strong>Ücretsiz Kargo</strong></div>
            <div class="total-percent"><div class="percent" style="width:20%"></div></div>
        </div>
        <div class="buttons">
            <a href="{{auth()->id() ? route('basket.short_list') : route('tempbasket.short_list') }}" class="button btn view-cart btn-primary">Sepete Git</a>
            <a href="{{route('home')}}" class="button btn checkout btn-default">Alışverişe Devam Et</a>
        </div>

    </div>

@else
    <div class="cart-empty-wrap">
        <ul class="cart-list">
            <li class="empty">
                <span>
                   Gösterilecek bir şey yok!
                 </span>
                <a class="go-shop" href="{{route('home')}}">Alışverişe Devam Et<i aria-hidden="true" class="arrow_right"></i></a>
            </li>
        </ul>
    </div>
@endif
