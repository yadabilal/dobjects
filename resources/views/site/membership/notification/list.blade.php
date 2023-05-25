@foreach($items as $item)
  @if($item->data['type'] == \App\Model\Notification::TYPE_ORDER_DEMAND)
    @include('site.membership.notification.type.demand_item', ['item' => $item])
  @elseif($item->data['type'] == \App\Model\Notification::TYPE_ORDER_REQUEST)
    @include('site.membership.notification.type.request_item', ['item' => $item])
  @else
    @include('site.membership.notification.type.standart_item', ['item' => $item])
  @endif
@endforeach
@if(method_exists($items, 'currentPage') && $items->currentPage()==1 && $items->currentPage()<$items->lastPage())
  <div class="load-more-wrap has-text-centered">
    <a class="load-more-button loading"
        data-item=".notification-full-item"
        data-url="{{\App\Model\Notification::more_url($items)}}"
        data-last-page="{{$items->lastPage()}}"
        data-page="{{$items->currentPage()+1}}"
        data-page-name="{{$items->getPageName()}}"
    >Daha Fazla</a>
  </div>
@endif
