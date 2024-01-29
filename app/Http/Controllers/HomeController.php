<?php

namespace App\Http\Controllers;

use App\Model\Base;
use App\Model\Category;
use App\Model\Comment;
use App\Model\Facebook;
use App\Model\HomePage;
use App\Model\Page;
use App\Model\Product;
use App\Model\Subscribe;
use App\Model\Support;
use App\Model\Town;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{

    public function index() {

        $typeOnes = HomePage::where('type', HomePage::TYPE_1)
            ->where('status', HomePage::STATUS_PUBLISH)
            ->orderBy('sorting')
            ->get();

        $typeTwos = HomePage::where('type', HomePage::TYPE_2)
            ->where('status', HomePage::STATUS_PUBLISH)
            ->orderBy('sorting')
            ->get();

        $typeThrees = HomePage::where('type', HomePage::TYPE_3)
            ->where('status', HomePage::STATUS_PUBLISH)
            ->orderBy('sorting')
            ->get();

        $typeFours = HomePage::where('type', HomePage::TYPE_4)
            ->where('status', HomePage::STATUS_PUBLISH)
            ->orderBy('sorting')
            ->get();

        $typeFives = HomePage::where('type', HomePage::TYPE_5)
            ->where('status', HomePage::STATUS_PUBLISH)
            ->orderBy('sorting')
            ->get();

        $typeSixes = HomePage::where('type', HomePage::TYPE_6)
            ->where('status', HomePage::STATUS_PUBLISH)
            ->orderBy('sorting')
            ->get();

        $typeSevens = HomePage::where('type', HomePage::TYPE_7)
            ->where('status', HomePage::STATUS_PUBLISH)
            ->orderBy('sorting')
            ->get();

        $comments = Comment::where('status', Comment::STATUS_PUBLISH)
            ->with('product', 'user')
            ->where('show_home_page', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $products = Product::where('status', Product::STATUS_PUBLISH)
            ->where('show_home_page', 1)
            ->orderBy('sorting')
            ->get();

        return view('site.index', compact('typeOnes', 'typeTwos', 'typeThrees', 'typeFours', 'typeFives', 'comments', 'products', 'typeSixes', 'typeSevens'));

    }

    public function product(\Illuminate\Http\Request $request)
    {
        $allCount = 0; // Product::list_all_count();
        $urls = Product::shortingUrls();
        $items = Product::list_all();
        $categories = Category::list();

        try {
            $facebook = new Facebook();
            if($request->get('urun')) {
                $facebook->event = Facebook::EVENT_SEARCH;
                $facebook->customData['search_string'] = $request->get('urun');
            }

            $facebook->sourceUrl = $request->url();
            $facebook->user = $this->user;
            $result = $facebook->events($this->setting);
        }catch (\Exception $e) {}

        return view('site.product', compact('items', 'categories', 'allCount', 'urls'));
    }

    public function discountedProducts(\Illuminate\Http\Request $request)
    {
        $allCount = 0; // Product::list_all_count();
        $urls = Product::shortingUrls();
        $items = Product::list_all(null, false, false,  true);
        $categories = Category::list();

        try {
            $facebook = new Facebook();
            if($request->get('urun')) {
                $facebook->event = Facebook::EVENT_SEARCH;
                $facebook->customData['search_string'] = $request->get('urun');
            }

            $facebook->sourceUrl = $request->url();
            $facebook->user = $this->user;
            $result = $facebook->events($this->setting);
        }catch (\Exception $e) {}

        $title = "İndirimli Ürünler";
        return view('site.product', compact('items', 'categories', 'allCount', 'urls', 'title'));
    }

    // İletişim Sayfası
    public function show($url, \Illuminate\Http\Request $request)
    {
        $item = Product::findByUrl($url);

        if($item) {
            try {
                $facebook = new Facebook();
                $facebook->sourceUrl = $request->url();
                $facebook->user = $this->user;
                $result = $facebook->events($this->setting);
            }catch (\Exception $e) {}

            $maxCount = $item->stock ?: Product::MAX_ORDER_COUNT;
            $lastItems = Product::list_all(8, false, true);
            return view('site.show', compact('item', 'lastItems', 'maxCount'));
        }else {
            return $this->errorPage();
        }
    }

    // İletişim Sayfası
    public function contract($url = '')
    {
        $page = Page::where('url', $url)->where('status', Page::STATUS_PUBLISH)->first();

        if($page) {
            return view('site.contract.index', compact('page'));
        }

      return view('site.error');
    }

    // İlçeleri bul
    public function town(\Illuminate\Http\Request $request)
    {
      if($request->post() && @$request->all()['city']) {
        if ($request->all()['city']) {
          $towns =  Town::all_list($request->all()['city'], 'uuid');
          return \Illuminate\Support\Facades\Response::json(
            [
              'success' => true,
              'towns' => json_encode($towns),
            ], 200);
        }
      }
    }

    public function subscribeCheck(\Illuminate\Http\Request $request)
    {
        $request = \request();
        $data['success'] = false;
        if($request->post()) {
            $data['success'] = true;
            $inputs = Base::js_xss($request);
            $rule = [
                'email' => 'required|email|max:150',
            ];

            $validator = Validator::make($inputs, $rule);
            $errors = $validator->getMessageBag()->toArray();

            if ($errors){
                $data['success'] = false;
                $data['errors'] = $errors;
            }
        }else {
            $data['message'] = 'Geçersiz İstek!';
        }

        return Response::json($data, 200);
    }
    public function subscribe(\Illuminate\Http\Request $request)
    {
        $request= \request();
        if($request->post()) {
            $check = $this->subscribeCheck($request);
            $result = $check->getData();
            $inputs = Base::js_xss($request);
            if(@$result->success) {

                $emailAddress = trim($request->all()['email']);
                if ($emailAddress) {
                    $email = Subscribe::where('email', $emailAddress)->first();

                    if(!$email) {
                        $model = new Subscribe();
                        $model->email = $emailAddress;
                        $model->save();
                    }
                }

                Session::flash('success_message', 'Aboneliğin başarılı bir şekilde oluştu.');

                return redirect()->back();

            }else {
                if(@$result->message) {
                    Session::flash('error_message', $result->message);
                }else{
                    Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
                }
                return redirect()->back()->withErrors($result->errors)->withInput();
            }
        }

    }
}
