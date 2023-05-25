<?php

namespace App\Http\Controllers;

use App\Model\Category;
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
            $lastItems = Product::list_all(8);
            return view('site.show', compact('item', 'lastItems'));
        }else {
            return $this->errorPage();
        }
    }

    // İletişim Sayfası
    public function contract($url = '')
    {
        if($url == 'gizlilik-sozlesmesi') {
            return view('site.contract.gizlilik');
        }else if($url == 'kullanici-sozlesmesi') {
            return view('site.contract.kullanici');
        }else if($url == 'kisisel-verilerin-korunmasi') {
            return view('site.contract.kisisel_veri_korunmasi');
        }else if($url == 'iptal-ve-iade-kosullari') {
            return view('site.contract.iade_ve_iptal');
        }

      return view('site.contract.index');
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
