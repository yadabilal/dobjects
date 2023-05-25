<?php

namespace App\Model;

use App\User;
use Illuminate\Support\Facades\Auth;

class Balance extends Base
{
  //use SoftDeletes;
  const PAGINATION_LIST =5;
  const TYPE_MONTHLY = 'MONTHLY';
  const TYPE_ORDER_BUY = 'ORDER_BUY';
  const TYPE_ORDER_SELL = 'ORDER_SELL';
  const TYPE_ORDER_BUY_CANCEL = 'ORDER_BUY_CANCEL';
  const TYPE_ORDER_BUY_COMPLETED = 'ORDER_BUY_COMPLETED';

  protected $table = 'balances';
  protected $fillable = [
    'uuid', 'user_id', 'order_id',
    'title','description', 'amount',
    'before_balance',
    'after_balance',
    'type'
  ];

  // User
  public function user() {
    return $this->belongsTo(User::class, 'user_id');
  }
  // Order
  public function order() {
    return $this->belongsTo(Order::class, 'order_id');
  }
  // List
  public static function my_list() {
    return Auth::user()->balances()->paginate(self::PAGINATION_LIST, ['*'], 'sayfa');
  }
  // More Url
  public static function more_url($items) {
    return url('hesabim/bakiye/daha-fazla').'?'.$items->getPageName().'=';
  }

  public static function monthly_title() {
    return 'Aylık Bakiye';
  }

  public static function monthly_description() {
    return 'Aylık '.User::DEFAULT_BALANCE.' bakiyen hesabına tanımlandı.';
  }

  public static function buy_title() {
    return 'Kitap Satın Alma';
  }
  public static function buy_description() {
    return 'Yeni kitap satın aldın.';
  }

  public static function order_cancel_title() {
    return 'Kitap İsteği İptal Edildi';
  }
  public static function order_cancel_description() {
    return 'İstediğin kitap iptal edildiği için bakiyen geri yüklendi.';
  }

  public static function order_completed_title() {
    return 'Gönderdiğin Kitap Alıcıya Ulaştı!';
  }
  public static function order_completed_description() {
    return 'Gönderdiğin kitap alıcıya ulaştığı için 1 bakiyen hesabına yüklendi.';
  }
}
