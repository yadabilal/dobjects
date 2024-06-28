<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Base
{
    use SoftDeletes;

    const MAX_ORDER_COUNT = 10;
    const PAGINATION_COUNT = 12;
    const STATUS_PUBLISH = 'PUBLISH';
    const STATUS_NOT_PUBLISH = 'NOT_PUBLISH';

    public $store_path = 'products';

    protected $table = 'products';
    protected $fillable = [
        'uuid', 'tags', 'name', 'sorting',
        'url', 'meta_description', 'short_description',
        'description','additional_information','status', 'stock',
        'discount_rate', 'price', 'discount_price', 'category_id', 'show_home_page', 'is_accesorio'
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->status = $model->status ? :self::STATUS_NOT_PUBLISH;
        });

        self::updating(function($model){

        });
        self::saving(function($model){
            $model->url = $model->url ?: Str::slug($model->name);
        });

        self::deleted(function($model){
            $model->baskets()->delete();
        });
    }

    // Bekleyen Sipariş
    public function waitingOrders() {
        return $this->hasMany(OrderItem::class, 'product_id')
            ->orderBy('id', 'desc')
            ->whereHas('order', function ($q) {
                $q->where('status', Order::STATUS_NEW);
            });

    }

    // Tamamı
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items');
    }

    public function newOrders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->where('orders.status', Order::STATUS_NEW)
            ->with('user', 'items', 'address', 'address.city', 'address.town')
                ->withCount('items');
    }


    // Sepet
    public function baskets() {
        return $this->hasMany(Basket::class, 'product_id')->orderBy('id', 'desc');
    }

  // Yorum
    public function comments() {
        return $this->hasMany(Comment::class, 'product_id')->where('status', Comment::STATUS_PUBLISH)->orderBy('id', 'desc');
    }

    // Yorum ortalaması
    public function avgRating()
    {
        return $this->comments()
            ->selectRaw('avg(rate) as aggregate, product_id')
            ->groupBy('product_id');
    }

    // Yorum ortalama okunabilir
    public function getAvgRatingAttribute()
    {
        if ( ! array_key_exists('avgRating', $this->relations)) {
            $this->load('avgRating');
        }

        $relation = $this->getRelation('avgRating')->first();

        return ($relation) ? ceil($relation->aggregate) : null;
    }

    // Category
    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }


    // Sepete eklenebilir mi?
    public function can_add_cart() {

        if(\auth()->id() == 3)
            return true;

        if($this->in_basket())
            return false;

        return true;
    }
    // Daha önce sepete eklenmiş mi?
    public function in_basket() {
        $user= \auth()->user();
        if($user && $user->baskets()->where('product_id',$this->id)->first())
            return true;

        return false;
    }

    public function in_wishlist() {
        $user= \auth()->user();
        if($user && $user->wishlists()->where('product_id',$this->id)->first())
            return true;

        return false;
    }

    public function seo_title() {
        return $this->name;
    }
    public function seo_tags() {
        return $this->tags.','.$this->category->name;
    }
    public function seo_description() {
        return $this->meta_description ?: $this->seo_title();
    }

    public function readableTags() {

      $tags = [];
      $allTags = $this->id ? explode(',', $this->tags) : [];

      foreach ($allTags as $allTag) {
          $tag = trim($allTag);
          $tags[] = '<a href="'.route('home').'?tag='.$tag.'">'.$tag.'</a>';
      }

      return implode(',', $tags);
    }

    public function can_comment() {
        $orderDetails = OrderItem::where('product_id', $this->id)
            ->where('user_id', auth()->id())
            ->whereHas('order', function($q){
                $q->where('status', Order::STATUS_COMPLETED);
            })
            ->with('order')
            ->first();

        return $orderDetails ? true : false;
    }

    // İndirimde mi?
    public function isDiscount() {
        return (int)$this->discount_rate ? true : false;
    }

    // Kullanıcı indirim durumu
    public function readableDisCountRate() {
        return $this->discount_rate ? "-".$this->discount_rate."%" : '0%';
    }
    public function readablePrice() {
          return self::decimalFormat($this->price).self::currency();
    }
    public function readableDiscountPrice() {
        return self::decimalFormat($this->discount_price).self::currency();
    }

    public function readableDiscountPriceWithQuantity($quantity){
        return self::decimalFormat($this->discount_price*$quantity).self::currency();
    }

    public function readablePriceWithQuantity($quantity){
        return self::decimalFormat($this->price*$quantity).self::currency();
    }

    public function addFavoriteUrl() {
        return route('wishlist.add', ['uuid' => $this->uuid]);
    }

    public function deleteFavoriteUrl() {
        return route('wishlist.delete', ['uuid' => $this->uuid]);
    }

    public function detailUrl() {
        return route('product.show', ['url' => $this->url]);
    }
    public function shareTwitterUrl() {
      return "http://twitter.com/home/?status=".$this->detailUrl()."&t=İşte benim tarzım!";
    }
    public function shareFacebookUrl() {
        return "https://www.facebook.com/sharer/sharer.php?u=".$this->detailUrl()."&t=İşte benim tarzım!";
    }

    public function readableStatus() {
        return @self::statues()[$this->status] ?: $this->status;
    }

    public function readableStatusColor() {
        return @self::statusColors()[$this->status] ?: $this->status;
    }
    public static function shortingUrls() {
        $urls = [];
        $query = request()->query();
        $url = request()->url();

        $query['sirala'] = "fiyat";
        $query['durum'] = "artan";

        $urls[] = [
            "title" => "Fiyata Göre [Düşük-Yüksek]",
            "url" => $url."?".http_build_query($query)
        ];

        $query['sirala'] = "fiyat";
        $query['durum'] = "azalan";

        $urls[] = [
            "title" => "Fiyata Göre [Yüksek-Düşük]",
            "url" => $url."?".http_build_query($query)
        ];

        $query['sirala'] = "tarih";
        $query['durum'] = "yeni";

        $urls[] = [
            "title" => "Tarihe Göre [Yeni-Eski]",
            "url" => $url."?".http_build_query($query)
        ];

        $query['sirala'] = "tarih";
        $query['durum'] = "eski";

        $urls[] = [
            "title" => "Tarihe Göre [Eski-Yeni]",
            "url" => $url."?".http_build_query($query)
        ];

        $query['sirala'] = "ad";
        $query['durum'] = "a-z";

        $urls[] = [
            "title" => "Ürün Adına Göre [A-Z]",
            "url" => $url."?".http_build_query($query)
        ];

        $query['sirala'] = "ad";
        $query['durum'] = "z-a";

        $urls[] = [
            "title" => "Ürün Adına Göre [Z-A]",
            "url" => $url."?".http_build_query($query)
        ];

        return $urls;
    }
    public static function list_all($paginate =null, $waiting_orders = false, $withRate = false, $discount= false, $type = 'all') {
        $paginate = $paginate ? : self::PAGINATION_COUNT;
        $search = Base::js_xss(request());
        $items = self::with("category", "files");

        if($withRate) {
            $items->with("avgRating");
        }

        if($discount) {
            $items->where('discount_rate', '>', 0);
        }

        if($type == 'accesorio') {
            $items->where('is_accesorio', '=', 1);
        }else if($type == 'no_accesorio') {
            $items->where('is_accesorio', '=', 0);
        }

        // Ürün adına göre
        if(@$search['urun']) {
            $items->where('name', 'like', '%'.$search['urun'].'%');
        }

        // Taga göre
        if(@$search['tag']) {
            $items->where('tags', 'like', '%'.$search['tag'].'%');
        }

        // Kategori
        if(@$search['kategori']) {
            $items->whereHas('category', function ($q) use ($search) {
                $q->where('url', $search['kategori']);
            });
        }

        // Sıralama yap
        if(@$search['sirala']) {
            if($search['sirala'] == 'tarih'){
                if($search['durum'] == 'yeni') {
                    $items = $items->orderBy('created_at', 'desc');
                }else {
                    $items = $items->orderBy('created_at', 'asc');
                }
            }

            if($search['sirala'] == 'ad'){
                if($search['durum'] == 'a-z') {
                    $items = $items->orderBy('name', 'asc');
                }else {
                    $items = $items->orderBy('name', 'desc');
                }
            }

            if($search['sirala'] == 'fiyat'){
                if($search['durum'] == 'artan') {
                    $items = $items->orderBy('discount_price', 'asc');
                }else {
                    $items = $items->orderBy('discount_price', 'desc');
                }
            }
        }else {

            if($waiting_orders) {
                $items = $items->orderBy('created_at', 'desc');
            }else {
                $items = $items->orderBy('sorting')->orderBy('created_at', 'desc');
            }
        }

        if($waiting_orders) {
            if(@$search['order_by'] == 'waitingOrders_count') {
                $items = $items->withCount('waitingOrders')->orderBy('waiting_orders_count', @$search['dir'] ? : 'desc');
            }else {
                $items =$items->withCount('waitingOrders')->orderBy('id', 'desc');
            }
            $items = $items->withCount('baskets');
        }else {
            $items = $items->where('status', self::STATUS_PUBLISH);
        }

        return $items->paginate($paginate, ['*'], 'sayfa');
    }
    public static function list_all_count() {
        return self::where('status', self::STATUS_PUBLISH)
            ->orderBy('created_at', 'desc')
            ->count();
    }
    public static function findByUrl($url) {
        return self::where('status', self::STATUS_PUBLISH)
            ->with("category", "files")
            ->with("comments", "comments.user")
            ->withCount('comments')
            ->with("avgRating")
            ->where('url', $url)
            ->first();
    }

    public static function statues() {
        return [
            self::STATUS_PUBLISH => 'Yayında',
            self::STATUS_NOT_PUBLISH => 'Yayında Değil',
        ];
    }

    public static function statusColors() {
        return [
            self::STATUS_PUBLISH => 'badge badge-success',
            self::STATUS_NOT_PUBLISH => 'badge badge-danger',
        ];
    }

}
