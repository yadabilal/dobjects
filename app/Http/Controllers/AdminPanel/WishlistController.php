<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Model\Wishlist;

class WishlistController extends Controller
{
  public $view = 'admin.wishlist';
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $models = Wishlist::orderBy('id', 'desc')
      ->with('user', 'product')
      ->paginate(Wishlist::PAGINATION_LIST_ADMIN);
    return view($this->view.'.index', compact('models'));
  }

}
