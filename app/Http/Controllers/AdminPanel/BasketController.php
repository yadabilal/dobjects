<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Model\Basket;

class BasketController extends Controller
{
  public $view = 'admin.basket';
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $models = Basket::orderBy('id', 'desc')
      ->with('user', 'product')
      ->paginate(Basket::PAGINATION_LIST_ADMIN);
    return view($this->view.'.index', compact('models'));
  }

}
