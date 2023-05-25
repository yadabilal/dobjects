<?php

namespace App\Model;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class OrderItem extends Base
{
  use SoftDeletes;

  const STATUS_NEW = 'NEW';
  const STATUS_PAYED = 'PAYED';
  const STATUS_CANCEL = 'CANCEL';
  const STATUS_CARGO = 'CARGO';
  const STATUS_COMPLETED = 'COMPLETED';


  protected $table = 'order_items';
  protected $fillable = [
    'uuid',  'status', 'order_id',
    'user_id', 'product_id', 'discount',
    'quantity', 'price', 'total_price',
      'total_discount_price', 'discount_price',
  ];

  // Sender
  public function user() {
    return $this->belongsTo(User::class, 'user_id');
  }

  // Receiver
  public function order() {
    return $this->belongsTo(Order::class, 'order_id')->withTrashed();
  }

  public function product() {
    return $this->belongsTo(Product::class, 'product_id')->withTrashed();
  }

    // İndirimde mi?
    public function isDiscount() {
        return (int)$this->discount ? true : false;
    }

    // Kullanıcı indirim durumu
    public function readableDisCountRate() {
        return self::decimalFormat($this->discount).self::currency();
    }
    public function readablePrice() {
        return self::decimalFormat($this->price).self::currency();
    }
    public function readableDiscountPrice() {
        return self::decimalFormat($this->discount_price).self::currency();
    }
    public function readableTotalDiscountPrice() {
        return self::decimalFormat($this->total_discount_price).self::currency();
    }

  protected static function boot(){
    parent::boot();

    static::creating(function ($model) {
      $model->status = self::STATUS_NEW;
      $model->user_id = \auth()->id();
    });

    static::created(function ($model) {

    });
    static::updating(function ($model) {

    });
  }


}
