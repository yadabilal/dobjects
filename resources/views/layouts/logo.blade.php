<a href="{{url('/mainpage')}}" class="{{@$linkClass ? : 'logo'}}">
    @if(@$settings['logo'])
        <img src="{{ url('uploads/'.$settings['logo']) }}" title="{{@$settings['meta_title'] ?: 'Deek Objects | Tasarım Ürünleri'}}" alt="{{@$settings['meta_title'] ?: 'Deek Objects | Tasarım Ürünleri'}}" />
    @else
        {{@$settings['title'] ?: 'Deek Objects'}}
    @endif
</a>
