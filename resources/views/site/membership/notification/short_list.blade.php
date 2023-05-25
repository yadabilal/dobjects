<div class="nav-drop-header">
  <span>Bildirimler</span>

</div>

<div class="nav-drop-body is-friend-requests">
  @if(!$count)
    @include('site.membership.layouts.no_result_soft')
  @endif
  @foreach($notifications as $notification)
      @if($notification->data['type'] == \App\Model\Notification::TYPE_ORDER_DEMAND)
        @include('site.membership.notification.short_type.demand_item', ['item' => $notification])
      @elseif($notification->data['type'] == \App\Model\Notification::TYPE_ORDER_REQUEST)
        @include('site.membership.notification.short_type.request_item', ['item' => $notification])
      @else
        @include('site.membership.notification.short_type.standart_item', ['item' => $notification])
      @endif
  @endforeach
</div>
@if($count>\App\Model\Notification::LIST_SHORT_COUNT)
<div class="nav-drop-footer">
  <a href="{{url('hesabim/bildirim')}}">Tümünü Gör</a>
</div>
@endif
