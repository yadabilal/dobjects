<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
@yield('meta')

@if(@$settings['logo'])
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('uploads/'.$settings['logo']) }}">
@endif
<link rel="preload" as="style" onload="this.rel='stylesheet'"  href="{{ asset('theme/deekobjects/libs/bootstrap/css/bootstrap.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('theme/deekobjects/libs/feather-font/css/iconfont.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('theme/deekobjects/libs/icomoon-font/css/icomoon.css') }}" type="text/css">
<link rel="preload" as="style" onload="this.rel='stylesheet'"  href="{{ asset('theme/deekobjects/libs/font-awesome/css/font-awesome.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('theme/deekobjects/libs/wpbingofont/css/wpbingofont.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('theme/deekobjects/libs/elegant-icons/css/elegant.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('theme/deekobjects/libs/slick/css/slick.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('theme/deekobjects/libs/slick/css/slick-theme.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('theme/deekobjects/libs/mmenu/css/mmenu.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('theme/deekobjects/libs/slider/css/jslider.css') }}">

<link rel="stylesheet" href="{{ asset('theme/deekobjects/css/responsive.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('theme/deekobjects/css/extra.css?'.\Carbon\Carbon::now()->getTimestamp()) }}" type="text/css">
<link rel="stylesheet" href="{{ asset('theme/deekobjects/css/font-barlow.css') }}" >
<link rel="stylesheet" href="{{ asset('theme/deekobjects/css/font-garamond.css') }}">

<!-- <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@100;200;300;400;500;600;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=EB+Garamond:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&amp;display=swap" rel="stylesheet">
-->
<style>
    .page-title {
        background-image: url({{@$settings['breadcrumb'] ? url('uploads/'.$settings['breadcrumb']): asset('theme/deekobjects/media/site-header.jpg')}})!important;
    }
</style>
<script>
  var csrf_token= '{{csrf_token()}}';
</script>
