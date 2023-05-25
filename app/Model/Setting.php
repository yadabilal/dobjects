<?php

namespace App\Model;

class Setting extends Base
{
  protected $table = 'setting';
  protected $fillable = [ 'param', 'value'];

  public $timestamps = false;

    const  STORE_PATH = 'settings';

  public static function by_key($key) {
    $item = self::where('param', $key)->first();

    return $item ? $item->value : '';
  }


  public static function by_key_item($key) {
    return self::where('param', $key)->first();
  }

  public static function default_list() {
    return[
      [
        'param' => 'phone',
        'value' => '+908505906812',
      ],
      [
        'param' => 'mobile_phone',
        'value' => '05346326393',
      ],
      [
        'param' => 'email',
        'value' => 'info@deekobjects.com',
      ],
      [
        'param' => 'address',
        'value' => 'Örnek Mahallesi Bestekar Amir Ateş Cad. No:32 D.2 Ataşehir/İstanbul',
      ],
    ];
  }
}
