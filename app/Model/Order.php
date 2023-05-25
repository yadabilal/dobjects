<?php

namespace App\Model;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Order extends Base
{
  use SoftDeletes;

  const STATUS_NEW = 'NEW';
  const STATUS_PROCCESS = 'PROCCESS';
  const STATUS_CANCEL = 'CANCEL';
  const STATUS_CARGO = 'CARGO';
  const STATUS_COMPLETED = 'COMPLETED';

  //
  const PAGINATION_LIST = 10;
    public $store_path = 'orders';
  protected $table = 'orders';

  protected $fillable = [
    'uuid',  'status', 'number',
    'user_id', 'address_id',
    'total_quantity', 'total_price',
      'total_discount_price', 'discount_price',
      'cargo_id','folow_number', 'extra_messages'
  ];


    public function items() {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

  // Kullanıcı
  public function user() {
    return $this->belongsTo(User::class, 'user_id');
  }

  // Receiver
  public function address() {
    return $this->belongsTo(Address::class, 'address_id')->withTrashed();
  }

  public function cargo() {
    return $this->belongsTo(CargoCompany::class, 'cargo_id')->withTrashed();
  }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items');
    }

  public function cargo_url() {
      return $this->cargo->folow_url;
  }

    public function readableTotalDiscountPrice() {
        return self::decimalFormat($this->total_discount_price).self::currency();
    }

    public function status($for_admin = false) {
      return @self::status_list($for_admin)[$this->status] ?: '';
    }
    public function status_color($for_admin = false) {
        return @self::color_list($for_admin)[$this->status] ?: '';
    }


  protected static function boot(){
    parent::boot();

    static::creating(function ($model) {
      $model->status = self::STATUS_NEW;
      $model->user_id = \auth()->id();
    });

    static::created(function ($model) {
      //Bildirim Notification::create_order_create($model);

      /* Status Log
      $status_log['after_status'] = self::STATUS_NEW;
      $status_log['order_id'] = $model->id;
      $status_log['user_id'] = $user->id;
      OrderStatusLog::create($status_log); */

      // Sms Prodda düzelt
      //Sms::new_order($model);
    });
    static::updating(function ($model) {

    });
  }

  /*
   * Siparişe ait
   * Durum Bilgilerini
   * Listeler
   */
  public static function status_list($for_admin = false) {

      if($for_admin) {
          return [
              self::STATUS_NEW => 'Yeni',
              self::STATUS_PROCCESS => 'Hazırlanıyor',
              self::STATUS_CARGO => 'Kargolandı',
              self::STATUS_CANCEL => 'İptal Edildi',
              self::STATUS_COMPLETED => 'Teslim Edildi',
          ];
      }

    return [
      self::STATUS_NEW => 'İşleme Alındı',
      self::STATUS_PROCCESS => 'Hazırlanıyor',
      self::STATUS_CARGO => 'Kargolandı',
      self::STATUS_CANCEL => 'İptal Edildi',
      self::STATUS_COMPLETED => 'Teslim Edildi',
    ];
  }
  /*
   * Siparişe Ait Renk
   * Listesini Döner
   */
  public static function color_list() {
    return [
      self::STATUS_NEW => 'badge badge-info',
      self::STATUS_PROCCESS => 'badge badge-warning',
      self::STATUS_CARGO => 'badge badge-secondary',
      self::STATUS_CANCEL => 'badge badge-danger',
      self::STATUS_COMPLETED => 'badge badge-success',
    ];
  }

}
