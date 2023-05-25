<?php

namespace App\Http\Controllers\MemberShipPanel;

use App\Http\Controllers\Controller;
use App\Model\Basket;
use App\Model\Notification;
use App\Model\Order;
use Illuminate\Support\Facades\Response;

class NotificationController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    $items = Notification::my_list();
    return view('site.membership.notification.index', compact('items'));
  }
  public function list()
  {
    $items  = Notification::my_list();
    return view('site.membership.notification.list', compact('items'));
  }
  // Bildirim Listele
  public function short_list()
  {
    $data['success'] = false;
    if(request()->ajax()) {
      $data['success'] = true;
      $notifations = auth()->user()->unreadNotifications()->take(Notification::LIST_SHORT_COUNT)->get();
      $notifation_total = auth()->user()->unreadNotifications()->count();
      $data['list'] = view('site.membership.notification.short_list',['notifications' => $notifations ? : [], 'count' => $notifation_total])->render();
    }else {
      $data['error'] = 'Geçersiz istek.';
    }
    return Response::json($data, 200);
  }

  // Bildirim Listele
  public function show($uuid)
  {
    $data['success'] = false;
    if(request()->ajax()) {
      $data['success'] = true;
      $notification = auth()->user()->notifications->where('id', $uuid)->first();
      if($notification) {
        if(!$notification->read_at) {
          $notification->markAsRead();
        }

        $data['modal'] = Notification::MODAL_CLASS_DEFAULT;
        $item['title'] = $notification->data['title'];
        $item['description'] = $notification->data['description'];
        $model = Notification::type($notification->data);
        if($model) {
          if($notification->data['type']==Notification::TYPE_ORDER_DEMAND && $model->can_shiping()) {
            $item['id'] = $model->uuid;
            $item['last_send_at'] = $model->last_send_at();
            $item['book'] = $model->book->name;
            $item['cargo'] = $model->cargo_id ? $model->cargo->name : '';
            $item['cargo_date'] = $model->log_cargo_at();
            $item['author'] = $model->book->author;
            $item['address'] = $model->address();
            $item['receiver'] = $model->receiver->full_name();
            $item['image'] = $model->book->image();
            $data['modal'] = Notification::MODAL_CLASS_DEMAND;
          }else if($notification->data['type']==Notification::TYPE_ORDER_REQUEST) {
            $result = $model->format_with_logs();
            $item = array_merge($item, $result['item']);
            $data['modal'] = Notification::MODAL_CLASS_REQUEST;
            $data['logs'] = $result['logs'];
          }
        }
        $data['item'] = $item;
        $data['has'] = auth()->user()->unreadNotifications()->first() ? 1 : 0;
      }else {
        $data['error'] = 'Geçersiz istek.';
      }
    }else {
      $data['error'] = 'Geçersiz istek.';
    }
    return Response::json($data, 200);
  }
}
