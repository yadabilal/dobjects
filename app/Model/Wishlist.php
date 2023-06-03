<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wishlist extends Base
{
  use SoftDeletes;

  const PAGINATION_LIST = 10;

  const LIST_SHORT_COUNT = 3;
  const PAGINATION_LIST_ADMIN = 10;

  protected $table = 'wishlists';
  protected $fillable = [ 'uuid', 'product_id', 'user_id'];

  // Kullanıcı
  public function user() {
    return $this->belongsTo(User::class, 'user_id');
  }

  // Ürün
  public function product() {
    return $this->belongsTo(Product::class, 'product_id')
        ->where('status', Product::STATUS_PUBLISH)
        ->with('file');
  }


    public function can_delete() {
      return $this->user_id == auth()->id();
    }

  // Sepete Ekle
  public static function add($product, $userId = null) {
      $userId = $userId ? : \auth()->id();
    $item = self::where('product_id', $product->id)
        ->where('user_id', $userId)
        ->first();

    if(!$item) {
        $item= new self();
        $item->product_id = $product->id;
        $item->user_id = $userId;
        $item->save();

    }

    return $item;
  }

  // Sepetten Çıkar
  public static function remove($productId, $userId=null) {
      $userId = $userId ? : \auth()->id();

    return self::where('book_id', $productId)->where('user_id', $userId)->delete();
  }
}
