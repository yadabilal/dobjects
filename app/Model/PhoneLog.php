<?php

namespace App\Model;

class PhoneLog extends Base
{
  protected $table = 'phone_logs';
  protected $fillable = [ 'uuid', 'phone', 'user_id', 'phone_verified_at'];
  
  
}
