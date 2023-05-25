<?php

namespace App\Model;
use App\User;

class Search extends Base
{
  const PAGINATION_LIST_ADMIN = 10;
  const TYPE_ALL= 'ALL';
  const TYPE_JUST_SEND= 'JUST_SEND';

  const ALL_YES = 'Evet';
  const ALL_NO = 'Hayir';

  protected $table = 'searchs';
  protected $fillable = [
    'uuid','session_id', 'book_name',
    'type', 'author', 'user_id',
    'city_id', 'town_id', 'category_id', 'ip_address'
  ];

  public function user() {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function city() {
    return $this->belongsTo(City::class, 'city_id');
  }

  public function town() {
    return $this->belongsTo(Town::class, 'town_id');
  }

  public function category() {
    return $this->belongsTo(Category::class, 'category_id');
  }

  public static function add() {
    $model_search = new Search();
    $model_search->user_id = \auth()->id() ? : null;
    $model_search->session_id = session()->getId() ? : null;
    $model_search->type = self::TYPE_ALL;
    $model_search->ip_address = request()->ip();
    $search_save = false;
    $search = Base::js_xss(request());

    // Kitap var mı?
    if(@$search['kitap']) {
      $search_save = true;
      $model_search->book_name = $search['kitap'];
    }
    if(@$search['tumu'] == self::ALL_NO) {
      $search_save = true;
      $model_search->type = self::TYPE_JUST_SEND;
    }
    // Yazar Adı
    if(@$search['yazar']) {
      $search_save = true;
      $model_search->author = $search['yazar'];
    }
    // Kategori
    if(@$search['kategori']) {
      $search_save = true;
      $category = Category::by_uuid($search['kategori']);
      $model_search->category_id = $category ? $category->id : null;
    }
    // İl-İlçe
    if(@$search['sehir']) {
      $search_save = true;
      $city = @City::by_uuid($search['sehir']) ? : null;
      $model_search->city_id = $city ? $city->id : null;
    }

    if(@$search['ilce']) {
      $search_save = true;
      $town = @Town::by_uuid($search['ilce']) ? : null;
      $model_search->town_id = $town ? $town->id : null;
    }

    if($search_save) {
      try {
        $model_search->save();
      }catch (\Exception $e) {}
    }

    return $model_search;
  }
}
