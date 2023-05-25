<?php

namespace App\Model;

use App\User;

class Support extends Base
{
  const STATUS_NEW= 'NEW';
  const PAGINATION_LIST_ADMIN = 10;
  
  protected $table = 'supports';
  protected $fillable = [ 'uuid', 'user_id', 'name', 'surname', 'subject', 'email', 'detail', 'status'];
  
  public function user() {
    return $this->belongsTo(User::class, 'user_id');
  }
  
  protected static function boot()
  {
    parent::boot();
    static::creating(function ($model) {
      $model->status = Support::STATUS_NEW;
    });
  }
}
