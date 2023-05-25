<?php

namespace App\Model;

class Permission extends Base
{
  const TYPE_BOTH = 'BOTH';
  
  protected $table = 'permissions';
  protected $fillable = [ 'uuid', 'type', 'title', 'description'];
  
  public static function all_list() {
    return self::orderBy('id', 'asc')->get();
  }
  
  public static function default_list() {
    return [
      'Yeni Gelişmeler' => 'Yeni gelişmelerden haberdar olmak istiyorum.',
      'Yeni Kitap Ekleme' => 'Okuduğum türde yeni kitaplar eklendiğinde bildirim...',
    ];
  }
}
