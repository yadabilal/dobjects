<?php

namespace App\Http\Controllers\MemberShipPanel;

use App\Http\Controllers\Controller;
use App\Model\Base;
use App\Model\Basket;
use App\Model\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class BasketController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  // Sipariş Tamamla
  public function index() {
    $user = $this->user;
    $carts = $user->baskets()->get();
    $totalPrices = $user->basketTotals();
    $totalCount = $this->user->baskets->sum('quantity');
    $totalDiscountPrice = Base::amountFormatterWithCurrency($totalPrices['totalFinallyPrice']);
    $totalPrice = Base::amountFormatterWithCurrency($totalPrices['totalPrice']);
    $discountPrice = Base::amountFormatterWithCurrency($totalPrices['totalDiscountPrice']);

    return view('site.membership.basket.index', compact('carts', 'user', 'totalCount', 'totalPrice', 'totalDiscountPrice', 'discountPrice'));
  }

  // Sepete Ekle
  public function add()
  {

    $data['success'] = false;
    if(request()->ajax() && request()->post('id')) {

        if(!$this->user->can_order()) {
            $data['mesage'] = "Lütfen profilinizi tamamlayın!";
        }else {
            $all = Base::js_xss(request());
            $product = Product::where('uuid', $all['id'])
                ->where('status', Product::STATUS_PUBLISH)
                ->first();

            if($product) {
                $basket = Basket::add($product, @$all['quantity'] ?: 1, @$all['note'] ?: '');

                if($basket) {
                    $count = $this->user->baskets->sum('quantity');

                    $data['success'] = true;
                    $data['id'] = $basket->uuid;
                    $data['count'] = $count;
                    $data['message'] = "Ürün Sepete Eklendi!";
                }else {
                    $data['message'] = "Geçersiz İşlem!";
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

          $count = $this->user->baskets->sum('quantity');
          $tdp = $this->user->baskets->sum('total_discount_price');
          $totalDiscountPrice = Base::amountFormatterWithCurrency($tdp);
          $tp = $this->user->baskets->sum('total_price');
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
          $carts = $this->user->baskets;
          if(@$inputs['quantity'] && $carts) {
              foreach ($carts as $cart) {
                  $quantity = @$inputs['quantity'][$cart->uuid] ?: null;
                  if($quantity) {
                      $product = Product::where('id', $cart->product_id)
                          ->where('status', Product::STATUS_PUBLISH)
                          ->first();

                      if($product) {
                          $cart->price = $product->price;
                          $cart->quantity = $quantity;
                          $cart->discount_price = $product->discount_price;
                          $cart->total_price = $product->price*$quantity;
                          $cart->total_discount_price = $product->discount_price*$quantity;
                          $cart->save();
                      }else {
                          $cart->delete();
                      }

                  }else {
                      $cart->delete();
                  }
              }

              Session::flash('success_message', 'Sepet güncellendi!');
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
          $carts = $this->user->baskets()->get() ? : [];
          $totalPrices = $this->user->basketTotals();
          $count = $this->user->baskets->sum('quantity');
          $totalPrice = Base::amountFormatterWithCurrency($totalPrices['totalFinallyPrice']);
          $data['list'] = view('site.membership.basket.short_list',compact('carts', 'count', 'totalPrice'))->render();
        }else {
          $data['message'] = 'Geçersiz İşlem!';
        }

        return Response::json($data, 200);
  }

}
