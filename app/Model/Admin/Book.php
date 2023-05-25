<?php

namespace App\Model\Admin;


class Book extends \App\Model\Book
{
  const PAGINATION_LIST_ADMIN =10;
  
  public function childs() {
    return $this->hasMany(self::class, 'parent_id')->withTrashed();
  }
  
  public function status() {
    if($this->deleted_at) {
      return 'Silinmiş';
    } else if($this->parent_id) {
      return 'Ücretsiz Sahip Oldu';
    }
    
    return 'Kendi Ekledi';
  }
}
