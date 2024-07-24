<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Base
{
  use SoftDeletes;

  protected $table = 'categories';
  protected $fillable = [ 'uuid', 'name', 'url', 'sorting' ];


  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      if(!$model->url) {
        $model->url = Str::slug($model->name);
      }
    });
  }

    public function products() {
        return $this->hasMany(Product::class, 'category_id')->where('status', Product::STATUS_PUBLISH);
    }

    public function detailUrl() {
      return request()->url().'?kategori='.$this->url;
    }

    public static function list($forAdmin = false, $discount= false, $type = 'all') {

        $search = Base::js_xss(request());
        if(@$search['urun']) {
            $type = 'all';
        }

      if($forAdmin) {
          return self::with("products")->withCount('products')
              ->orderBy('created_at', 'desc')
              ->orderBy('name')
              ->get();
      }

      $categories = self::select('id', 'name', 'url')
          ->orderBy('sorting')
          ->orderBy('created_at', 'desc')
          ->orderBy('name');

        if($discount) {
            $categories->whereHas('products', function($q){
                $q->where('products.discount_rate',  '>', 0);
            });
        }

        if($type == 'accesorio') {
            $categories->whereHas('products',  function($q) {
                    $q->where('is_accesorio', '=', 1);
                }, '>', 0)
                ->withCount(['products' =>  function($q) {
                    $q->where('is_accesorio', '=', 1);
                }]);
        }else if($type == 'no_accesorio') {
            $categories->whereHas('products',  function($q) {
                $q->where('is_accesorio', '=', 0);
            }, '>', 0)
                ->withCount(['products' =>  function($q) {
                    $q->where('is_accesorio', '=', 0);
                }]);
        }else {
            $categories->withCount('products')->with('products')->has('products', '>' , 0);
        }

        return $categories ->get();
    }

}
