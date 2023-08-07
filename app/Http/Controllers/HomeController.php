<?php

namespace App\Http\Controllers;

use App\Model\Category;
use App\Model\Facebook;
use App\Model\Page;
use App\Model\Product;
use App\Model\Town;

class HomeController extends Controller
{

    public function index(\Illuminate\Http\Request $request)
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

        return view('site.home', compact('items', 'categories', 'allCount', 'urls'));
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
}
