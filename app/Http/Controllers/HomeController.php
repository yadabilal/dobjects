<?php

namespace App\Http\Controllers;

use App\Model\Category;
use App\Model\Page;
use App\Model\Product;
use App\Model\Town;

class HomeController extends Controller
{

    public function index()
    {
        $allCount = Product::list_all_count();
        $urls = Product::shortingUrls();
        $items = Product::list_all();

        $categories = Category::list();

        return view('site.home', compact('items', 'categories', 'allCount', 'urls'));
    }

    // İletişim Sayfası
    public function show($url)
    {
        $item = Product::findByUrl($url);

        if($item) {
            $maxCount = $item->stock ?: Product::MAX_ORDER_COUNT;
            $lastItems = Product::list_all(8);
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
