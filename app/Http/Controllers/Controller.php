<?php

namespace App\Http\Controllers;

use App\Model\Basket;
use App\Model\Book;
use App\Model\File;
use App\Model\HomePage;
use App\Model\Page;
use App\Model\Product;
use App\Model\Setting;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
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
      }else {
          $cartItemCount = Session::get('basket.totalQuantity') ?:0;
      }

      $pages = Page::orderBy('sorting')->pluck('title', 'url');
      $clickedPopup = request()->get('clickedPopup');
      $popup = HomePage::where('type', HomePage::TYPE_8)
          ->where('status', HomePage::STATUS_PUBLISH)
          ->where('url', '!=', $clickedPopup)
          ->orderBy('sorting', 'desc')
          ->orderBy('id', 'desc')
          ->first();

      $popupId = md5($popup->sorting.$popup->id);
      $shownPopupId = "";
      if($popupId == $clickedPopup) {
          $shownPopupId = 'popup-shown-'.md5($popup->sorting.$popup->id);
      }

      $settings = Setting::pluck('value', 'param');
      $this->setting = $settings;

      View::share(['user' => $this->user, 'settings' => $settings,
          'pages' => $pages, 'wishListCount' => $wishListCount,
          'shownPopupId' => $shownPopupId,
          'popupId' => $popupId,
          'cartItemCount' => $cartItemCount, 'popup' => $popup]);
      return parent::callAction($method, $parameters);
    }

    public function errorPage() {
        return view('site.error');
    }
}
