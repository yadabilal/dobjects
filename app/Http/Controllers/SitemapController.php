<?php

namespace App\Http\Controllers;

use App\Model\Product;
use App\Http\Requests;

class SitemapController extends Controller
{
  public function index()
  {

    return response()->view('site.sitemap.index', [
    ])->header('Content-Type', 'text/xml');
  }
  public function statics()
  {
        return response()->view('site.sitemap.statics', [
        ])->header('Content-Type', 'text/xml');
  }
  public function products()
  {
      $products = Product::where('status', Product::STATUS_PUBLISH)->get();
        return response()->view('site.sitemap.products', [
          'products' => $products
        ])->header('Content-Type', 'text/xml');
  }
}
