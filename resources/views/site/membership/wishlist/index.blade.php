@extends('layouts.app')
@section('meta')
    <title> Deek Objects | Favorilerim</title>
    <meta name="keywords" content="">
    <meta name="description" content="" />
@endsection
@section('content')
    @include('layouts.breadcrumb', ['title' => "Favorilerim"])

    <div id="content" class="site-content" role="main">
        <div class="section-padding">
            <div class="section-container p-l-r">
                <div class="page-my-account">
                    <div class="my-account-wrap clearfix">
                        @include("layouts.lef_menu")
                        <div class="my-account-content tab-content">
                            <div class="tab-pane fade show active">
                                <div id="shop-page" class="my-account-orders stats-wrapper shop-wrapper">
                                    <div class="shop-wishlist">
                                        <table class="wishlist-items">
                                            <tbody>
                                            @if(!$items->total())
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="alert alert-danger fullwidth" role="alert">
                                                            Herhangi bir sonuç bulunamadı!
                                                        </div>
                                                    </td>
                                                </tr>

                                            @else
                                                @foreach($items as $item)
                                                    <tr class="wishlist-item">
                                                        <td class="wishlist-item-remove"><a href="{{$item->product->deleteFavoriteUrl()}}"><span></span></a></td>
                                                        @if($item->product->file)
                                                            <td class="wishlist-item-image">
                                                                <a href="{{$item->product->detailUrl()}}">
                                                                    <img width="600" height="600" src="{{url('uploads/'.$item->product->file->path)}}" alt="">
                                                                </a>
                                                            </td>
                                                        @endif

                                                        <td class="wishlist-item-info">
                                                            <div class="wishlist-item-name">
                                                                <a href="{{$item->product->detailUrl()}}">{{$item->product->name}}</a>
                                                            </div>
                                                            <div class="wishlist-item-price">
                                                                @if($item->product->isDiscount())
                                                                    <del aria-hidden="true"><span>{{$item->product->readablePrice()}}</span></del>
                                                                    <ins><span>{{$item->product->readableDiscountPrice()}}</span></ins>
                                                                @else
                                                                    <span>{{$item->product->readablePrice()}}</span>
                                                                @endif
                                                            </div>
                                                            <div class="wishlist-item-time">{{$item->created_at()}}</div>
                                                        </td>
                                                        <td class="wishlist-item-actions">
                                                            <div class="wishlist-item-stock">
                                                                Stokta Var
                                                            </div>
                                                            <div class="wishlist-item-add">
                                                                @if($item->product->in_basket())
                                                                    <div class="btn-add-to-cart" data-title="Sepetinde"  data-id="{{$item->product->uuid}}">
                                                                        <a href="javascript:void(0)" class="added-to-cart product-btn" title="Sepetinde" tabindex="0">Sepetinde</a>
                                                                    </div>
                                                                @else
                                                                    <div class="btn-add-to-cart" data-title="Sepete Ekle"  data-id="{{$item->product->uuid}}">
                                                                        <a rel="nofollow" href="javascript:void(0)" class="product-btn button" data-id="{{$item->product->uuid}}">Sepete Ekle</a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            </tbody>
                                        </table>

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
