<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class TempBasket extends Base
{
  use SoftDeletes;

  const LIST_SHORT_COUNT = 3;
  const PAGINATION_LIST_ADMIN = 10;
  const DELETE_REASON_OWN = 'REASON_OWN';

  protected $table = 'temp_baskets';
  protected $fillable = [ 'uuid', 'product_id', 'session_id', 'quantity'];


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
      return $this->session_id == Session::getId();
    }

    public static function totalCount() {
        return self::where('session_id', Session::getId())
            ->count();
    }

  // Sepete Ekle
  public static function add($product, $quantity = 1) {
    $sessionId = Session::getId();
    $basketElement = self::where('product_id', $product->id)
        ->where('session_id', $sessionId)
        ->first();

    if(!$basketElement) {
        $basketElement= new self();
        $basketElement->product_id = $product->id;
        $basketElement->session_id = $sessionId;
        $basketElement->quantity = $quantity;
        $basketElement->save();

    }

    return $basketElement;
  }

  public static function addRealBasket() {
      $carts = self::getBaskets();

      if($carts) {
          foreach ($carts as $cart) {
              $realCart = Basket::add($cart->product, $cart->quantity);
              if($realCart) {
                  $cart->delete();
              }

          }
      }
  }

  public static function getBaskets () {
      $sessionId = Session::getId();
      return TempBasket::where('session_id', $sessionId)->with('product')->get();
  }
    public static function basketTotals($baskets) {
        $totalCount = 0;
        $totalPrice = 0;
        $totalFinallyPrice = 0;

        foreach ($baskets as $basket) {
            $totalCount +=$basket->quantity;
            $totalPrice += $basket->product->price*$basket->quantity;
            $totalFinallyPrice += $basket->product->discount_price*$basket->quantity;
        }

        $totalDiscountPrice = $totalPrice-$totalFinallyPrice;

        return [
            'totalCount' => $totalCount,
            'totalPrice' => $totalPrice,
            'totalDiscountPrice' => $totalDiscountPrice,
            'totalFinallyPrice' => $totalFinallyPrice
        ];
    }

  // Sepetten Çıkar
  public static function remove($productId) {
      $sessionId = Session::getId();

    return self::where('product_id', $productId)
        ->where('session_id', $sessionId)
        ->delete();
  }
}
