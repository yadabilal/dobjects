<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Base
{
  use SoftDeletes;

  protected $table = 'categories';
  protected $fillable = [ 'uuid', 'name', 'url' ];


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
        return $this->hasMany(Product::class, 'category_id');
    }

    public function detailUrl() {
      return route('home').'?kategori='.$this->url;
    }

    public static function list() {
        return self::with("products")->withCount('products')
            ->has('products', '>' , 0)
            ->orderBy('created_at', 'desc')
            ->orderBy('name', 'asc')
            ->get();
    }



    // TODO: SİL
  public function books() {
    return $this->hasMany(Book::class, 'category_id');
  }

}
