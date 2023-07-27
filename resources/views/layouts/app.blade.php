<!DOCTYPE html>
<html lang="tr">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    @include('layouts.meta')
    <meta name="facebook-domain-verification" content="23clmbhq6deq3fihp601xly1pqcbdd" />
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1253011552026497');
        @yield('facebookAction')

    </script>
    <noscript>
        <img height="1" width="1"
             src="https://www.facebook.com/tr?id=1253011552026497&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
</head>

<body class="shop">
<div id="page" class="hfeed page-wrapper">
    @include('layouts.header')
    <div id="site-main" class="site-main">
        <div id="main-content" class="main-content">
            @yield('content')
        </div>
    </div>

    @include('layouts.footer')
</div>

@include('layouts.search')

@include('layouts.js')

</body>
</html>
