<a href="{{url('/')}}" class="{{@$linkClass ? : 'logo'}}">
    @if(@$settings['logo'])
        <img src="{{ url('uploads/'.$settings['logo']) }}" alt="Deek Objects | Tasarım Ürünleri" />
    @else
        {{@$settings['title'] ?: 'Deek Objects'}}
    @endif
</a>
