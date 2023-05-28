<div id="title" class="page-title" style="background-image: url({{@$settings['breadcrumb'] ? url('uploads/'.$settings['breadcrumb']): asset('theme/deekobjects/media/site-header.jpg')}})!important;">
    <div class="section-container">
        <div class="content-title-heading">
            <h1 class="text-title-heading">
                {{$title}}
            </h1>
        </div>
        <div class="breadcrumbs">
            <a href="{{route('home')}}">Anasayfa</a>
            <span class="delimiter"></span>{{$title}}
        </div>
    </div>
</div>
