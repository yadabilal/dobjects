@extends('layouts.app')
@section('meta')
  <title>{{@$settings['meta_title'] ?: 'Deek Objects | Tasarım Ürünleri'}}</title>
  <meta name="keywords" content="{{@$settings['meta_keywords']}}">
  <meta name="description" content="{{@$settings['meta_description']}}" />
@endsection
@section('facebookAction')
    fbq('track', 'PageView');
@endsection
@section('content')
    @include('layouts.breadcrumb', ['title' => "Mağaza"])
    <div id="content" class="site-content" role="main">
        <div class="section-padding">
            <div class="section-container p-l-r">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-12 col-12 sidebar left-sidebar md-b-50">
                        <div class="block block-product-cats">
                            <div class="block-title"><h2>Kategoriler</h2></div>
                            <div class="block-content">
                                <div class="product-cats-list">
                                    <ul>
                                        @foreach($categories as $category)
                                            <li class="{{request()->url() == $category->detailUrl() ? 'current' : '' }}">
                                                <a href="{{$category->detailUrl()}}">{{$category->name}} <span class="count">{{$category->products_count}}</span></a>
                                            </li>
                                        @endforeach
                                        <li>
                                            <a class="{{request()->url() == route('home') ? 'current' : '' }}" href="{{route('home')}}">Tüm Ürünler</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-9 col-lg-9 col-md-12 col-12">
                        <div class="products-topbar clearfix">
                            <div class="products-topbar-left">
                                <div class="products-count">
                                   Toplam {{$items->total()}} ürün bulundu.
                                </div>
                            </div>
                            <div class="products-topbar-right">
                                <div class="products-sort dropdown">
                                    <span class="sort-toggle dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Sırala</span>
                                    <ul class="sort-list dropdown-menu" x-placement="bottom-start">
                                        @foreach($urls as $url)
                                            <li class="active"><a href="{{$url['url']}}">{{$url['title']}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <ul class="layout-toggle nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="layout-grid nav-link active" data-toggle="tab" href="#layout-grid" role="tab"><span class="icon-column"><span class="layer first"><span></span><span></span><span></span></span><span class="layer middle"><span></span><span></span><span></span></span><span class="layer last"><span></span><span></span><span></span></span></span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="layout-grid" role="tabpanel">
                                <div class="products-list grid">
                                    <div class="row">

                                        @if(!$items->total())
                                            <div class="alert alert-danger fullwidth" role="alert">
                                                Herhangi bir sonuç bulunamadı! Lütfen tüm bilgileri doğru girdiğinden emin ol!
                                            </div>
                                        @endif

                                        @foreach($items as $item)
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                                                <div class="products-entry clearfix product-wapper">
                                                    <div class="products-thumb">
                                                        <div class="product-lable">
                                                            @if($item->isDiscount())
                                                                <div class="onsale">{{$item->readableDisCountRate()}}</div>
                                                                <div class="hot">İndirimli</div>
                                                            @endif
                                                        </div>
                                                        <div class="product-thumb-hover">
                                                            <a href="{{$item->detailUrl()}}">
                                                                @if($item->twoFiles)
                                                                    @if(@$item->twoFiles[0])
                                                                        <img width="600" height="600" src="{{url('uploads/'.$item->twoFiles[0]->path)}}" class="post-image" title="{{$item->seo_title()}}" alt="{{$item->seo_description()}}">
                                                                    @endif
                                                                    @if(@$item->twoFiles[1])
                                                                        <img width="600" height="600" src="{{url('uploads/'.$item->twoFiles[1]->path)}}" class="hover-image back" title="{{$item->seo_title()}}" alt="{{$item->seo_description()}}">
                                                                    @elseif(@$item->twoFiles[0])
                                                                            <img width="600" height="600" src="{{url('uploads/'.$item->twoFiles[0]->path)}}" class="hover-image back" title="{{$item->seo_title()}}" alt="{{$item->seo_description()}}">

                                                                    @endif
                                                                @endif
                                                            </a>
                                                        </div>
                                                        <div class="product-button">
                                                            @if($item->in_basket())
                                                                <div class="btn-add-to-cart" data-title="Sepetinde"  data-id="{{$item->uuid}}">
                                                                    <a href="javascript:void(0)" class="added-to-cart product-btn" title="Sepetinde" tabindex="0">Sepetinde</a>
                                                                </div>
                                                            @else
                                                                <div class="btn-add-to-cart" data-title="Sepete Ekle"  data-id="{{$item->uuid}}">
                                                                    <a rel="nofollow" href="javascript:void(0)" class="product-btn button" data-id="{{$item->uuid}}">Sepete Ekle</a>
                                                                </div>
                                                            @endif
                                                                @if($item->in_wishlist())
                                                                    <div class="btn-wishlist" data-title="Favorilerinde">
                                                                        <button class="product-btn added" onclick="location.href='{{$item->deleteFavoriteUrl()}}';">Favorilerinde</button>
                                                                    </div>
                                                                @else
                                                                    <div class="btn-wishlist" data-title="Favorilere Ekle">
                                                                        <button class="product-btn" onclick="location.href='{{$item->addFavoriteUrl()}}';">Favorilere Ekle</button>
                                                                    </div>
                                                                @endif
                                                        </div>
                                                    </div>
                                                    <div class="products-content">
                                                        <div class="contents text-center">
                                                            <h3 class="product-title"><a href="{{$item->detailUrl()}}">{{$item->name}}</a></h3>
                                                            <span class="price">
                                                                @if($item->isDiscount())
																    <del aria-hidden="true"><span>{{$item->readablePrice()}}</span></del>
															        <ins><span>{{$item->readableDiscountPrice()}}</span></ins>
                                                                @else
                                                                    <span class="price">{{$item->readablePrice()}}</span>
                                                                @endif
															</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{ $items->appends(request()->input())->links('layouts.pagination')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page-scripts')

@endpush
