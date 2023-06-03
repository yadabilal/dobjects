<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Page;
use App\Model\Product;
use App\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public $view = 'admin';
    public function __construct()
    {
      $this->middleware('auth');
    }
    public function index()
    {
      return view($this->view.'.password');
    }

  public function save()
  {
    if(request()->post('password')) {
      $password = request()->post('password');
      $this->user->update(['password' => User::hash_pasword($password)]);
    }
    return redirect()->back();
  }

  public function urlGenerator() {
        $name = request()->post('title');
        $id = @request()->post('id') ?: null;
        $for = @request()->post('forWhat') ?: "product";
        $url = Str::slug($name);
        $idField = 'uuid';

        if($for == "product") {
            $product = Product::where('url', $url);
        }else if($for == 'contract'){
            $product = Page::where('url', $url);
            $idField = 'id';
        }else {
            $product = Category::where('url', $url);
        }

        if($id) {
            $product->where($idField, '!=', $id);
        }

      $product= $product->first();
        if($product) {
            $url = $url.'-'.$product->id;
        }

        $data['success'] = true;
        $data['url'] = $url;

        return Response::json($data);
  }
}
