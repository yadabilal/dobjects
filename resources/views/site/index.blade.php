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

    <div id="site-main" class="site-main">
        <div id="main-content" class="main-content">
            <div id="primary" class="content-area">
                <div id="content" class="site-content" role="main">
                    @if($typeOnes->count())
                        <section class="section m-b-70">
                            <!-- Block Sliders -->
                            <div class="block block-sliders layout-3 nav-vertical">
                                <div class="slick-sliders" data-autoplay="true" data-dots="true" data-nav="false" data-columns4="1" data-columns3="1" data-columns2="1" data-columns1="1" data-columns1440="1" data-columns="1">
                                    @foreach($typeOnes as $typeOne)
                                        <div class="item slick-slide">
                                            <div class="item-content">
                                                <div class="content-image">
                                                    <img width="1920" height="1080" src="{{$typeOne->getPic()}}" title="{{$typeOne->title}}" alt="{{$typeOne->title}}">
                                                </div>
                                                <div class="section-padding">
                                                    <div class="section-container">
                                                        <div class="item-info horizontal-start vertical-middle">
                                                            <div class="content">
                                                                <h2 class="title-slider">{{$typeOne->title}}</h2>
                                                                <div class="description-slider">{{$typeOne->sub_title}}</div>
                                                                @if($typeOne->url)
                                                                    <a class="button-slider button-white" href="{{$typeOne->url}}">Alışveriş Yap</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    @endif

                    @if($typeTwos->count())
                            <section class="section section-padding m-b-70">
                                @foreach($typeTwos as $typeTwo)
                                    <div class="section-container">
                                        <div class="block block-intro">
                                            <div class="block-widget-wrap">
                                                <div class="intro-image">
                                                    <img width="105" height="105" class="animation-round" src="{{$typeTwo->getPic()}}" alt="{{$typeTwo->title}}" title="{{$typeTwo->title}}">
                                                </div>
                                                <div class="intro-text">
                                                    {{$typeTwo->title}}
                                                </div>
                                                @if($typeTwo->url)
                                                    <div class="intro-button">
                                                        <a class="btn-underline center" href="{{$typeTwo->url}}">{{$typeTwo->sub_title}}</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </section>
                    @endif

                        @if($typeThrees->count())
                            <section class="section section-padding m-b-70">
                                <div class="section-container">
                                    <div class="block block-banners layout-4 banners-effect">
                                        <div class="block-widget-wrap">
                                            <div class="row">
                                                @foreach($typeThrees as $typeThree)
                                                    <div class="col-md-4 sm-m-b-50">
                                                        <div class="block-widget-banner layout-5">
                                                            <div class="bg-banner">
                                                                <div class="banner-wrapper banners">
                                                                    <div class="banner-image">
                                                                        <a href="{{$typeThree->url ?: 'javascript:void(0)'}}">
                                                                            <img width="496" height="577" src="{{$typeThree->getPic()}}" alt="{{$typeThree->title}}" title="{{$typeThree->title}}">
                                                                        </a>
                                                                    </div>
                                                                    <div class="banner-wrapper-infor">
                                                                        <div class="info">
                                                                            <div class="content">
                                                                                <a class="link-title" href="{{$typeThree->url ?: 'javascript:void(0)'}}">
                                                                                    <h3 class="title-banner">{{$typeThree->title}}</h3>
                                                                                </a>
                                                                                <div class="banner-image-description">
                                                                                    {{$typeThree->sub_title}}
                                                                                </div>
                                                                                @if($typeThree->url)
                                                                                    <a class="button btn-underline" href="{{$typeThree->url}}">Alışveriş Yap</a>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif

                        @if($typeFours->count())
                            <section class="section section-padding top-border p-t-70 m-b-70">
                                <div class="section-container">
                                    <div class="block block-product-cats slider">
                                        <div class="block-widget-wrap">
                                            <div class="block-title"><h2>Kategoriler</h2></div>
                                            <div class="block-content">
                                                <div class="product-cats-list slick-wrap">
                                                    <div class="slick-sliders content-category" data-dots="0" data-slidestoscroll="true" data-nav="1" data-columns4="2" data-columns3="3" data-columns2="4" data-columns1="5" data-columns1440="5" data-columns="5">
                                                        @foreach($typeFours as $typeFours)
                                                            <div class="item item-product-cat slick-slide">
                                                                <div class="item-product-cat-content">
                                                                    <a href="{{$typeFours->url ?: 'javascript:void(0)'}}">
                                                                        <div class="item-image">
                                                                            <img width="258" height="258" src="{{$typeFours->getPic()}}" alt="{{$typeFours->title}}" title="{{$typeFours->title}}">
                                                                        </div>
                                                                    </a>
                                                                    <div class="product-cat-content-info">
                                                                        <h2 class="item-title">
                                                                            <a href="{{$typeFours->url ?: 'javascript:void(0)'}}">{{$typeFours->title}}</a>
                                                                        </h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif

                        @if($typeFives->count())
                            <section class="section background-2 no-space m-b-70">
                                @foreach($typeFives as $typeFive)
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="block block-banners banners-effect">
                                                <div class="block-widget-banner">
                                                    <div class="bg-banner">
                                                        <div class="banner-wrapper banners">
                                                            <div class="banner-image">
                                                                <a href="#">
                                                                    <img width="961" height="452" src="{{$typeFive->getPic()}}" alt="{{$typeFive->title}}" title="{{$typeFive->title}}">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="block block-newsletter position-center">
                                                <div class="newsletter-wrap">
                                                    <form action="{{route('subscribe.save')}}" method="post">
                                                        @csrf
                                                        <div class="sub-title">{{$typeFive->sub_title}}</div>
                                                        <div class="title">{{$typeFive->title}}</div>
                                                        <div class="newsletter-content without-message">
													    <span class="your-email">
														    <input id="txtEmail" type="email" name="email" value="" size="40" aria-required="true" placeholder="Email Adresi">
                                                        </span>
                                                        <span class="clearfix">
                                                                <input type="button" value="Abone Ol" class="buttonDisable btn-save" data-action="{{route('subscribe')}}">
                                                            </span>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </section>
                        @endif
                        @if($typeSixes->count())
                            <section class="section section-padding m-b-70">
                                <div class="section-container">
                                    <div class="block block-product-cats layout-2">
                                        <div class="block-widget-wrap">
                                            <div class="block-title"><h2>Size Özel Tasarımlar</h2></div>
                                            <div class="block-content">
                                                <div class="row">
                                                    @foreach($typeSixes as $typeSix)
                                                        <div class="col-md-3 sm-m-b-30">
                                                            <div class="cat-item">
                                                                <div class="cat-image">
                                                                    <a href="{{$typeSix->url ?: 'javascript:void(0)'}}">
                                                                        <img width="303" height="366" src="{{$typeSix->getPic()}}" alt="{{$typeSix->title}}" title="{{$typeSix->title}}">
                                                                    </a>
                                                                </div>
                                                                <div class="cat-title">
                                                                    <a href="{{$typeSix->url ?: 'javascript:void(0)'}}">
                                                                        <h3>{{$typeSix->title}}</h3>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif
                        @if($products->count())
                            <section class="section section-padding top-border p-t-70 m-b-70">
                                <div class="section-container">
                                    <div class="block block-products slider">
                                        <div class="block-widget-wrap">
                                            <div class="block-title"><h2>Çok Satanlar</h2></div>
                                            <div class="block-content">
                                                <div class="content-product-list slick-wrap">
                                                    <div class="slick-sliders products-list grid" data-slidestoscroll="true" data-dots="false" data-nav="1" data-columns4="1" data-columns3="2" data-columns2="3" data-columns1="3" data-columns1440="4" data-columns="4">
                                                        @foreach($products as $product)
                                                            <div class="item-product slick-slide">
                                                                <div class="items">
                                                                    <div class="products-entry clearfix product-wapper">
                                                                        <div class="products-thumb">
                                                                            <div class="product-lable">
                                                                                @if($product->isDiscount())
                                                                                    <div class="onsale">{{$product->readableDisCountRate()}}</div>
                                                                                    <div class="hot">İndirimli</div>
                                                                                @endif
                                                                            </div>
                                                                            <div class="product-thumb-hover">
                                                                                <a href="{{$product->detailUrl()}}">
                                                                                    @if($product->twoFiles)
                                                                                        @if(@$product->twoFiles[0])
                                                                                            <img width="600" height="600" src="{{url('uploads/'.$product->twoFiles[0]->path)}}" class="post-image" title="{{$product->seo_title()}}" alt="{{$product->seo_description()}}">
                                                                                        @endif
                                                                                        @if(@$product->twoFiles[1])
                                                                                            <img width="600" height="600" src="{{url('uploads/'.$product->twoFiles[1]->path)}}" class="hover-image back" title="{{$product->seo_title()}}" alt="{{$product->seo_description()}}">
                                                                                        @elseif(@$product->twoFiles[0])
                                                                                            <img width="600" height="600" src="{{url('uploads/'.$product->twoFiles[0]->path)}}" class="hover-image back" title="{{$product->seo_title()}}" alt="{{$product->seo_description()}}">

                                                                                        @endif
                                                                                    @endif
                                                                                </a>
                                                                            </div>
                                                                            <div class="product-button">

                                                                                @if($product->in_wishlist())
                                                                                    <div class="btn-wishlist" data-title="Favorilerinde">
                                                                                        <a href="{{$product->deleteFavoriteUrl()}}" class="product-btn added-favorite">Favorilerinde</a>
                                                                                    </div>
                                                                                @else
                                                                                    <div class="btn-wishlist" data-title="Favorilere Ekle">
                                                                                        <a href="{{$product->addFavoriteUrl()}}" class="product-btn">Favorilere Ekle</a>
                                                                                    </div>
                                                                                @endif

                                                                                @if($product->in_basket())
                                                                                    <div class="btn-add-to-cart" data-title="Sepetinde"  data-id="{{$product->uuid}}">
                                                                                        <a href="javascript:void(0)" class="added-to-cart product-btn" title="Sepetinde" tabindex="0">Sepetinde</a>
                                                                                    </div>
                                                                                @else
                                                                                    <div class="btn-add-to-cart" data-title="Sepete Ekle"  data-id="{{$product->uuid}}">
                                                                                        <a rel="nofollow" href="javascript:void(0)" class="product-btn button" data-id="{{$product->uuid}}">Sepete Ekle</a>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="products-content">
                                                                            <div class="contents text-center">
                                                                                <h3 class="product-title"><a href="{{$product->detailUrl()}}">{{$product->name}}</a></h3>
                                                                                <div class="rating">
                                                                                    <div class="star {{\App\Model\Comment::starClass($product->avgRating)}}"></div>
                                                                                </div>
                                                                                <span class="price">
                                                                                    @if($product->isDiscount())
                                                                                        <del aria-hidden="true"><span>{{$product->readablePrice()}}</span></del>
                                                                                        <ins><span>{{$product->readableDiscountPrice()}}</span></ins>
                                                                                    @else
                                                                                        <span class="price">{{$product->readablePrice()}}</span>
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="btn-all">
                                                    <a class="button-outline" href="{{route('home')}}">Tümü</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif
                        @if($comments->count())
                            <section class="section section-padding top-border p-t-70 m-b-70">
                                <div class="section-container">
                                    <!-- Block Testimonial -->
                                    <div class="block block-testimonial layout-2">
                                        <div class="block-widget-wrap">
                                            <div class="block-title"><h2>Müşterilerimiz Ne Söyledi?</h2></div>
                                            <div class="block-content">
                                                <div class="testimonial-wrap slick-wrap">
                                                    <div class="slick-sliders" data-slidestoscroll="true" data-nav="1" data-dots="0" data-columns4="1" data-columns3="1" data-columns2="2" data-columns1="2" data-columns="3">
                                                        @foreach($comments as $comment)
                                                            <div class="testimonial-content">
                                                                <div class="item">
                                                                    <div class="testimonial-item">
                                                                        <div class="testimonial-icon">
                                                                            <div class="rating">
                                                                                <div class="star {{$comment->star()}}"></div>
                                                                            </div>
                                                                            <span class="icon-quote"></span>
                                                                        </div>
                                                                        <h2 class="testimonial-title">{{$comment->product ? $comment->product->name : ''}}</h2>
                                                                        <div class="testimonial-excerpt">
                                                                            ” {{$comment->review}} “
                                                                        </div>
                                                                    </div>
                                                                    <div class="testimonial-image image-position-top">
                                                                        <div class="testimonial-info">
                                                                            <h2 class="testimonial-customer-name">{{$comment->user->username}}</h2>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif

                        @if($typeSevens->count())
                            <section class="section section-padding top-border p-t-20 m-b-20">
                                <div class="section-container">
                                    <div class="block block-image slider">
                                        <div class="block-widget-wrap">
                                            <div class="slick-wrap">
                                                <div class="slick-sliders" data-nav="0" data-columns4="2" data-columns3="3" data-columns2="4" data-columns1="4" data-columns1440="5" data-columns="5">
                                                    @foreach($typeSevens as $typeSeven)
                                                        <div class="item slick-slide">
                                                            <div class="item-image">
                                                                <a href="{{$typeThree->url ?: 'javascript:void(0)'}}">
                                                                    <img width="450" height="450" src="{{$typeSeven->getPic()}}" alt="{{$typeSeven->title}}" title="{{$typeSeven->title}}">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-scripts')
@endpush
