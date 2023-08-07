<?php

namespace App\Http\Controllers\MemberShipPanel;

use App\Http\Controllers\Controller;
use App\Model\Facebook;
use App\Model\Product;
use App\Model\Wishlist;
use Illuminate\Support\Facades\Session;

class WishlistController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index() {
    $user = $this->user;
    $items = $user->wishlists()->paginate(Wishlist::PAGINATION_LIST, ['*'], 'sayfa');

    return view('site.membership.wishlist.index', compact('items', 'user'));
  }

  public function add($uuid) {
      $product = Product::where('uuid', $uuid)
          ->where('status', Product::STATUS_PUBLISH)
          ->first();

      if($product) {
          $item = Wishlist::where('product_id', $product->id)
              ->where('user_id', $this->user->id)
              ->first();

          if(!$item) {

              $item = Wishlist::add($product);
              if($item) {
                  try {
                      $contents = [];
                      $content['id'] = $item->id;
                      $content['item_name'] = $item->product->name;
                      $content['item_price'] = $item->product->discount_price;
                      $contents[] = $content;

                      $facebook = new Facebook();
                      $facebook->event = Facebook::EVENT_WISHLIST;
                      $facebook->sourceUrl = request()->url();
                      $facebook->user = $this->user;
                      $facebook->customData['contents'] = $contents;
                      $result = $facebook->events($this->setting);
                  }catch (\Exception $e) {}

                  Session::flash('success_message', 'Ürün favorilerinize eklendi!');
              }else {
                  Session::flash('error_message', 'Beklenmedik bir hata meydana geldi!');
              }
          }else {
              Session::flash('success_message', 'Bu ürün zaten favorilerinizde!');
          }
      }else {
          Session::flash('error_message', 'Beklenmedik bir hata meydana geldi!');
      }

      return redirect()->back();
  }


  public function delete($uuid) {
        $product = Product::where('uuid', $uuid)
            ->where('status', Product::STATUS_PUBLISH)
            ->first();

        if($product) {
            $item = Wishlist::where('product_id', $product->id)
                ->where('user_id', $this->user->id)
                ->first();

            if($item && $item->can_delete()) {
                if($item->delete()) {
                    Session::flash('success_message', 'Ürün favorilerinizden çıkarıldı!');
                }else {
                    Session::flash('error_message', 'Beklenmedik bir hata meydana geldi!');
                }
            }else {
                Session::flash('error_message', 'Geçersiz İşlem!');
            }
        }else {
            Session::flash('error_message', 'Beklenmedik bir hata meydana geldi!');
        }

      return redirect()->back();
    }
}
