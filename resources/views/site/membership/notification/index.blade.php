@extends('layouts.user')
@section('meta')
  <title> Bir Kitap Bul | İkinci El Ücretsiz Kitap Platformu | Bildirimler</title>
  <meta name="keywords" content="">
  <meta name="description" content="" />
@endsection
@section('content')
<div class="stats-wrapper">
  <div class="quick-activity questions-settings">
    <div class="settings-header mb-0">
      <h2>Bildirimler</h2>
    </div>
    <div class="activity-list load-more-info"
    data-item=".notification-full-item"
    data-url="{{\App\Model\Notification::more_url($items)}}"
    data-last-page="{{$items->lastPage()}}"
    data-page="{{$items->currentPage()+1}}"
    data-page-name="{{$items->getPageName()}}"
    >
      @if(!$items->total())
        @include('site.membership.layouts.no_result')
      @else
        @include('site.membership.notification.list')
      @endif
    </div>
  </div>
</div>
@endsection
