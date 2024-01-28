<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Model\Subscribe;
use App\User;

class SubscribeController extends Controller
{
    public $view = 'admin.subscribe';
    public function __construct()
    {
      $this->middleware('auth');
    }
    public function index()
    {
      $models = Subscribe::orderBy('id', 'desc');
      $models = $models->paginate(User::LIST_ADMIN);

      return view($this->view.'.index', compact('models'));
    }

}
