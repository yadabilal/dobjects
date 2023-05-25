<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Base
{
    use SoftDeletes;
  protected $table = 'addresses';
  protected $fillable = [ 'uuid', 'user_id', 'city_id', 'town_id', 'address',
      "name", "surname", "phone", "email", "billing_note", "note"];


  public function fullDetail() {
      return $this->address.' <br>'.$this->city->name.','.$this->town->name;
  }

  public function city() {
    return $this->belongsTo(City::class, 'city_id');
  }
  public function town() {
    return $this->belongsTo(Town::class, 'town_id');
  }
  public function user() {
    return $this->belongsTo(User::class, 'user_id');
  }

}
