@extends('layouts.app')
@section('meta')
  <title>Deek Objects | {{$item->seo_title()}}</title>
  <meta name="keywords" content="{{$item->seo_tags()}}">
  <meta name="description" content="{{$item->seo_description()}}" />
@endsection
@section('content')
    @include('layouts.breadcrumb', ['title' => $item->name])
    <div id="content" class="site-content" role="main">
        <div class="shop-details zoom" data-product_layout_thumb="scroll" data-zoom_scroll="true" data-zoom_contain_lens="true" data-zoomtype="inner" data-lenssize="200" data-lensshape="square" data-lensborder="" data-bordersize="2" data-bordercolour="#f9b61e" data-popup="false">
            <div class="product-top-info">
                <div class="section-padding">
                    <div class="section-container p-l-r">
                        <div class="row">
                            <div class="product-images col-lg-7 col-md-12 col-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="content-thumbnail-scroll">
                                            <div class="image-thumbnail slick-carousel slick-vertical" data-asnavfor=".image-additional" data-centermode="true" data-focusonselect="true" data-columns4="5" data-columns3="4" data-columns2="4" data-columns1="4" data-columns="4" data-nav="true" data-vertical="&quot;true&quot;" data-verticalswiping="&quot;true&quot;">
                                                @foreach($item->files as $file)
                                                    <div class="img-item slick-slide">
                                                        <span class="img-thumbnail-scroll">
                                                            <img width="600" height="600" src="{{url('uploads/'.$file->path)}}" alt="">
                                                        </span>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="scroll-image main-image">
                                            <div class="image-additional slick-carousel" data-asnavfor=".image-thumbnail" data-fade="true" data-columns4="1" data-columns3="1" data-columns2="1" data-columns1="1" data-columns="1" data-nav="true">
                                                @foreach($item->files as $file)
                                                    <div class="img-item slick-slide">
                                                        <img width="900" height="900" src="{{url('uploads/'.$file->path)}}" alt="" title="">
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="product-info col-lg-5 col-md-12 col-12 ">
                                <h1 class="title">{{$item->name}}</h1>
                                <span class="price">
                                    @if($item->isDiscount())
                                        <del aria-hidden="true"><span>{{$item->readablePrice()}}</span></del>
                                        <ins><span>{{$item->readableDiscountPrice()}}</span></ins>
                                    @else
                                        <span class="price">{{$item->readablePrice()}}</span>
                                    @endif
								</span>
                                <div class="rating">
                                    <div class="star {{\App\Model\Comment::starClass($item->avgRating)}}"></div>
                                    <div class="review-count">
                                        ({{$item->comments_count}}<span> Yorum</span>)
                                    </div>
                                </div>
                                <div class="description">
                                    <p>{!! $item->short_description !!}</p>
                                </div>

                                <div class="buttons">
                                    <div class="add-to-cart-wrap">
                                        <div class="quantity">
                                            <button type="button" class="plus">+</button>
                                            <input type="number" class="qty" step="1" min="1" max="" id="quantity" data-id="{{$item->uuid}}" name="quantity" value="1" title="Qty" size="4" placeholder="" inputmode="numeric" autocomplete="off">
                                            <button type="button" class="minus">-</button>
                                        </div>
                                        @guest
                                            <div class="btn-add-to-cart" data-title="Sepete Ekle"  data-id="{{$item->uuid}}">
                                                <a rel="nofollow" href="javascript:void(0)" class="button" data-id="{{$item->uuid}}">Sepete Ekle</a>
                                            </div>
                                        @else

                                            @if($item->in_basket())
                                                <div class="btn-add-to-cart" data-title="Sepetinde"  data-id="{{$item->uuid}}">
                                                    <a href="javascript:void(0)" class="added-to-cart product-btn" title="Sepetinde" tabindex="0">Sepetinde</a>
                                                </div>
                                            @else
                                                <div class="btn-add-to-cart" data-title="Sepete Ekle"  data-id="{{$item->uuid}}">
                                                    <a rel="nofollow" href="javascript:void(0)" class="button" data-id="{{$item->uuid}}">Sepete Ekle</a>
                                                </div>
                                            @endif
                                        @endguest
                                    </div>
                                </div>
                                <div class="product-meta">
                                    <span class="posted-in">Kategori: <a href="{{$item->category->detailUrl()}}" rel="tag">{{$item->category->name}}</a></span>
                                    <span class="tagged-as">Etiketler:
                                        {!! $item->readableTags() !!}
                                    </span>
                                </div>
                                <div class="social-share">
                                    <a href="{{$item->shareFacebookUrl()}}" title="Facebook" class="share-facebook" target="_blank">
                                        <i class="fa fa-facebook"></i>Facebook
                                    </a>
                                    <a href="{{$item->shareTwitterUrl()}}" title="Twitter" class="share-twitter" target="_blank">
                                        <i class="fa fa-twitter"></i>Twitter
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-tabs">
                <div class="section-padding">
                    <div class="section-container p-l-r">
                        <div class="product-tabs-wrap">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#description" role="tab">Açıklama</a>
                                </li>
                                @if($item->additional_information)
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#additional-information" role="tab">Ek Bilgiler</a>
                                </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Yorumlar ({{$item->comments_count}})</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="description" role="tabpanel">
                                {!! $item->description !!}
                                </div>
                                @if($item->additional_information)
                                <div class="tab-pane fade" id="additional-information" role="tabpanel">
                                    {!! $item->additional_information !!}
                                </div>
                                @endif
                                <div class="tab-pane fade" id="reviews" role="tabpanel">
                                    <div id="reviews" class="product-reviews">
                                        <div id="comments">
                                            <h2 class="reviews-title">{{$item->comments_count ? $item->comments_count.' yorum yapıldı.': 'Henüz yorum yapılmadı. İlk yorum yapan sen ol!'}} </h2>
                                            <ol class="comment-list">
                                                @foreach($item->comments as $comment)
                                                    <li class="review">
                                                        <div class="content-comment-container">
                                                            <div class="comment-container">
                                                                <div class="comment-text">
                                                                    <div class="rating small">
                                                                        <div class="star {{$comment->star()}}"></div>
                                                                    </div>
                                                                    <div class="review-author">{{$comment->user->username}}</div>
                                                                    <div class="review-time">{{$comment->created_at()}}</div>
                                                                </div>
                                                            </div>
                                                            <div class="description">
                                                                <p>{!! $comment->review !!}</p>
                                                            </div>
                                                        </div>
                                                    </li>

                                                @endforeach

                                            </ol>
                                        </div>
                                        <div id="review-form">
                                            <div id="respond" class="comment-respond">
                                                <span id="reply-title" class="comment-reply-title">Yorum Yaz</span>
                                                @guest
                                                    <div class="alert alert-danger fullwidth" role="alert">
                                                        Yorum yapabilmek için lütfen kayıt olun ya da giriş yapın!
                                                    </div>
                                                @else
                                                    @if(!$item->can_comment())
                                                        <div class="alert alert-danger fullwidth" role="alert">
                                                            Bu ürüne, sadece ürünü satın alanlar yorum yapabilir!
                                                        </div>
                                                    @else
                                                    <form method="post" id="comment-form" class="comment-form" action="{{route('comment.save')}}">
                                                        @csrf

                                                        <input type="hidden" name="id" value="{{$item->uuid}}">
                                                        <p class="comment-notes">
                                                            <span id="email-notes">Yaptığın yorumlarda adın ve soyadın paylaşılmayacaktır.</span> Kullanıcı adın görünecektir.
                                                        </p>
                                                        <div class="comment-form-rating">
                                                            <label for="rating">Puanın</label>
                                                            <p class="stars">
                                                                <span>
                                                                    <a class="review-star star-1" data-id="1" href="javascript:void(0)">1</a>
                                                                    <a class="review-star star-2" data-id="2" href="javascript:void(0)">2</a>
                                                                    <a class="review-star star-3" data-id="3" href="javascript:void(0)">3</a>
                                                                    <a class="review-star star-4" data-id="4" href="javascript:void(0)">4</a>
                                                                    <a class="review-star star-5" data-id="5" href="javascript:void(0)">5</a>
                                                                </span>
                                                                <input type="hidden" name="rate" id="reviewRate" class="input input-text required">
                                                            </p>

                                                        </div>

                                                        <div class="content-info-reviews">
                                                            <p class="comment-form-comment">
                                                                <textarea id="review" name="review" class="input input-text required" placeholder="Yorumun *" cols="45" rows="3" aria-required="true" required=""></textarea>
                                                            </p>
                                                            <p class="form-submit">
                                                                <input
                                                                    data-action="{{route('comment.check')}}"
                                                                    class="button alt is-solid accent-button raised  buttonDisable btn-save submit is-fullwidth"
                                                                    type="button" value="Yorum Yap">
                                                            </p>
                                                        </div>
                                                    </form>
                                                    @endif
                                                @endguest

                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-related">
                <div class="section-padding">
                    <div class="section-container p-l-r">
                        <div class="block block-products slider">
                            <div class="block-title"><h2>Son Eklenen Ürünler</h2></div>
                            <div class="block-content">
                                <div class="content-product-list slick-wrap">
                                    <div class="slick-sliders products-list grid" data-slidestoscroll="true" data-dots="false" data-nav="1" data-columns4="1" data-columns3="2" data-columns2="3" data-columns1="3" data-columns1440="4" data-columns="4">
                                        @foreach($lastItems as $lastItem)
                                            <div class="item-product slick-slide">
                                                <div class="items">
                                                    <div class="products-entry clearfix product-wapper">
                                                        <div class="products-thumb">
                                                            <div class="product-lable">
                                                                @if($lastItem->isDiscount())
                                                                    <div class="onsale">{{$lastItem->readableDisCountRate()}}</div>
                                                                    <div class="hot">İndirimli</div>
                                                                @endif
                                                            </div>
                                                            <div class="product-thumb-hover">
                                                                <a href="{{$lastItem->detailUrl()}}">
                                                                    @if($lastItem->twoFiles)
                                                                        @if(@$lastItem->twoFiles[0])
                                                                            <img width="600" height="600" src="{{url('uploads/'.$lastItem->twoFiles[0]->path)}}" class="post-image" alt="">
                                                                        @endif
                                                                        @if(@$lastItem->twoFiles[1])
                                                                            <img width="600" height="600" src="{{url('uploads/'.$lastItem->twoFiles[1]->path)}}" class="hover-image back" alt="">
                                                                        @elseif(@$lastItem->twoFiles[0])
                                                                            <img width="600" height="600" src="{{url('uploads/'.$lastItem->twoFiles[0]->path)}}" class="hover-image back" alt="">

                                                                        @endif
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="product-button">
                                                                @guest
                                                                    <div class="btn-add-to-cart" data-title="Sepete Ekle"  data-id="{{$lastItem->uuid}}">
                                                                        <a rel="nofollow" href="javascript:void(0)" class="product-btn button" data-id="{{$lastItem->uuid}}">Sepete Ekle</a>
                                                                    </div>
                                                                @else

                                                                    @if($lastItem->in_basket())
                                                                        <div class="btn-add-to-cart" data-title="Sepetinde"  data-id="{{$lastItem->uuid}}">
                                                                            <a href="javascript:void(0)" class="added-to-cart product-btn" title="Sepetinde" tabindex="0">Sepetinde</a>
                                                                        </div>
                                                                    @else
                                                                        <div class="btn-add-to-cart" data-title="Sepete Ekle"  data-id="{{$lastItem->uuid}}">
                                                                            <a rel="nofollow" href="javascript:void(0)" class="product-btn button" data-id="{{$lastItem->uuid}}">Sepete Ekle</a>
                                                                        </div>
                                                                    @endif
                                                                @endguest
                                                            </div>
                                                        </div>
                                                        <div class="products-content">
                                                            <div class="contents text-center">
                                                                <h3 class="product-title"><a href="{{$lastItem->detailUrl()}}">{{$lastItem->name}}</a></h3>
                                                                <div class="rating">
                                                                    <div class="star {{\App\Model\Comment::starClass($lastItem->avgRating)}}"></div>
                                                                </div>
                                                                <span class="price">
                                                                    @if($lastItem->isDiscount())
                                                                            <del aria-hidden="true"><span>{{$lastItem->readablePrice()}}</span></del>
                                                                            <ins><span>{{$lastItem->readableDiscountPrice()}}</span></ins>
                                                                        @else
                                                                            <span class="price">{{$lastItem->readablePrice()}}</span>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('page-scripts')

@endpush
