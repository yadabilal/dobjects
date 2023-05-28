<?php

namespace App\Http\Controllers;

use App\Model\Base;
use App\Model\Basket;
use App\Model\Product;
use App\Model\TempBasket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class TempBasketController extends Controller
{
    // Sipariş Tamamla
  public function index() {
      $carts = TempBasket::getBaskets();
      $totalPrices = TempBasket::basketTotals($carts);
    $totalCount = $totalPrices['totalCount'];
    $totalDiscountPrice = Base::amountFormatterWithCurrency($totalPrices['totalFinallyPrice']);
    $totalPrice = Base::amountFormatterWithCurrency($totalPrices['totalPrice']);
    $discountPrice = Base::amountFormatterWithCurrency($totalPrices['totalDiscountPrice']);

    return view('site.membership.basket.index', compact('carts', 'totalCount', 'totalPrice', 'totalDiscountPrice', 'discountPrice'));
  }

  // Sepete Ekle
  public function add()
  {
    $data['success'] = false;
    if(request()->ajax() && request()->post('id')) {

        $all = Base::js_xss(request());
        $product = Product::where('uuid', $all['id'])
            ->where('status', Product::STATUS_PUBLISH)
            ->first();

        if($product) {
            $max = $product->stock ?: Product::MAX_ORDER_COUNT;
            $quantity = @$all['quantity'] ?: 1;

            if($quantity > 0 && $quantity <= $max) {
                $basket = TempBasket::add($product, $quantity);
                if($basket) {

                    $carts = TempBasket::getBaskets();
                    $totalPrices = TempBasket::basketTotals($carts);

                    $data['success'] = true;
                    $data['id'] = $basket->uuid;
                    $data['count'] = $totalPrices['totalCount'];
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
      $basket = TempBasket::by_uuid($id);
      if($basket && $basket->can_delete()) {
        DB::beginTransaction();
        $note = Basket::DELETE_REASON_OWN;
        $basket->update(['note' => $note]);
        $basket->delete();
        DB::commit();

          $carts = TempBasket::getBaskets();
          $totalPrices = TempBasket::basketTotals($carts);

          $data['success'] = true;

          $totalCount = $totalPrices['totalCount'];
          $totalDiscountPrice = Base::amountFormatterWithCurrency($totalPrices['totalFinallyPrice']);
          $totalPrice = Base::amountFormatterWithCurrency($totalPrices['totalPrice']);
          $discountPrice = Base::amountFormatterWithCurrency($totalPrices['totalDiscountPrice']);

          $data['totalPrice'] = $totalPrice;
          $data['totalDiscountPrice'] = $totalDiscountPrice;
          $data['discountPrice'] = $discountPrice;
          $data['count'] = $totalCount;
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
          $carts = TempBasket::getBaskets();

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
                              $cart->quantity = $quantity;
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
              return redirect(route('tempbasket.short_list'));
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
            $carts = TempBasket::getBaskets();
            $totalPrices = TempBasket::basketTotals($carts);
          $count = $totalPrices['totalCount'];
          $totalPrice = Base::amountFormatterWithCurrency($totalPrices['totalFinallyPrice']);
          $data['list'] = view('site.membership.basket.short_list',compact('carts', 'count', 'totalPrice'))->render();
        }else {
          $data['message'] = 'Geçersiz İşlem!';
        }

        return Response::json($data, 200);
  }

}
