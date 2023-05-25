<a href="{{url('/')}}" class="{{@$linkClass ? : 'logo'}}">
    @if(@$settings['logo'])
        <img width="400" height="79" src="{{ url('uploads/'.$settings['logo']) }}" alt="Deek Objects" />
    @else
        {{@$settings['title'] ?: 'Deek Objects'}}
    @endif
</a>
