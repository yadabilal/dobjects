<header id="site-header" class="site-header header-v1 absolute">
    <div class="header-mobile">
        <div class="section-padding">
            <div class="section-container">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-3 header-left">

                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6 header-center">
                        <div class="site-logo">
                            @include('layouts.logo')
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-3 header-right">
                        <div class="ruper-topcart dropdown">
                            <div class="dropdown mini-cart top-cart">
                                <div class="remove-cart-shadow"></div>
                                <a class="dropdown-toggle cart-icon cart-button" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="icons-cart"><i class="icon-large-paper-bag"></i><span class="cart-count">{{$cartItemCount}}</span></div>
                                </a>
                                <div class="dropdown-menu cart-popup">
                                    <div class="loader is-loading"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="header-mobile-fixed">
            <!-- Shop -->
            <div class="shop-page">
                <a href="{{route('mainpage')}}" title="Anasayfa"><i class="fa fa-home font-awasome-icon"></i></a>
            </div>
            <div class="shop-page">
                <a href="{{route('home')}}" title="Mağaza"><i class="wpb-icon-shop"></i></a>
            </div>
            <div class="shop-page">
                <a href="{{route('product.accesorio')}}" title="Aksesuar"><i class="wpb-icon-d-design"></i></a>
            </div>
            <div class="shop-page">
                <a href="{{route('product.discounted')}}" title="İndirimli Ürünler"><i class="wpb-icon-gift-voucher"></i></a>
            </div>
            <div class="shop-page">
                <a href="{{route('contact')}}" title="İletişim"><i class="wpb-icon-chat"></i></a>
            </div>
            @guest
                <!-- Login -->
                <div class="my-account">
                    <div class="login-header">
                        <a href="{{url('giris-yap')}}" title="Giriş Yap"><i class="wpb-icon-user"></i></a>
                    </div>
                </div>
            @else

                <div class="my-account">
                    <div class="login-header">
                        <a href="{{route('profile')}}" title="Hesabım"><i class="wpb-icon-user"></i></a>
                    </div>
                </div>
            @endguest

            <div class="wishlist-box">
                <a href="{{route('wishlist.index')}}" title="Favorilerim">
                    <i class="wpb-icon-heart"></i>
                </a>
            </div>
            <div class="search-box">
                <div class="search-toggle" title="Ara"><i class="wpb-icon-magnifying-glass"></i></div>
            </div>
        </div>
    </div>

    <div class="header-desktop">
        <div class="header-wrapper">
            <div class="section-padding">
                <div class="section-container p-l-r">
                    <div class="row">
                        <div class="col-xl-3 col-lg-2 col-md-12 col-sm-12 col-12 header-left">
                            <div class="site-logo">
                                @include('layouts.logo')
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 text-center header-center">
                            <div class="site-navigation">
                                <nav id="main-navigation">
                                    <ul id="menu-main-menu" class="menu">
                                        <li class="level-0 menu-item">
                                            <a href="{{route('mainpage')}}"><span class="menu-item-text">Anasayfa</span></a>
                                        </li>
                                        <li class="level-0 menu-item">
                                            <a href="{{route('home')}}"><span class="menu-item-text">Mağaza</span></a>
                                        </li>
                                        <li class="level-0 menu-item">
                                            <a href="{{route('product.accesorio')}}"><span class="menu-item-text">Aksesuar</span></a>
                                        </li>
                                        <li class="level-0 menu-item">
                                            <a href="{{route('product.discounted')}}"><span class="menu-item-text">İndirimli Ürünler</span></a>
                                        </li>
                                        <li class="level-0 menu-item">
                                            <a href="{{route('contact')}}"><span class="menu-item-text">İletişim</span></a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-12 header-right">
                            <div class="header-page-link">
                                @guest
                                    <div class="login-header">
                                        <a href="{{url('giris-yap')}}">Giriş Yap</a>
                                    </div>
                                @else
                                    <div class="login-header">
                                        <a href="{{route('profile')}}">Hesabım</a>
                                    </div>
                                    <div class="wishlist-box">
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i></a>
                                    </div>
                                @endguest

                                <div class="search-box">
                                    <div class="search-toggle"><i class="icon-search"></i></div>
                                </div>

                                    <div class="wishlist-box">
                                        <a href="{{route('wishlist.index')}}"><i class="icon-heart"></i></a>
                                        <span class="count-wishlist">{{$wishListCount}}</span>
                                    </div>
                                <div class="ruper-topcart dropdown light">
                                    <div class="dropdown mini-cart top-cart">
                                        <div class="remove-cart-shadow"></div>
                                        <a class="dropdown-toggle cart-icon cart-button" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <div class="icons-cart"><i class="icon-large-paper-bag"></i><span class="cart-count">{{$cartItemCount}}</span></div>
                                        </a>
                                        <div class="dropdown-menu cart-popup" id="cart-popup">
                                            <div class="loader is-loading"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

@guest

@else
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
@endguest



