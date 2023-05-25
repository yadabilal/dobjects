
<div class="back-top button-show">
    <i class="arrow_carrot-up"></i>
</div>

<div class="search-overlay">
    <div class="close-search"></div>
    <div class="wrapper-search">
        <form role="search" method="get" class="search-from ajax-search" action="{{route('home')}}">
            <div class="search-box">
                <button id="searchsubmit" class="btn" type="submit">
                    <i class="icon-search"></i>
                </button>
                <input id="myInput" type="text" autocomplete="off" value="{{request()->has('urun') ? request()->get('urun') : ''}}" name="urun" class="input-search s" placeholder="Ürün adı yazın...">
                <div class="search-top">
                    <div class="close-search">İptal Et</div>
                </div>
                <!--<div class="content-menu_search">
                    <label>Kategoriler</label>
                    <ul id="menu_search" class="menu">
                        <li><a href="#">Furniture</a></li>
                        <li><a href="#">Home Décor</a></li>
                        <li><a href="#">Industrial</a></li>
                        <li><a href="#">Kitchen</a></li>
                    </ul>
                </div> -->
            </div>
        </form>
    </div>
</div>

<div class="page-preloader">
    <div class="loader">
        <div></div>
        <div></div>
    </div>
</div>
