<?php

namespace App\Model;


use App\Notifications\TaskComplete;

class Notification extends Base
{
  const LIST_SHORT_COUNT = 3;

  //Modal Name
  const MODAL_CLASS_DEMAND ='demand-modal';
  const MODAL_CLASS_REQUEST ='request-modal';
  const MODAL_CLASS_DEFAULT ='notification-modal';

  // Types
  const TYPE_STANDART = 'STANDART';
  const TYPE_BALANCE = 'BALANCE';
  const TYPE_ORDER_DEMAND = 'ORDER_DEMAND';
  const TYPE_ORDER_REQUEST = 'ORDER_REQUEST';

  protected $table = 'notifications';


  public static function show_url($item) {
    return route('notification.show', $item->id);
  }
  public static function type($data) {
    if(@$data['type']== self::TYPE_ORDER_DEMAND || @$data['type']== self::TYPE_ORDER_REQUEST) {
      return Order::with('receiver', 'book')->find($data['id']);
    }
    return null;
  }

  public static function my_list() {
    return auth()->user()->notifications()->paginate(self::PAGINATION_MY_LIST, ['*'], 'sayfa');
  }
  public static function more_url($items) {
    return url('hesabim/bildirim/daha-fazla').'?'.$items->getPageName().'=';
  }
  /*
   * Kitap İstendiğinde
   * Göndericiye Gönderilen Bildirim
   */
  public static function create_order_create($order){
    $task_complete= [
      'type' => self::TYPE_ORDER_DEMAND,
      'status' => Order::STATUS_NEW,
      'id' => $order->id,
      'title' => $order->receiver->full_name().' senden kitap istiyor.',
      'description' => $order->receiver->full_name().' senden '.$order->book->full_name().' kitabını istedi. En geç '.$order->last_send_at().' tarihinde kargola!',
      'url' => '',
    ];
    $order->sender->notify(new TaskComplete($task_complete));
  }
  /*
   * Kitap İstendiğinde
   * Alıcıya Gönderilen Kullanılan Bakiye Bildirimi
   */
  public static function create_order_new_balance($user, $now, $order_count){
    $diff = $now-$order_count;

    $description = $diff ?
      'Harcamaların sonrası kullanabileceğin '.$diff.' kBakiyen kaldı.'
      : 'Harcamaların sonra kullanabileceğin kBakiye kalmadı. kBakiye kazanmak için bol bol kitap yolla!';
    $task_complete= [
      'type' => self::TYPE_STANDART,
      'id' => '',
      'title' => $order_count.' tane kitap istedin.',
      'description' => 'Yeni kitaplar isteyerek toplam '.$order_count.' kBakiyeni kullandın. '.$description,
      'url' => '',
    ];
    $user->notify(new TaskComplete($task_complete));
  }
  /*
   * Kitap kargolandığında
   * Alıcıya Gönderilir
   */
  public static function create_order_cargo($order) {
    $task_complete= [
      'type' => self::TYPE_ORDER_REQUEST,
      'id' => $order->id,
      'status' => Order::STATUS_CARGO,
      'title' => $order->book->name.' adlı kitabın kargolandı.',
      'description' => $order->sender->full_name(). ', istediğin kitabı '.$order->cargo->name.' ile gönderdiğini bildirdi.',
      'url' => '',
    ];
    $order->receiver->notify(new TaskComplete($task_complete));
  }
  /*
   * Kitap alıcının eline ulaştığında
   * Göndericiye Gönderilir
   */
    public static function create_order_completed($order) {
      $task_complete= [
        'type' => self::TYPE_ORDER_DEMAND,
        'id' => $order->id,
        'status' => Order::STATUS_COMPLETED,
        'title' => $order->book->name.' adlı kitabın alıcıya ulaştı.',
        'description' => $order->receiver->full_name(). ' kullanıcısına gönderdiğin '.$order->book->name.' adlı kitabın eline ulaştığını bildirdi. Kitabının raflarda tozlanmasına müsade etmediğin için teşekkür ederiz.',
        'url' => '',
      ];
      $order->sender->notify(new TaskComplete($task_complete));

      $task_complete= [
        'type' => self::TYPE_ORDER_DEMAND,
        'id' => $order->id,
        'status' => self::TYPE_BALANCE,
        'title' => ' Hesabına 1 kBakiye yüklendi.',
        'description' => $order->book->name.' adlı kitap '.$order->receiver->full_name().' kullanıcısına ulaştı. Sen de 1 kBakiye kazandın. Kitaplarını isteyen kullanıcılara gönder kBakiye kazan, kazandıkça doya doya kitap oku!',
        'url' => '',
      ];
      $order->sender->notify(new TaskComplete($task_complete));

    }
    /*
     * Kitap alıcının eline ulaşmadıysa
     * Göndericiye bildirim yolla
     */
    public static function create_order_not_completed($order) {
      $task_complete= [
        'type' => self::TYPE_ORDER_DEMAND,
        'id' => $order->id,
        'status' => Order::STATUS_NOT_COMPLETED,
        'title' => $order->book->name.' adlı kitabın alıcıya ulaşmadı!',
        'description' => $order->receiver->full_name(). ' gönderdiğin '.$order->book->name.' adlı kitabın eline ulaşmadığını bildirdi. Birkitapbul.com ekibi konu ile ilgili inceleme başlattı.',
        'url' => '',
      ];
      $order->sender->notify(new TaskComplete($task_complete));
    }
    /*
     * Kitap isteği iptal edilmişse
     * Alıcıya bildirim gider.
     */
    public static function create_order_cancel($order) {
      $task_complete= [
        'type' => self::TYPE_ORDER_REQUEST,
        'id' => $order->id,
        'status' => Order::STATUS_CANCEL,
        'title' => $order->book->name.' adlı kitap isteğin iptal edildi.',
        'description' => $order->sender->full_name(). ', istediğin '.$order->book->name.' kitabını gönderemeyeceğini bildirdi. Üzülme! Aradığın kitabı başka kullanıcılardan bulabilirsin. Hemen, yeni kitaplar bul!',
        'url' => '',
      ];
      $order->receiver->notify(new TaskComplete($task_complete));
    }

    public static function balance_monthly($user, $balance) {
      $task_complete= [
        'type' => self::TYPE_BALANCE,
        'id' => $balance->id,
        'title' => 'Aylık kitap isteme kBakiyen yüklendi.',
        'description' => 'Sen doya doya  kitap oku diye bu aya ait '.$balance->amount.' kitap isteme kBakiyen hesabına tanımlandı :)',
        'url' => '',
      ];
      $user->notify(new TaskComplete($task_complete));
    }

    public static function balance_order_cancel($order, $balance) {
      $task_complete= [
        'type' => self::TYPE_BALANCE,
        'id' => $balance->id,
        'title' => 'Bakiyen geri yüklendi.',
        'description' => $order->sender->full_name().' istediğin '.$order->book->name.' kitabını iptal ettiği için kBakiyen hesabına geri yüklendi.',
        'url' => '',
      ];
      $order->receiver->notify(new TaskComplete($task_complete));
    }
}
