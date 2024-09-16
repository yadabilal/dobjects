<?php

namespace App\Http\Controllers;

use App\Model\Address;
use App\Model\Base;
use App\Model\Basket;
use App\Model\City;
use App\Model\Order;
use App\Model\OrderItem;
use App\Model\Town;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class GuestShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    // Adres Bilgilerini Gir
  public function index() {
    $carts = Basket::getAll();
    $totalCount = Basket::sumQuantity();

    if($totalCount && @$this->setting['kayitOlmadanAlisveris'] == 1) {
        $user = null;
        $totalPrices = Basket::totals();
        $totalDiscountPrice = Base::amountFormatterWithCurrency($totalPrices['totalFinallyPrice']);
        $totalPrice = Base::amountFormatterWithCurrency($totalPrices['totalPrice']);
        $discountPrice = Base::amountFormatterWithCurrency($totalPrices['totalDiscountPrice']);

        $oldAddress = new Address();
        $oldBillingAddress = new Address();
        $billingTypes = Address::billingType();

        $cities = City::all_list();
        $towns = old('city_id') ? Town::all_list(old('city_id'), 'uuid') : (@$oldAddress->city_id ? Town::all_list($oldAddress->city_id): []);
        $billingTowns = old('Billing_city_id') ? Town::all_list(old('Billing_city_id'), 'uuid') : (@$oldBillingAddress->city_id ? Town::all_list($oldBillingAddress->city_id): []);

        return view('site.membership.shop.index', compact('oldAddress', 'billingTowns', 'oldBillingAddress', 'billingTypes','carts', 'user', 'totalCount', 'totalPrice', 'totalDiscountPrice', 'discountPrice', 'cities', 'towns'));

      }else {
          Session::flash('error_message', 'Sepetinde yeterli miktarda ürün yok!');
          return redirect()->route('home');
      }
    }

    public function resultPayment($uuid) {
        $order = Order::where('session_id', session()->getId())
            ->with('items')
            ->where('uuid', $uuid)
            ->whereNull('user_id')
            ->whereIn('status', [Order::STATUS_ERROR, Order::STATUS_NEW])
            ->first();

        if($order) {
            return view('site.membership.shop.result', compact('order'));
        }else {
            Session::flash('error_message', 'Geçersiz İşlem!');
            return redirect()->route('home');
        }

    }

    // Adres Bilgilerini Gir
    public function callbackPayment($uuid) {

        $order = Order::where('session_id', session()->getId())
            ->with('items')
            ->where('uuid', $uuid)
            ->whereNull('user_id')
            ->where('status', Order::STATUS_WAITING_PAYMENT);

        if(request('token')) {
            $order->where('payment_reference', request('token'));
        }

        $order= $order->first();

        if($order) {
            if($order->checkPayment()) {
                return redirect()->route('guest.shop.result', ['uuid' => $order->uuid]);
            }else {
                Session::flash('error_message', 'İşlem sırasında bir hata meydana geldi. Lütfen bizi bilgilendirin!');
            }
        }else {
            Session::flash('error_message', 'Geçersiz İşlem!');
            return redirect()->route('home');
        }
    }

    // Ödeme Sayfasına Git
    public function payment($uuid) {
        $order = Order::where('session_id', session()->getId())
            ->with('items')
            ->where('uuid', $uuid)
            ->where('status', Order::STATUS_WAITING_PAYMENT)
            ->whereNull('user_id')
            ->first();

        if($order) {
            return redirect($order->payment_url);
        }else {
            Session::flash('error_message', 'Geçersiz İşlem!');
            return redirect()->route('home');
        }
  }

    // Validasyon Kontrolü
    public function check() {
        // Post varsa
        if(\request()->post() && @$this->setting['kayitOlmadanAlisveris'] == 1){
            $inputs = Base::js_xss(\request());
            $data['success'] = true;
            $count = Basket::sumQuantity();

            if($count) {
                $differentAddress = false;
                if(@$inputs['different_address']) {
                    $differentAddress = true;
                }

                $rules = [
                    'name' => 'required|max:25',
                    'surname' => 'required|max:30',
                    'email' => 'required|email|max:150',
                    'phone' => 'required|min:10|max:11|phone:TR|regex:/(5)[0-9]/|not_regex:/[a-z]/|',
                    'identity_number' => 'required|max:11|min:11',
                    'city_id' => 'required|exists:cities,uuid',
                    'town_id' => 'required|exists:towns,uuid',
                    'address' => 'required|max:130|min:20',
                    'note' => 'nullable|max:255',
                    'confirm_document' => 'required|in:1',
                    'different_address' => 'nullable|in:0,1',
                ];

                if($differentAddress) {
                    $rules = array_merge($rules, [
                        'different_address' => 'nullable|in:0,1',
                        'Billing_type' => 'required_if:different_address,=,1|in:'.implode(',', array_keys(Address::billingType())),
                        'Billing_name' => 'required_if:Billing_type,=,'.Address::BILLING_TYPE_PERSONAL.'|max:25',
                        'Billing_name2' => 'required_if:Billing_type,=,'.Address::BILLING_TYPE_COMPANY.'|max:25',
                        'Billing_surname' => 'required_if:Billing_type,=,'.Address::BILLING_TYPE_PERSONAL.'|max:30',
                        'Billing_email' => 'required_if:different_address,=,1|email|max:150',
                        'Billing_phone' => 'required_if:different_address,=,1|min:10|max:15|phone:TR|regex:/(5)[0-9]/|not_regex:/[a-z]/|',
                        'Billing_identity_number' => 'required_if:Billing_type,=,'.Address::BILLING_TYPE_COMPANY.'|min:9',
                        'Billing_identity_number2' => 'required_if:Billing_type,=,'.Address::BILLING_TYPE_PERSONAL.'|min:11|max:11',
                        'Billing_city_id' => 'required_if:different_address,=,1|exists:cities,uuid',
                        'Billing_town_id' => 'required_if:different_address,=,1|exists:towns,uuid',
                        'Billing_address' => 'required_if:different_address,=,1|max:130|min:20',
                    ]);
                }


                $validator = Validator::make($inputs, $rules, [
                    'identity_number.required' => "Tc Kimlik Numaran boş olamaz!",
                    'required_if' => "Bu alan boş olamaz!",
                    'confirm_document.required' => "Ön Bilgilendirme Koşulları'nı ve Mesafeli Satış Sözleşmesi'ni okuyup onaylayın!",
                    'confirm_document.in' => "Ön Bilgilendirme Koşulları'nı ve Mesafeli Satış Sözleşmesi'ni okuyup onaylayın!",
                ]);

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
    public function save_address(Request $request) {

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $secretKey = @$this->setting['RECAPTCHA_SECRET_KEY'] ?: '';
        $errors=[];

        if($recaptchaResponse && $secretKey && @$this->setting['kayitOlmadanAlisveris'] == 1) {
            $ip = $request->ip();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'secret' => $secretKey,
                'response' => $recaptchaResponse,
                'remoteip' => $ip
            ]));

            // reCAPTCHA doğrulaması
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}");
            $responseKeys = json_decode($response, true);

            // Başarılı olup olmadığını kontrol et
            if (@$responseKeys['success']) {
                $count = Basket::sumQuantity();
                if(\request()->post() && $count) {
                    $check = $this->check();
                    $result = $check->getData();
                    if(@$result->success) {
                        DB::beginTransaction();
                        try {
                            $inputs = Base::js_xss(\request());
                            $city = City::by_uuid($inputs['city_id']);
                            $town = Town::by_uuid($inputs['town_id']);
                            $billingCity  = $city;
                            $billingTown  = $town;

                            $address = new Address();
                            $address->type = Address::TYPE_SHIPPING;
                            $address->identity_number = $inputs['identity_number'];
                            $address->session_id = session()->getId();
                            $address->name = $inputs['name'];
                            $address->surname = $inputs['surname'];
                            $address->city_id = $city->id;
                            $address->town_id = $town->id;
                            $address->address = $inputs['address'];
                            $address->phone = $inputs['phone'];
                            $address->email = $inputs['email'];
                            $address->note = $inputs['note'];
                            $address->save();

                            $billingOurAddress = new Address();

                            if(@$inputs['different_address']) {
                                $billingCity = City::by_uuid($inputs['Billing_city_id']);
                                $billingTown = Town::by_uuid($inputs['Billing_town_id']);

                                $billingOurAddress->billing_type = $inputs['Billing_type'];
                                $billingOurAddress->type = Address::TYPE_BILLING;
                                $billingOurAddress->session_id = session()->getId();
                                $billingOurAddress->name = $inputs['Billing_name'];
                                $billingOurAddress->identity_number = $billingOurAddress->billing_type == Address::BILLING_TYPE_PERSONAL ? $inputs['Billing_identity_number2'] : $inputs['Billing_identity_number'];
                                $billingOurAddress->surname = $inputs['Billing_surname'];
                                $billingOurAddress->city_id = $billingCity->id;
                                $billingOurAddress->town_id = $billingTown->id;
                                $billingOurAddress->address = $inputs['Billing_address'];
                                $billingOurAddress->phone = $inputs['Billing_phone'];
                                $billingOurAddress->email = $inputs['Billing_email'];
                            }
                            else {
                                $billingOurAddress->billing_type = Address::BILLING_TYPE_PERSONAL;
                                $billingOurAddress->type = Address::TYPE_BILLING;
                                $billingOurAddress->identity_number = $inputs['identity_number'];
                                $billingOurAddress->session_id = session()->getId();
                                $billingOurAddress->name = $inputs['name'];
                                $billingOurAddress->surname = $inputs['surname'];
                                $billingOurAddress->city_id = $billingCity->id;
                                $billingOurAddress->town_id = $billingTown->id;
                                $billingOurAddress->address = $inputs['address'];
                                $billingOurAddress->phone = $inputs['phone'];
                                $billingOurAddress->email = $inputs['email'];
                                $billingOurAddress->note = $inputs['note'];
                            }

                            $billingOurAddress->save();
                            $buyer = new \Iyzipay\Model\Buyer();
                            $buyer->setId($address->session_id);
                            $buyer->setName($address->name);
                            $buyer->setSurname($address->surname);
                            $buyer->setGsmNumber($address->phone);
                            $buyer->setEmail($address->email);
                            $buyer->setIdentityNumber($address->identity_number);
                            $buyer->setRegistrationDate(Carbon::now()->format('Y-m-d H:i:s'));
                            $buyer->setRegistrationAddress($address->address);
                            $buyer->setIp(request()->ip());
                            $buyer->setCity($city->name);
                            $buyer->setCountry("Turkey");

                            $shippingAddress = new \Iyzipay\Model\Address();
                            $shippingAddress->setContactName($address->fullName());
                            $shippingAddress->setCity($city->name);
                            $shippingAddress->setCountry("Turkey");
                            $shippingAddress->setAddress($address->address);

                            $billingAddress = new \Iyzipay\Model\Address();
                            $billingAddress->setContactName($billingOurAddress->fullName());
                            $billingAddress->setCity($billingCity->name);
                            $billingAddress->setCountry("Turkey");
                            $billingAddress->setAddress($billingOurAddress->address);

                            $itemSavedCount = 0;
                            $itemCount = 0;
                            $carts = Basket::getAll(null);
                            $totalCount = Basket::sumQuantity();
                            $tdp = Basket::sumTotalDiscountPrice();
                            $tp = Basket::sumTotalPrice();
                            $discountPrice = $tp-$tdp;
                            $latestOrder = Order::orderBy('id', 'desc')->first();

                            $order = new Order();
                            $order->address_id = $address->id;
                            $order->billing_address_id = $billingOurAddress->id;
                            $order->number = $latestOrder ? substr(str_shuffle(MD5(microtime())), 0, (50-strlen($latestOrder->id))).$latestOrder->id : substr(str_shuffle(MD5(microtime())), 0, 50);
                            $order->total_quantity = $totalCount ;
                            $order->total_price = $tp ;
                            $order->total_discount_price = $tdp;
                            $order->discount_price = $discountPrice;
                            $order->setNote(Order::MESSAGE_USER_NOTE, @$inputs['note'] ?: '');
                            $order->save();

                            $basketItems = [];
                            foreach ($carts as $cart) {
                                $itemCount++;
                                $orderItem = new OrderItem();
                                $orderItem->status = Order::STATUS_WAITING_PAYMENT;
                                $orderItem->order_id = $order->id;
                                $orderItem->product_id = $cart->product_id;
                                $orderItem->quantity = $cart->quantity;
                                $orderItem->price = $cart->product->price;
                                $orderItem->discount_price = $cart->product->discount_price;
                                $orderItem->total_price = $orderItem->price*$orderItem->quantity;
                                $orderItem->total_discount_price = $orderItem->discount_price*$orderItem->quantity;
                                $orderItem->discount = $orderItem->total_price-$orderItem->total_discount_price;

                                if($orderItem->save()) {
                                    $basketItem = new \Iyzipay\Model\BasketItem();
                                    $basketItem->setId($orderItem->id);
                                    $basketItem->setName($cart->product->name);
                                    $basketItem->setCategory1($cart->product->category->name);
                                    $basketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
                                    $basketItem->setPrice($orderItem->total_discount_price);
                                    $basketItems[] = $basketItem;
                                    $itemSavedCount ++;
                                }
                            }

                            if($itemSavedCount == $itemCount) {
                                $options = new \Iyzipay\Options();
                                $options->setApiKey(env('IYZICO_API_KEY'));
                                $options->setSecretKey(env('IYZICO_API_SECRET'));
                                $options->setBaseUrl(env('IYZICO_BASE_URL'));

                                $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
                                $request->setLocale(\Iyzipay\Model\Locale::TR);
                                $request->setConversationId($order->number);
                                $request->setPrice($order->total_discount_price);
                                $request->setPaidPrice($order->total_discount_price);
                                $request->setCurrency(\Iyzipay\Model\Currency::TL);
                                $request->setBasketId($order->reference);
                                $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
                                $request->setCallbackUrl(route('guest.shop.callback', ['uuid' => $order->uuid]));
                                $request->setEnabledInstallments([2, 3, 6, 9]);

                                $request->setBuyer($buyer);
                                $request->setShippingAddress($shippingAddress);
                                $request->setBillingAddress($billingAddress);
                                $request->setBasketItems($basketItems);
                                $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);

                                if($checkoutFormInitialize->getStatus() == 'success') {
                                    $order->payment_reference = $checkoutFormInitialize->getToken();
                                    $order->payment_payload = $checkoutFormInitialize->getRawResult();
                                    $order->payment_url = $checkoutFormInitialize->getPaymentPageUrl().'&iframe=true';
                                    $order->save();

                                    $billingOurAddress->delete();
                                    $address->delete();
                                    DB::commit();;
                                    return redirect(route('guest.shop.payment', ['uuid' => $order->uuid]));
                                }else {
                                    DB::rollBack();
                                    Session::flash('error_message', $checkoutFormInitialize->getErrorMessage() ?: 'Ödeme sisteminde beklenmedik bir hata alındı!Kod: 24');
                                }
                            }

                        }catch (\Exception $e) {
                            DB::rollBack();
                            Session::flash('error_message', $e->getMessage() );
                        }

                        return redirect()->back();
                    }else {
                        Session::flash('error_message', 'Beklenmedik bir hata alındı! Kod: 23');

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

            } else {
                Session::flash('error_message', 'reCAPTCHA doğrulaması başarısız oldu.');
                $errors['g-recaptcha-response'] = 'reCAPTCHA doğrulaması başarısız oldu.';
            }
        }else {
            Session::flash('error_message', 'reCAPTCHA doğrulaması başarısız oldu.');
            $errors['g-recaptcha-response'] = 'reCAPTCHA doğrulaması başarısız oldu.';
        }

        return redirect()->back()->withErrors($errors)->withInput();
    }

}
