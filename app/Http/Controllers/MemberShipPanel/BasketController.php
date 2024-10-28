<?php

namespace App\Http\Controllers\MemberShipPanel;

use App\Http\Controllers\Controller;
use App\Model\Base;
use App\Model\Basket;
use App\Model\Facebook;
use App\Model\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class BasketController extends Controller
{
  public function __construct()
  {
    //$this->middleware('auth');
  }

  // Sipariş Tamamla
  public function index() {
    $user = $this->user ?: null;
    $carts = Basket::getAll();
    $totalPrices = Basket::totals();
    $totalCount = Basket::sumQuantity();
    $totalDiscountPrice = Base::amountFormatterWithCurrency($totalPrices['totalFinallyPrice']);
    $totalPrice = Base::amountFormatterWithCurrency($totalPrices['totalPrice']);
    $discountPrice = Base::amountFormatterWithCurrency($totalPrices['totalDiscountPrice']);

    return view('site.membership.basket.index', compact('carts', 'user', 'totalCount', 'totalPrice', 'totalDiscountPrice', 'discountPrice'));
  }

  // Sepete Ekle
  public function add(\Illuminate\Http\Request $request)
  {
    $data['success'] = false;
    if($request->ajax() && $request->post('id')) {

        if($this->user && !$this->user->can_order()) {
            $data['mesage'] = "Lütfen profilinizi tamamlayın!";
        }else {
            $all = Base::js_xss($request);
            $product = Product::where('uuid', $all['id'])
                ->where('status', Product::STATUS_PUBLISH)
                ->first();

            if($product) {
                $max = $product->stock ?: Product::MAX_ORDER_COUNT;
                $quantity = @$all['quantity'] ?: 1;

                if($quantity > 0 && $quantity <= $max) {
                    $basket = Basket::add($product, @$all['quantity'] ?: 1, @$all['note'] ?: '');

                    if($basket) {
                        try {
                            $contents = [];
                            $baskets = Basket::getAll();
                            foreach ($baskets as $basket) {
                                $content['id'] = $basket->product->id;
                                $content['quantity'] = $basket->quantity;
                                $content['item_price'] = $basket->product->discount_price;
                                $contents[] = $content;
                            }

                            $facebook = new Facebook();
                            $facebook->event = Facebook::EVENT_BASKET;
                            $facebook->sourceUrl = $request->url();
                            $facebook->user = $this->user ?: 'quest_'.session_id();
                            $facebook->customData['contents'] = $contents;
                            $result = $facebook->events($this->setting);
                        }catch (\Exception $e) {}

                        $count = Basket::sumQuantity();

                        $data['success'] = true;
                        $data['id'] = $basket->uuid;
                        $data['name'] = $product->name;
                        $data['price'] = $product->discount_price;
                        $data['count'] = $count;
                        $data['message'] = "Ürün Sepete Eklendi!";
                    }else {
                        $data['message'] = "Geçersiz İşlem!";
                    }
                }else {
                    $data['message'] = "Aynı üründen en fazla ".$max.' adet sipariş verebilirsiniz!';
                }

            }else {
                $data['message'] = "Geçersiz İşlem!";
            }
        }
    }else {
        $data['message'] = "Geçersiz İşlem!";
    }

    return Response::json($data, 200);
  }

  // Sepetten Sil
  public function delete()
  {
    $data['success'] = false;
    $id = request()->post('id');

    if(request()->ajax() && $id) {
      $basket = Basket::by_uuid($id);
      if($basket && $basket->can_delete()) {
        DB::beginTransaction();
        $note = Basket::DELETE_REASON_OWN;
        $basket->update(['note' => $note]);
        $basket->delete();
        DB::commit();

          $count = Basket::sumQuantity()-$basket->quantity;
          $tdp = Basket::sumTotalDiscountPrice();
          $totalDiscountPrice = Base::amountFormatterWithCurrency($tdp);
          $tp = Basket::sumTotalPrice();
          $totalPrice = Base::amountFormatterWithCurrency($tp);
          $discountPrice = Base::amountFormatterWithCurrency($tp-$tdp);

          $data['success'] = true;
          $data['totalPrice'] = $totalPrice;
          $data['totalDiscountPrice'] = $totalDiscountPrice;
          $data['discountPrice'] = $discountPrice;
          $data['count'] = $count;
          $data['message'] = "Ürün sepetten çıkarıldı!";

      }else {
          $data['message'] = "Geçersiz İşlem!";
      }
    }else {
        $data['message'] = "Geçersiz İşlem!";
    }

    return Response::json($data, 200);
  }

  // Sepeti Güncelle
  public function update() {
      if (request()->isMethod('post')) {
          $inputs = Base::js_xss(\request());
          $carts = Basket::getAll();
          if(@$inputs['quantity'] && $carts) {
              $isError = false;
              foreach ($carts as $cart) {
                  $quantity = @$inputs['quantity'][$cart->uuid] ?: null;

                  if($quantity>0) {
                      $product = Product::where('id', $cart->product_id)
                          ->where('status', Product::STATUS_PUBLISH)
                          ->first();
                      $max = $product->stock ?: Product::MAX_ORDER_COUNT;
                      if($product) {
                          if($quantity <= $max) {
                              $cart->price = $product->price;
                              $cart->quantity = $quantity;
                              $cart->discount_price = $product->discount_price;
                              $cart->total_price = $product->price*$quantity;
                              $cart->total_discount_price = $product->discount_price*$quantity;
                              $cart->save();
                          }else {
                              $isError = true;
                              Session::flash('error_message', "Aynı üründen en fazla ".$max.' adet sipariş verebilirsiniz!');
                          }

                      }else {
                          $cart->delete();
                      }

                  }else {
                      $cart->delete();
                  }
              }

              if(!$isError) {
                  Session::flash('success_message', 'Sepet güncellendi!');
              }
              return redirect(route('basket.short_list'));
          }else {
              Session::flash('error_message', 'Geçersiz İşlem!');
              return redirect()->back();
          }
      }else {
          Session::flash('error_message', 'Geçersiz İşlem!');
          return redirect()->back();
      }
  }

  // Sepet Listele
  public function list()
  {
        $data['success'] = false;

        if(request()->ajax()) {
          $data['success'] = true;
          $carts = Basket::getAll();
          $totalPrices = Basket::totals();
          $count = Basket::sumQuantity();
          $totalPrice = Base::amountFormatterWithCurrency($totalPrices['totalFinallyPrice']);
          $data['list'] = view('site.membership.basket.short_list',compact('carts', 'count', 'totalPrice'))->render();
        }else {
          $data['message'] = 'Geçersiz İşlem!';
        }

        return Response::json($data, 200);
  }

}
