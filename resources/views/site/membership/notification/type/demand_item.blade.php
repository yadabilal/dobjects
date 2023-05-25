<?php $model= \App\Model\Notification::type($item->data); ?>
@if($model)
<div class="activity-item notification-full-item notification-parent">
  <a class="btn-notification-show {{$item->read_at ? 'read' : ' not-read'}}" style="color: #393a4f;"
      data-action="{{\App\Model\Notification::show_url($item)}}"
  >
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart">
      <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
      </path>
    </svg>
  </a>
  <div class="meta">
    <span class="btn-notification-show"
        data-action="{{\App\Model\Notification::show_url($item)}}"
    >{{$item->data['title']}}</span>

    @if(@$item->data['status'] == \App\Model\Order::STATUS_NEW)
      <span>
      <a>{{$model->receiver->full_name()}}</a> senden
      <a>{{$model->book->full_name()}}</a>
      kitabını istedi. En geç <a>{{$model->last_send_at()}}</a> tarihinde kargola!
    </span>
    @elseif(@$item->data['status'] == \App\Model\Order::STATUS_COMPLETED)
      <span>
        <a>{{$model->book->author}}</a> yazarına ait
        <a>{{$model->book->name}}</a> adlı kitabın
        <a>{{$model->receiver->full_name()}}</a> kullanıcısına ulaştı.
        Kitabının raflarda tozlanmasına izin vermediğin için teşekkür ederiz.
        Haydi! Sen de <a href="{{route('home')}}">Bir Kitap Bul</a>
      </span>
    @elseif(@$item->data['status'] == \App\Model\Order::STATUS_NOT_COMPLETED)
      <span>
        Gönderdiğin <a>{{$model->book->author}}</a> yazarına ait
        <a>{{$model->book->name}}</a> adlı kitap
        <a>{{$model->receiver->full_name()}}</a> kullanıcısına ulaşmadı!
        Birkitapbul.com ekibi konuyla ilgili takip başlattı. Yakın zamanda birkitapbul.com ekibi tarafından aranabilirsin.
      </span>
    @else
      <span> {{$item->data['description']}}</span>
    @endif
    <small>{{\App\Model\Base::time_read($item->created_at)}}</small>
  </div>
</div>
@endif
