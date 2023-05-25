<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    public $view = 'admin.user';
    public function __construct()
    {
      $this->middleware('auth');
    }
    public function index()
    {
      $models = User::where('type', '!=',User::TYPE_ADMIN)->with('waitingOrders');
      if(@$_GET['name']) {
        $val = $_GET['name'];
        $models = $models->where('name', 'like', '%' . $val . '%');
        $models = $models->orWhere('surname', 'like', '%' . $val . '%');
        $models = $models->orWhere('phone', 'like', '%' . $val . '%');
        $models = $models->orWhere('username', 'like', '%' . $val . '%');
      }
      if(@$_GET['status']) {
        $val = $_GET['status'];
        $models = $models->where('status', $val);
      }

      if(@$_GET['order_by'] == 'waitingOrders_count') {
        $models = $models->withCount('waitingOrders')->orderBy('waiting_orders_count', @$_GET['dir'] ? : 'desc');
      }else {
          $models =$models->withCount('waitingOrders')->orderBy('id', 'desc');
      }

      $models = $models->paginate(User::LIST_ADMIN);

      return view($this->view.'.index', compact('models'));
    }

}
