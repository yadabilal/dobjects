<!DOCTYPE html>
<html lang="tr">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    @include('layouts.meta')
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
