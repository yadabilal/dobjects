<?php

namespace App\Model;

use Illuminate\Support\Facades\Auth;

class UserPermission extends Base
{
  protected $table = 'user_permissions';
  protected $fillable = [ 'uuid', 'permission_id', 'user_id', 'is_allow'];
  
  public static function my_list() {
    $permissions = Auth::user()->permissions()->get();
    $datas = [];
    foreach ($permissions as $permission) {
      $datas[$permission->permission_id] = $permission->is_allow;
    }
    return $datas;
  }
  public static function by_permission_id($permission_id) {
    return self::where('permission_id', $permission_id)->where('user_id', Auth::id())->first();
  }
}
