<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class CargoCompany extends Base
{
  use SoftDeletes;
  protected $table = 'cargo_companies';
  protected $fillable = [ 'uuid', 'name', 'order', 'description', 'folow_url'];

  public function image() {
    return '';
  }

  public static function all_list() {
    return self::orderBy('order', 'asc')->get();
  }
}
