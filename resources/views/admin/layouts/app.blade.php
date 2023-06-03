<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
  <title>DeekObjects - Admin Paneli</title>
  <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ asset('preadmin/css/bootstrap.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('preadmin/css/font-awesome.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('preadmin/css/fullcalendar.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('preadmin/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('preadmin/css/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('preadmin/css/bootstrap-datetimepicker.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('preadmin/plugins/morris/morris.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('preadmin/plugins/summernote/dist/summernote-bs4.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('preadmin/css/style.css') }}">
  <!--[if lt IE 9]>
  <script src="{{ asset('preadmin/js/html5shiv.min.js') }}"></script>
  <script src="{{ asset('preadmin/js/respond.min.js') }}"></script>
  <![endif]-->
</head>

<body>
<div class="main-wrapper">
  <div class="header">
    <div class="header-left">
      <a href="{{url('admin')}}" class="logo">
          @if(@$settings['logo'])
              <img src="{{ url('uploads/'.$settings['logo'])}}"  width="112" height="28">
          @else
              Deek Objects
          @endif

      </a>
    </div>
    <div class="page-title-box pull-left">
    </div>
    <a id="mobile_btn" class="mobile_btn pull-left" href="#sidebar"><i class="fa fa-bars" aria-hidden="true"></i></a>
    <ul class="nav user-menu pull-right">
      <li class="nav-item dropdown has-arrow">
        <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span class="user-img">
							<img class="rounded-circle" src="{{asset('preadmin/img/user.jpg')}}" width="40" alt="Admin">
							<span class="status online"></span>
						</span>
          <span>{{\Illuminate\Support\Facades\Auth::user()->name}}</span>
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="{{route('admin.password')}}">Şifre Değiştir</a>

          <a class="dropdown-item" href="{{ route('logout') }}"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
          >Çıkış Yap</a>
        </div>
      </li>
    </ul>
    <div class="dropdown mobile-user-menu pull-right">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
      <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="{{route('admin.password')}}">Şifre Değiştir</a>
        <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        >Çıkış Yap</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </div>
    </div>
  </div>
  <div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
      <div id="sidebar-menu" class="sidebar-menu">
        <ul>
          <li class="{{request()->url() == route('admin.user.index') ? 'active': ''}}">
            <a href="{{route('admin.user.index')}}"><i class="fa fa-dashboard"></i> Kullanıcılar</a>
          </li>
          <li class="{{request()->url() == route('admin.product.index') ? 'active': ''}}">
            <a href="{{route('admin.product.index')}}"><i class="fa fa-book"></i> Ürünler</a>
          </li>
          <li class="{{request()->url() == route('admin.order.index') ? 'active': ''}}">
            <a href="{{route('admin.order.index')}}"><i class="fa fa-shopping-cart"></i> Siparişler</a>
          </li>

            <li class="{{request()->url() == route('admin.category.index') ? 'active': ''}}">
                <a href="{{route('admin.category.index')}}"><i class="fa fa-clipboard"></i> Kategoriler</a>
            </li>

            <li class="{{request()->url() == route('admin.cargo.index') ? 'active': ''}}">
                <a href="{{route('admin.cargo.index')}}"><i class="fa fa-truck"></i> Kargo Firmaları</a>
            </li>
            <li class="{{request()->url() == route('admin.comment.index') ? 'active': ''}}">
                <a href="{{route('admin.comment.index')}}"><i class="fa fa-comment"></i> Yorumlar</a>
            </li>
          <li class="{{request()->url() == route('admin.basket.index') ? 'active': ''}}">
            <a href="{{route('admin.basket.index')}}"><i class="fa fa-shopping-bag"></i> Sepetler</a>
          </li>

            <li class="{{request()->url() == route('admin.wishlist.index') ? 'active': ''}}">
                <a href="{{route('admin.wishlist.index')}}"><i class="fa fa-heart"></i> Favoriler</a>
            </li>

          <li class="{{request()->url() == route('admin.support.index') ? 'active': ''}}">
            <a href="{{route('admin.support.index')}}"><i class="fa fa-ticket"></i> İletişim Mesajları</a>
          </li>
            <li class="{{request()->url() == route('admin.page.index') ? 'active': ''}}">
                <a href="{{route('admin.page.index')}}"><i class="fa fa-file"></i> Sayfalar</a>
            </li>
          <li class="{{request()->url() == route('admin.setting.index') ? 'active': ''}}">
            <a href="{{route('admin.setting.index')}}"><i class="fa fa-cog"></i> Ayarlar</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="page-wrapper">
    <div class="content container-fluid">
      @yield('content')
    </div>
  </div>
</div>

@if(session()->has('success_message'))
    <div class="notification-popup success">
        <p>
            <span class="notification-text"> {{ session()->get('success_message') }}</span>
        </p>
    </div>
@endif
@if(session()->has('error_message'))
    <div class="notification-popup error">
        <p>
            <span class="notification-text">{{\Illuminate\Support\Facades\Session::get("error_message")}} </span>
        </p>
    </div>
    message("error", '{{ session()->get('error_message') }}')
@endif

<div class="sidebar-overlay" data-reff=""></div>
<script type="text/javascript" src="{{ asset('preadmin/js/jquery-3.2.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('preadmin/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('preadmin/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('preadmin/js/jquery.slimscroll.js') }}"></script>
<script type="text/javascript" src="{{ asset('preadmin/js/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('preadmin/js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('preadmin/js/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('preadmin/plugins/morris/morris.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('preadmin/plugins/raphael/raphael-min.js') }}"></script>
<script type="text/javascript" src="{{ asset('preadmin/plugins/summernote/dist/summernote-bs4.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('preadmin/js/app.js') }}"></script>
<script>
  var csrf_token= '{{csrf_token()}}';

  setTimeout(function() {
      $('.notification-popup').fadeOut();
  }, 1000);

  $('.product-image-remove').click(function () {
      var id = $(this).data('id');
      $("#"+id).val("removed");
      $(this).closest('.product-image-wrapper').hide();

  });

  $('.title').keyup(function () {
    var title = $(this).val();
    var id = 0;
    var forWhat = "product";

    if($('#productId').length>0) {
        id = $('#productId').val();
    }

      if($('#forWhat').length>0) {
          forWhat = $('#forWhat').val();
      }

    $.ajax({
      url: '{{route('admin.urlGenerator')}}',
      data: {_token: csrf_token, title: title, id:id, forWhat: forWhat},
      type: 'POST',
      dataType: 'JSON',
      success: function (data) {
        if(data.success) {
          $('.url').val(data.url);
        }
      },
    });
  });

  $('.calculate').keyup(function () {
      var rate = $('#discount_rate').val();
      var price = $('#price').val();

      $.ajax({
          url: '{{route('admin.product.calculate')}}',
          data: {_token: csrf_token, price: price, rate:rate},
          type: 'POST',
          dataType: 'JSON',
          success: function (data) {
              if(data.success) {
                  $('.discount_price').val(data.discount_price);
              }
          },
      });
  });

  $('.city').on('change',function () {
    var city = $(this).val();
    $.ajax({
      url: '{{url('sehir/town')}}',
      data: {city:city, _token:csrf_token},
      type: 'POST',
      success: function (data) {
        $('.town').html('');
        var newOption = new Option('Seç', '', false, false);
        $('.town').append(newOption);
        if(data.towns) {
          var towns = JSON.parse(data.towns);
          $.each(towns, function(k, v) {
            var newOption = new Option(v.name, v.id, false, false);
            $('.town').append(newOption);
          });
        }
      },
    });
  });
</script>
</body>

</html>
