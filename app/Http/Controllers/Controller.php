<?php

namespace App\Http\Controllers;

use App\Model\Book;
use App\Model\File;
use App\Model\Page;
use App\Model\Setting;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $user;
    public $setting;
    public $keywords = ['Mobilya', 'Tasarım ürünleri', 'Deek Mimarlık'];
    public function callAction($method, $parameters)
    {
      // Eğer Giriş Yapmışsa kullanıcının yapmadığı İşlere Yolla
      $this->user = \auth()->user();
        $wishListCount =  0;
        $cartItemCount =  0;

      if($this->user) {
        $midds = $this->getMiddleware();
        if(@$midds[0]['middleware']=='auth' && request()->is('*hesabim*')) {
          if(@$parameters[0] && @$parameters[0]->method() =='POST' && $this->user->status != User::STATUS_COMPLETED) {
            $data['success'] = false;
            $data['message'] = 'Bu işlemi yapabilmek için kayıt işlemini tamamlamalısın!';
            return Response::json($data, 200);
          }else if($this->user->status == User::STATUS_STEP_SECOND) {
            return redirect('kayit-ol/telefon-onayla');
          }else if($this->user->status == User::STATUS_STEP_THIRD) {
            return redirect('kayit-ol/kullanici-adi-belirle');
          }
        }

          $wishListCount = $this->user->wishlists->count();
          $cartItemCount = $this->user->baskets->sum('quantity');
      }

      $pages = Page::orderBy('sorting')->pluck('title', 'url');
      $settings = Setting::pluck('value', 'param');


      View::share(['user' => $this->user, 'settings' => $settings,
          'pages' => $pages, 'wishListCount' => $wishListCount,
          'cartItemCount' => $cartItemCount]);
      return parent::callAction($method, $parameters);
    }

    public function errorPage() {
        return view('site.error');
    }
}
