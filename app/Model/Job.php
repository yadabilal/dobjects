<?php

namespace App\Model;

use Carbon\Carbon;

class Job extends Base
{
    const WAITING_PAYMENT_MINUTE = 10;

  const TYPE_SMS= 'SMS';
  const TYPE_EMAIL= 'EMAIL';
  const TYPE_WAITING_PAYMENT= 'WAITING_PAYMENT';

  const STATUS_WAITING = 'WAITING';
  const STATUS_PROCESSING = 'PROCESSING';
  const STATUS_COMPLETED = 'COMPLETED';

  public $timestamps = false;
  protected $table = 'jobs';
  protected $fillable = [
    'type','status', 'contact',
    'content', 'send_at', 'subject'
  ];

  protected static function boot()
  {
    parent::boot();
    self::creating(function($model){
      $model->status = self::STATUS_WAITING;
      $model->send_at = $model->send_at ? : Carbon::now();
    });
  }
}
