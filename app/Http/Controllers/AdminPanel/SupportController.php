<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Model\Support;

class SupportController extends Controller
{
  public $view = 'admin.support';
  public function __construct()
  {
    $this->middleware('auth');
  }
 
  public function index()
  {
    $models = Support::orderBy('id', 'desc')
      ->paginate(Support::PAGINATION_LIST_ADMIN);
    
    return view($this->view.'.index', compact('models'));
  }
}
