<?php

namespace App\Model;

class HomePage extends Base
{
    const TYPE_1 = 1;
    const TYPE_2 = 2;
    const TYPE_3 = 3;
    const TYPE_4 = 4;
    const TYPE_5 = 5;
    const TYPE_6 = 6;
    const TYPE_7 = 7;

    const STATUS_PUBLISH = 'publish';
    const STATUS_NOT_PUBLISH = 'notpublish';

  protected $table = 'home_pages';
  public $timestamps = false;
  protected $fillable = [ 'title', 'sub_title', 'url', 'file', 'status', 'type', 'sorting'];

  public function getPic() {
      return @$this->file ?: '';
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
            self::STATUS_NOT_PUBLISH => 'Yayında Değil',
        ];
    }

    public static function statusColors() {
        return [
            self::STATUS_PUBLISH => 'badge badge-success',
            self::STATUS_NOT_PUBLISH => 'badge badge-danger',
        ];
    }

}
