<?php

namespace App\Model;

use Illuminate\Support\Str;

class Page extends Base
{
 const STATUS_PUBLISH = 'publish';
 const STATUS_UNPUBLISH = 'unPublish';

  protected $table = 'pages';
  protected $fillable = [
    'url', 'title', 'status', 'detail'
  ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->status = $model->status ? :self::STATUS_UNPUBLISH;
        });

        self::updating(function($model){

        });
        self::saving(function($model){
            $model->url = $model->url ?: Str::slug($model->name);
        });
    }

    public function readableStatus() {
        return @self::statues()[$this->status] ?: $this->status;
    }

    public function readableStatusColor() {
        return @self::statusColors()[$this->status] ?: $this->status;
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
