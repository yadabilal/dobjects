<?php

namespace App\Http\Controllers\MemberShipPanel;

use App\Http\Controllers\Controller;
use App\Model\Address;
use App\Model\Base;
use App\Model\City;
use App\Model\Order;
use App\Model\OrderItem;
use App\Model\Town;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

// Adres Bilgilerini Gir
  public function index() {
    $user = $this->user;
    $carts = $user->baskets()->get();
    $totalCount = $user->baskets->sum('quantity');

    if($totalCount) {
        $totalPrices = $user->basketTotals();
        $totalDiscountPrice = Base::amountFormatterWithCurrency($totalPrices['totalFinallyPrice']);
        $totalPrice = Base::amountFormatterWithCurrency($totalPrices['totalPrice']);
        $discountPrice = Base::amountFormatterWithCurrency($totalPrices['totalDiscountPrice']);

          $oldAddress = $user->address()->first();
          $cities = City::all_list();
          $towns = old('city_id') ? Town::all_list(old('city_id'), 'uuid') : (@$oldAddress->city_id ? Town::all_list(@$oldAddress->city_id): []);

          return view('site.membership.shop.index', compact('oldAddress','carts', 'user', 'totalCount', 'totalPrice', 'totalDiscountPrice', 'discountPrice', 'cities', 'towns'));

      }else {
          Session::flash('error_message', 'Sepetinde yeterli miktarda ürün yok!');
          return redirect()->route('home');
      }
    }

    // Adres Bilgilerini Gir
    public function result() {

        return view('site.membership.shop.result');
    }

    // Ödeme Sayfasına Git
    public function payment() {
        $user = $this->user;
        $carts = $user->baskets()->get();
        $totalCount = $this->user->baskets->sum('quantity');

        if($totalCount) {
            $totalPrices = $user->basketTotals();
            $totalDiscountPrice = Base::amountFormatterWithCurrency($totalPrices['totalFinallyPrice']);
            $totalPrice = Base::amountFormatterWithCurrency($totalPrices['totalPrice']);
            $discountPrice = Base::amountFormatterWithCurrency($totalPrices['totalDiscountPrice']);

            $oldAddress = $this->user->address()->first();
            $cities = City::all_list();
            $towns = old('city_id') ? Town::all_list(old('city_id'), 'uuid') : (@$oldAddress->city_id ? Town::all_list(@$oldAddress->city_id): []);

            return view('site.membership.shop.payment', compact('oldAddress','carts', 'user', 'totalCount', 'totalPrice', 'totalDiscountPrice', 'discountPrice', 'cities', 'towns'));

        }else {
            Session::flash('error_message', 'Sepetinde yeterli miktarda ürün yok!');
            return redirect()->route('home');
        }

  }

    // Validasyon Kontrolü
    public function check() {
        // Post varsa
        if(\request()->post()){
            $inputs = Base::js_xss(\request());
            $data['success'] = true;

            if($this->user->can_order()) {

                $rules = [
                    'name' => 'required|max:25',
                    'surname' => 'required|max:30',
                    'email' => 'required|email|max:150',
                    'phone' => 'required|min:10|max:15|phone:TR|regex:/(5)[0-9]/|not_regex:/[a-z]/|',
                    'city_id' => 'required|exists:cities,uuid',
                    'town_id' => 'required|exists:towns,uuid',
                    'address' => 'required|max:130|min:20',
                    'note' => 'nullable|max:255',
                    'billing_note' => 'nullable|max:255',
                    'confirm_document' => 'required|in:1'
                ];

                $validator = Validator::make($inputs, $rules, [
                    'confirm_document.required' => "Ön Bilgilendirme Koşulları'nı ve Mesafeli Satış Sözleşmesi'ni okuyup onaylayın!",
                    'confirm_document.in' => "Ön Bilgilendirme Koşulları'nı ve Mesafeli Satış Sözleşmesi'ni okuyup onaylayın!",
                ]);
                $validator->after(function ($validator){
                    $items = auth()->user()->baskets;
                    $message = trans('validation.dont_request');
                    foreach ($items as $item) {
                        if (!$item->product) {
                            $validator->errors()->add('items', [$item->uuid => $message]);
                        }
                    }
                });

                $errors = $validator->getMessageBag()->toArray();
                if ($errors){
                    $data['success'] = false;
                    $data['errors'] = $errors;
                }
            }else {
                $data['message'] = "Bu işlemi yapmaya yetkiniz yok!";
            }

            return Response::json($data, 200);
        }
    }

    // Adres Bilgilerini Kayder
    public function save_address() {

        $errors=[];
        if(\request()->post() && $this->user->can_order()) {
            $check = $this->check();
            $result = $check->getData();
            if(@$result->success) {
                $inputs = Base::js_xss(\request());
                $city = City::by_uuid($inputs['city_id']);
                $town = Town::by_uuid($inputs['town_id']);

                $address = $this->user->address()->first() ?: new Address();
                $address->user_id = auth()->id();
                $address->name = $inputs['name'];
                $address->surname = $inputs['surname'];
                $address->city_id = $city->id;
                $address->town_id = $town->id;
                $address->address = $inputs['address'];
                $address->phone = $inputs['phone'];
                $address->email = $inputs['email'];
                $address->billing_note = $inputs['billing_note'];
                $address->note = $inputs['note'];

                if($address->save()) {
                    return redirect()->route('shop.payment');
                }else {
                    Session::flash('error_message', 'İşlemini yaparken bir hata meydana geldi!');
                }

                return redirect()->back();
            }else {
                Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');

                foreach ($result->errors as $key => $values) {
                    if($key=='items') {
                        foreach ($values as $id=>$message) {
                            $item_key = key($message);
                            $errors[$item_key][]= $message->{$item_key};
                        }
                    }else {
                        $errors[$key]= $values;
                    }
                }
            }
        }else {
            Session::flash('error_message', 'Yetkisiz erişim!');
        }

        return redirect()->back()->withErrors($errors)->withInput();
    }

    public function save_payment() {
        $errors=[];
        if(\request()->post() && $this->user->can_order()) {
            DB::beginTransaction();
            try {
                $inputs = Base::js_xss(\request());
                $itemSavedCount = 0;
                $itemCount = 0;

                $carts = $this->user->baskets()->get();
                $totalCount = $this->user->baskets->sum('quantity');
                $tdp = $this->user->baskets->sum('total_discount_price');
                $tp = $this->user->baskets->sum('total_price');
                $discountPrice = $tp-$tdp;

                $address = $this->user->address()->first();
                $latestOrder = Order::orderBy('id', 'desc')->first();

                $order = new Order();
                $order->address_id = $address->id;
                $order->number = '#'.str_pad($latestOrder ? $latestOrder->id + 1 : 1, 8, "0", STR_PAD_LEFT);
                $order->total_quantity = $totalCount ;
                $order->total_price = $tp ;
                $order->total_discount_price = $tdp;
                $order->discount_price = $discountPrice;
                $order->save();

                foreach ($carts as $cart) {
                    $itemCount++;
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $cart->product_id;
                    $orderItem->quantity = $cart->quantity;
                    $orderItem->price = $cart->product->price;
                    $orderItem->discount_price = $cart->product->discount_price;
                    $orderItem->total_price = $orderItem->price*$orderItem->quantity;
                    $orderItem->total_discount_price = $orderItem->discount_price*$orderItem->quantity;
                    $orderItem->discount = $orderItem->total_price-$orderItem->total_discount_price;

                    if($orderItem->save()) {
                        $itemSavedCount ++;
                    }
                }

                if($itemSavedCount == $itemCount && $address->delete()) {
                    $this->user->baskets()->delete();
                    DB::commit();
                    $url = route('shop.result').'?order='.$order->uuid;
                    return redirect($url);
                }else {
                    DB::rollBack();
                    Session::flash('error_message', 'İşlemini yaparken bir hata meydana geldi!');
                }
            }catch (\Exception $e) {
                DB::rollBack();
                Session::flash('error_message', 'İşlemini yaparken bir hata meydana geldi!');
            }

            return redirect()->back();
        }else {
            Session::flash('error_message', 'Yetkisiz erişim!');
        }

        return redirect()->back()->withErrors($errors)->withInput();
    }

}
