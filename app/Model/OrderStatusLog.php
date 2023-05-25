<?php

namespace App\Model;

class OrderStatusLog extends Base
{
  protected $table = 'order_status_logs';
  protected $fillable = [ 'uuid', 'order_id', 'user_id', 'before_status', 'after_status', 'note'];
  
  public function order() {
    return $this->belongsTo(Order::class, 'order_id');
  }
  
  /*
 * Sipariş Durumunun
 * Renk Bilgisini Döndürür
 */
  public function color() {
    return @Order::color_list()[$this->after_status] ? : 'gray';
  }
  public function status_detail() {
    if($this->after_status == Order::STATUS_NEW) {
      return 'Kitabı istedin.';
    }else if($this->after_status == Order::STATUS_CARGO) {
      return 'Kargolandı.';
    }else if($this->after_status == Order::STATUS_CANCEL) {
      return 'İptal edildi';
    }else if($this->after_status == Order::STATUS_COMPLETED) {
      return 'Kitaplığında.';
    }else if($this->after_status == Order::STATUS_NOT_COMPLETED) {
      return 'Eline ulaşmadı.';
    }
    
    return '';
  }
}
