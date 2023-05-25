<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Model\Search;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
  public $view = 'admin.search';
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {

    $models = Search::orderBy('id', 'desc')
      ->with('category', 'city', 'town')
      ->paginate(Search::PAGINATION_LIST_ADMIN);
    return view($this->view.'.index', compact('models'));
  }

}
