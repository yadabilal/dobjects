<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Base
{
    use SoftDeletes;

    const TYPE_BILLING = 'billing';
    const TYPE_SHIPPING = 'shipping';
    const BILLING_TYPE_PERSONAL = 'personal';
    const BILLING_TYPE_COMPANY = 'company';

  protected $table = 'addresses';
  protected $fillable = [ 'uuid', 'user_id', 'city_id', 'town_id', 'address',
      "name", "surname", "phone", "email", "billing_note", "note", "type", 'identity_number',
      'billing_type', 'session_id'];


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

  public function fullName() {
      return $this->name.' '.$this->surname;
  }

  public function billingTypeReadable() {
      return @self::billingType()[$this->billing_type] ?: '-';
  }

  public static function billingType() {
      return [
          self::BILLING_TYPE_PERSONAL => 'Bireysel',
          self::BILLING_TYPE_COMPANY => 'Kurumsal',
      ];
  }

}
