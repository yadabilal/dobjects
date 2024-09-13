<script src="{{ asset('theme/deekobjects/libs/popper/js/popper.min.js') }}"></script>
<script src="{{ asset('theme/deekobjects/libs/jquery/js/jquery.min.js')}}"></script>
<script async src="{{ asset('theme/deekobjects/libs/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('theme/deekobjects/libs/slick/js/slick.min.js')}}"></script>
<script src="{{ asset('theme/deekobjects/libs/countdown/js/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('theme/deekobjects/libs/mmenu/js/jquery.mmenu.all.min.js')}}"></script>
<script src="{{ asset('theme/deekobjects/libs/slider/js/tmpl.js') }}"></script>
<script src="{{ asset('theme/deekobjects/libs/slider/js/jquery.dependClass-0.1.js')}}"></script>
<script src="{{ asset('theme/deekobjects/libs/slider/js/draggable-0.1.js') }}"></script>
<script src="{{ asset('theme/deekobjects/libs/slider/js/jquery.slider.js') }}"></script>

<script rel="preload" src="{{ asset('theme/deekobjects/js/app.min.js') }}"></script>
<script src="{{ asset('theme/deekobjects/libs/elevatezoom/js/jquery.elevatezoom.min.js') }}"></script>

<script>
    var popupShownId= '{{$popupId ? : ''}}';
    @if($shownPopupId)
        localStorage.setItem(popupShownId, 'true');
    @endif

</script>
<script src="{{ asset('theme/deekobjects/js/btn-proccess.js?'.\Carbon\Carbon::now()->getTimestamp()) }}"></script>
{!! @$settings['extraJs'] !!}
@stack('page-scripts')

<script>
    var homeUrl = '{{route('home')}}';
    var basketAddUrl = '{{auth()->id() ? route('basket.add'): route('tempbasket.add')}}';
    var basketList = '{{auth()->id() ? route('basket.list'): route('tempbasket.list')}}';
    var basketDelete = '{{auth()->id() ? route('basket.delete'): route('tempbasket.delete')}}';
    var cityChangeUrl = '{{url('ilce-bul')}}';
    var billingCompanyType = '{{\App\Model\Address::BILLING_TYPE_COMPANY}}';

  @if(session()->has('success_message'))
      message("success", '{{ session()->get('success_message') }}')
  @endif
  @if(session()->has('error_message'))
    message("error", '{{ session()->get('error_message') }}')
  @endif
</script>
