<?php

namespace App\Http\Controllers\MemberShipPanel;

use App\Http\Controllers\Controller;
use App\Model\Base;
use App\Model\Permission;
use App\Model\UserPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PermissionController extends Controller
{
  
  public function __construct()
  {
    $this->middleware('auth');
  }
 
  public function index()
  {
    $my_perissions = UserPermission::my_list();
    $all_permissions = Permission::all_list();
    
    return view('site.membership.permission.index', compact('my_perissions',  'all_permissions'));
  }
  
  public function edit(Request $request) {
    if($request->ajax()){
      $inputs = Base::js_xss($request);
      $permission = @$inputs['id'] ? Permission::by_uuid($inputs['id']) : null;
      $is_allow = @$inputs['is_allow'] ? 1 : 0;
      $data['success'] = true;
      $errors = [];
      
      if($permission) {
        $model = UserPermission::by_permission_id($permission->id) ? : null;
        
        if(!$model) {
          $model = new UserPermission();
          $model->user_id = Auth::id();
          $model->permission_id = $permission->id;
          $model->is_allow = $is_allow;
          $model->save();
        }else {
          $model->update(['is_allow' => $is_allow]);
        }
        
      }else {
        $errors['no_permission'] = 'Hatalı İşlem!';
      }
      
      if ($errors){
        $data['success'] = false;
        $data['errors'] = $errors;
      }
    
      return Response::json($data, 200);
    }
  }
}
