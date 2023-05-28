<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Basket extends Base
{
  use SoftDeletes;

  const LIST_SHORT_COUNT = 3;
  const PAGINATION_LIST_ADMIN = 10;
  const DELETE_REASON_OWN = 'REASON_OWN';

  protected $table = 'baskets';
  protected $fillable = [ 'uuid', 'product_id', 'user_id', 'quantity', 'price',
      'discount_price', 'total_price', 'total_discount_price', 'note'];

  // Kullanıcı
  public function user() {
    return $this->belongsTo(User::class, 'user_id');
  }

  // Ürün
  public function product() {
    return $this->belongsTo(Product::class, 'product_id')->with('file');
  }

    public function readablePrice() {
        return self::decimalFormat($this->product->price).self::currency();
    }
    public function readableDiscountPrice() {
        return self::decimalFormat($this->product->discount_price).self::currency();
    }

    public function readableTotalDiscountPrice() {
        return self::decimalFormat($this->product->discount_price*$this->quantity).self::currency();
    }


    public function can_delete() {
      return $this->user_id == auth()->id();
    }

  // Sepete Ekle
  public static function add($product, $quantity = 1, $note = '', $userId = null) {
      $userId = $userId ? : \auth()->id();
    $basketElement = self::where('product_id', $product->id)
        ->where('user_id', $userId)
        ->first();

    if(!$basketElement) {
        $basketElement= new self();
        $basketElement->note = $note;
        $basketElement->product_id = $product->id;
        $basketElement->user_id = $userId;
        $basketElement->quantity = $quantity;
        $basketElement->price = $product->price;
        $basketElement->discount_price = $product->discount_price;
        $basketElement->total_price = $product->price*$quantity;
        $basketElement->total_discount_price = $product->discount_price*$quantity;
        $basketElement->save();
    }else {
        $basketElement->quantity = $quantity;
        $basketElement->price = $product->price;
        $basketElement->discount_price = $product->discount_price;
        $basketElement->total_price = $product->price*$quantity;
        $basketElement->total_discount_price = $product->discount_price*$quantity;
        $basketElement->save();
    }

    return $basketElement;
  }

  // Sepetten Çıkar
  public static function remove($productId, $userId=null) {
      $userId = $userId ? : \auth()->id();

    return self::where('product_id', $productId)->where('user_id', $userId)->delete();
  }
}
