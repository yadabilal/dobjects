<?php

namespace App\Model;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Book extends Base
{
  use SoftDeletes;

  const PAGINATION_MY_BOOK =6;
  const PAGINATION_LIST_BOOK =8;
  const PAGINATION_LIST_ADMIN =10;

  const READ_DAY= 10;
  const READ_STATUS_READ = 'READ';
  const READ_STATUS_NOT_READ = 'NOT_READ';

  const STATUS_PUBLISH = 'PUBLISH';
  const STATUS_NOT_PUBLISH = 'NOT_PUBLISH';

  public $store_path = 'books';

  protected $table = 'books';
  protected $fillable = [
    'uuid', 'parent_id', 'name',
    'url', 'author', 'status',
    'read_status','category_id','user_id', 'level',
    'publish_date'
  ];

  protected static function boot()
  {
    parent::boot();
    self::creating(function($model){
      $model->status = $model->status ? :self::STATUS_PUBLISH;
      $model->publish_date = $model->publish_date ? : Carbon::now()->format('Y-m-d');
    });
    self::updating(function($model){
      if(!$model->check_publish_date() && $model->read_status == self::READ_STATUS_READ) {
        $model->publish_date = Carbon::now()->format('Y-m-d');
      }
    });
    self::saving(function($model){
      if(!$model->check_publish_date() && $model->read_status == self::READ_STATUS_READ) {
        $model->publish_date = Carbon::now()->format('Y-m-d');
      }
    });
    // Bir kitap silindiği zaman sepettekileri sil yoksa hata verir.
    self::deleted(function($model){
      $model->baskets()->delete();
    });
  }

  public function baskets() {
    return $this->hasMany(Basket::class, 'book_id')->orderBy('id', 'desc');
  }
  // User
  public function user() {
    return $this->belongsTo(User::class, 'user_id')->with('city', 'town');
  }
  // Category
  public function category() {
    return $this->belongsTo(Category::class, 'category_id');
  }
  // Parent
  public function parent() {
    return $this->belongsTo(self::class, 'parent_id')->withTrashed();
  }

  public function seo_title() {
    return $this->name.' - '.$this->author;
  }
  public function seo_description() {
    $where = $this->send_where();
    $title = $this->seo_title();
    return $where ?
      $title.' '.$where.' ve Türkiyenin her yerinden İkinci el ücretsiz kitap'
    : $title.' İstanbul, İzmir, Kocaeli, Bursa, Diyarbakır, Samsun, Antalya, Adana, Ankara ve Türkiyenin her yerinden İkinci el kitap, ücretsiz kitap.';
  }
  /*
   * Kitaba ait tüm siparişler
   * Listelenir
   */
  public function orders() {
    return $this->hasMany(Order::class, 'book_id');
  }
  /*
   * Kitaba ait beklyene siparişler
   * Listelenir
   */
  public function wait_orders() {
    return $this->orders()->where('status', Order::STATUS_NEW);
  }
  /*
   * Bir kitaba ait
   * Bekleyen ya da kargolanan
   * Sipariş varsa, İşlem yapılamaz
   * Sipariş verilemez, sepete eklenemez,
   * Güncellenemez, Silinemez
   */
  public function not_process_orders() {
    return $this->orders()->whereIn('status', [Order::STATUS_NEW, Order::STATUS_CARGO, Order::STATUS_NOT_COMPLETED]);
  }
  /*
   * Kitabın yazar ile
   * Birlikte tam adı
   * Kitap Adı + (Yazar)
   */
  public function full_name() {
    return $this->name.' ('.$this->author.')';
  }
  /*
   * Kitabın sahibinin
   * Göndereceği İl, İlçe
   * Bilgisi
   */
  public function send_where() {
    return $this->user->city_town() ?  : '';
  }
  /*
   * Kitap sahibinin Kitabı
   * Okuma durumu, Okudu, Okumadı
   */
  public function read_status() {
    return $this->read_status == Book::READ_STATUS_READ ? 'Okudun' : 'Okumadın';
  }

  public function publishdate() {
    return self::date_read($this->publish_date);
  }
  /*
   * Yayınlanma tarihinden önce istenemez
   */
  public function check_publish_date() {
    return !Carbon::today()->lessThanOrEqualTo($this->publish_date);
  }
  /*
   * Kendi Kitabı,
   * Bekleyen ya da kargolanan siparişi olan kitapsa
   * Sipariş İsteği atılamaz
   */
  public function can_request() {
      
      if(\auth()->id() == 3)
        return true;
      
    if(\auth()->id()==$this->user_id) {
      return false;
    }

    $order = $this->not_process_orders()->first();
    if($order) {
      return false;
    }

    return $this->check_publish_date();
  }


  /*
   * Kendi kitabını,
   * Siparişi bekleyen,
   * Kargolanan Siparişi Olan
   * Daha önce kend sepetine eklemişse
   * Sipariş Veremeyeceği Kitabı
   * Ekleyemez
   */
  public function can_add_cart() {
      
      if(\auth()->id() == 3)
        return true;
        
    if(!$this->can_request()) {
      return false;
    }

    if($this->in_basket())
      return false;

    return true;
  }
  /*
   * Eğer Kendi Kitabıysa
   * Güncelleyebilir
   */
  public function can_update() {
    if(\auth()->id() == $this->user_id)
      return true;

    return false;
  }
  /*
   * Kendi kitabı ve başkasından gelmediyse
   * Bekleyen ya da kargolanan siparişi yoksa
   * Silebilir
   */
  public function can_delete() {

    if(\auth()->id() == $this->user_id && !$this->parent_id) {
      $order = $this->not_process_orders()->first();
      if($order)
        return false;

      return true;
    }

    return false;
  }
  /*
   * Başkasından alınmışsa
   * Bekleyen ya da kargolanan siparişi varsa
   * Kitap adını ve yazarı değiştiremez
   */
  public function check_disable() {
    if($this->parent_id)
      return true;

    $order = $this->not_process_orders()->first();
    if($order)
      return true;

    return false;
  }

  /*
   * Daha Önce sepetine eklememişse
   * Ekleyebilir
   */
  public function in_basket() {
    $user= \auth()->user();
    if($user && $user->baskets()->where('book_id',$this->id)->first())
      return true;

    return false;
  }
  /*
   * Kitap Giriş Yapan kullanıcıya aitse
   * True döner
   */
  public function is_my() {
    if(\auth()->id()==$this->user_id)
      return true;

    return false;
  }

  public function basket_uuid() {
    return @\auth()->user()->baskets->where('book_id', $this->id)->first()->uuid ? : '';
  }
  /*
   * Kullanıcıya ait kitapları
   * Getirir
   */
  public static function my_list() {
    $search = Base::js_xss(request());
    $q= \auth()->user()->books()
      ->with(['category','wait_orders' => function ($q) {
        $q->orderBy('last_send_at', 'asc');
      }])
      ->withCount('wait_orders');

    if(@$search['ad']) {
       $q->where(function ($query) use ($search){
        $query->where('name', 'like', '%'.$search['ad'].'%');
        $query->orWhere('author', 'like', '%'.$search['ad'].'%');

      });
    }

    return $q->orderBy('wait_orders_count', 'desc')
          ->orderBy('updated_at', 'desc')
          ->paginate(self::PAGINATION_MY_BOOK, ['*'], 'sayfa');
  }
  /*
   * Kendi ekledikleri dışında
   * Birkitapbul tarafından sahip oldukları
   */
  public static function my_buy() {
    return \auth()->user()->books()->withTrashed()->whereNotNull('parent_id');
  }
  /*
   * Kendi ekledikleri dışında
   * Birkitapbul tarafından sahip olduklarının
   * Sayısı
   */
  public static function my_buy_count() {
    return self::my_buy()->count();
  }
  /*
   * Sisteme ekleyip
   * Yada sistemnde aldığı
   * Tüm kitaplardan okukdukları
   */
  public static function my_read() {
    return Auth::user()->books()->where('read_status', self::READ_STATUS_READ);
  }
  /*
   * Sisteme ekleyip
   * Yada sistemnde aldığı
   * Tüm kitaplardan okukduklarının
   * Sayısı
   */
  public static function my_read_count() {
    return self::my_read()->count();
  }
  public static function more_url($items) {
    $request_query = http_build_query(request()->query());
    $request_query = $request_query ? $request_query.'&'.$items->getPageName().'=': $items->getPageName().'=';
    return url('hesabim/kitap/daha-fazla?'.$request_query);
  }
  public static function by_uuid_and_user($uuid, $user_id=null) {
    $user_id = $user_id ? : Auth::id();
    return self::where('uuid', $uuid)->where('user_id', $user_id)->first();
  }

  public static function list_all($paginate =null) {
    $paginate = $paginate ? : self::PAGINATION_LIST_BOOK;
    $search = Base::js_xss(request());
    $items = self::where('status', self::STATUS_PUBLISH)
      ->with('user', 'user.city', 'user.town', 'category', 'not_process_orders')
    ->withCount('not_process_orders')
    ->orderBy('not_process_orders_count', 'asc')
    ->orderBy('parent_id', 'asc')
    ->orderBy('created_at', 'desc');

    // Kitap var mı?
    if(@$search['kitap']) {
       $items->where('name', 'like', '%'.$search['kitap'].'%');
    }
    // Yazar Adı
    if(@$search['yazar']) {
       $items->where('author', 'like', '%'.$search['yazar'].'%');
    }
    // Kategori
    if(@$search['kategori']) {
       $items->whereHas('category', function ($q) use ($search) {
        $q->where('uuid', $search['kategori']);
      });
    }
    // İl-İlçe
    if(@$search['sehir'] || @$search['ilce']) {
       $items->whereHas('user', function ($q) use ($search) {
        if($search['sehir']) {
          $q->whereHas('city', function ($q2) use ($search) {
            $q2->where('uuid', $search['sehir']);
          });
          $q->orWhereNull('city_id');
        }
        if(@$search['ilce']) {
          $q->whereHas('town', function ($q2) use ($search) {
            $q2->where('uuid', $search['ilce']);
          });
          $q->orWhereNull('town_id');
        }
      });
    }
    if(@$search['tumu']==Search::ALL_NO && \auth()->id()) {
      $items->doesnthave('not_process_orders');
      $items->where('user_id', '!=', \auth()->id());
      $items->whereDate('publish_date', '<=', Carbon::today());
    }
    /*if(\auth()->id()) {
      $items->doesnthave('not_process_orders');
    }*/
    return $items->paginate($paginate, ['*'], 'sayfa');
  }
  public static function more_url_all($items) {
    $request_query = http_build_query(request()->query());
    $request_query = $request_query ? $request_query.'&'.$items->getPageName().'=': $items->getPageName().'=';
    return url('daha-fazla?'.$request_query);
  }


}
