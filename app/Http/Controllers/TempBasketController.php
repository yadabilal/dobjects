<?php

namespace App\Http\Controllers;

use App\Model\Base;
use App\Model\Basket;
use App\Model\Product;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class TempBasketController extends Controller
{

    public function index() {
        $carts = Session::get('basket.items');
        $totalPrices = $this->basketTotals();
        $totalCount = Session::get('basket.totalQuantity');
        $totalDiscountPrice = Base::amountFormatterWithCurrency($totalPrices['totalFinallyPrice']);
        $totalPrice = Base::amountFormatterWithCurrency($totalPrices['totalPrice']);
        $discountPrice = Base::amountFormatterWithCurrency($totalPrices['totalDiscountPrice']);

        return view('site.membership.basket.index', compact('carts',  'totalCount', 'totalPrice', 'totalDiscountPrice', 'discountPrice'));
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
                    $baskets = Session::get('basket.items');

                    $basket = new Basket();
                    $basket->uuid = $product->uuid;
                    $basket->product = $product;
                    $basket->quantity = $quantity;
                    $totalQuantity = Session::get('basket.totalQuantity') ? Session::get('basket.totalQuantity') +$quantity : $quantity;

                    $baskets[ $product->uuid] = $basket;

                    Session::put('basket.items', $baskets);
                    Session::put('basket.totalQuantity', $totalQuantity);
                    if($totalQuantity) {
                        $data['success'] = true;
                        $data['id'] = $product->uuid;
                        $data['count'] = $totalQuantity;
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
        $cartItems = Session::get('basket.items');

        if(request()->ajax() && $id && @$cartItems[$id]) {
            unset($cartItems[$id]);

            Session::put('basket.items', $cartItems);
            $totalPrices = $this->basketTotals();
            Session::put('basket.totalQuantity', $totalPrices['totalQuantity']);

            $data['success'] = true;
            $data['totalPrice'] =  Base::amountFormatterWithCurrency($totalPrices['totalPrice']);
            $data['totalDiscountPrice'] = Base::amountFormatterWithCurrency($totalPrices['totalFinallyPrice']);
            $data['discountPrice'] =  Base::amountFormatterWithCurrency($totalPrices['totalDiscountPrice']);
            $data['count'] = $totalPrices['totalQuantity'];
            $data['message'] = "Ürün sepetten çıkarıldı!";
        }else {
            $data['message'] = "Geçersiz İşlem!";
        }

        return Response::json($data, 200);
    }

    // Sepeti Güncelle
    public function update() {
        if (request()->isMethod('post')) {
            $inputs = Base::js_xss(\request());
            $carts = Session::get('basket.items');
            if(@$inputs['quantity'] && $carts) {
                $isError = false;
                $totalQuantity = 0;

                foreach ($carts as $cartId => $cart) {
                    $quantity = @$inputs['quantity'][$cartId] ?: null;

                    if($quantity>0) {
                        $product = Product::where('uuid', $cartId)
                            ->where('status', Product::STATUS_PUBLISH)
                            ->first();
                        $max = $product->stock ?: Product::MAX_ORDER_COUNT;
                        if($product) {
                            $cart->product= $product;
                            if($quantity <= $max) {
                                $totalQuantity +=$quantity;
                                $carts[$cartId]['quantity'] = $quantity;
                            }else {
                                $isError = true;
                                Session::flash('error_message', "Aynı üründen en fazla ".$max.' adet sipariş verebilirsiniz!');
                            }

                        }else {
                            unset($carts[$cartId]);
                        }

                    }else {
                        unset($carts[$cartId]);
                    }
                }
                Session::put('basket.items', $carts);
                Session::put('basket.totalQuantity', $totalQuantity);
                if(!$isError) {
                    Session::flash('success_message', 'Sepet güncellendi!');
                }

                return redirect()->back();
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
            $carts = Session::get('basket.items');
            $totalPrices = $this->basketTotals();
            $count = $totalPrices['totalQuantity'];
            $totalPrice = Base::amountFormatterWithCurrency($totalPrices['totalFinallyPrice']);
            $data['list'] = view('site.membership.basket.short_list',compact('carts', 'count', 'totalPrice'))->render();
        }else {
            $data['message'] = 'Geçersiz İşlem!';
        }

        return Response::json($data, 200);
    }

    private function basketTotals() {
        $totalPrice = 0;
        $totalFinallyPrice = 0;
        $quantity = 0;

        $cartItems = Session::get('basket.items');

        foreach ($cartItems as $cartItemId => $basket) {
            $product = Product::where('uuid' , $cartItemId)
                ->where('status', Product::STATUS_PUBLISH)
                ->first();

            if($product) {
                $cartItemQuantity = $basket->quantity;
                $quantity += $cartItemQuantity;
                $totalPrice += $product->price*$cartItemQuantity;
                $totalFinallyPrice += $product->discount_price*$cartItemQuantity;
            }else {
                unset($cartItems[$cartItemId]);
            }
        }

        $totalDiscountPrice = $totalPrice-$totalFinallyPrice;

        return [
            'totalQuantity' => $quantity,
            'totalPrice' => $totalPrice,
            'totalDiscountPrice' => $totalDiscountPrice,
            'totalFinallyPrice' => $totalFinallyPrice
        ];
    }

}
