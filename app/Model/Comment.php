<?php

namespace App\Model;

use App\User;
use Carbon\Carbon;

class Comment extends Base
{
    const STATUS_PUBLISH = "PUBLISH";
    const STATUS_UNPUBLISH = "UNPUBLISH";

  protected $table = 'comments';
  protected $fillable = [ 'user_id', 'product_id', 'rate', 'review' ];


  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->user_id = $model->user_id ?: auth()->id();
      $model->status = $model->status ?: self::STATUS_UNPUBLISH;
      $model->created_at = Carbon::now();
    });

    static::updating(function($model) {
        $model->updated_at = Carbon::now();
    });
  }


  public function star() {
      return self::starClass($this->rate);
  }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function readableStatus() {
        return @self::statues()[$this->status] ?: $this->status;
    }

    public function readableStatusColor() {
        return @self::statusColors()[$this->status] ?: $this->status;
    }

    public static function starClass($rate) {
        return $rate ? "star-".$rate : "star-0";
    }

    public static function statues() {
        return [
            self::STATUS_PUBLISH => 'Yayında',
            self::STATUS_UNPUBLISH => 'Yayında Değil',
        ];
    }

    public static function statusColors() {
        return [
            self::STATUS_PUBLISH => 'badge badge-success',
            self::STATUS_UNPUBLISH => 'badge badge-danger',
        ];
    }
}
