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
      'discount_price', 'total_price', 'total_discount_price', 'note', 'session_id'];

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
      return auth()->id() ? $this->user_id == auth()->id() : $this->session_id == session()->getId();
    }

    public static function sumQuantity($userId = null) {
        $baskets = self::orderBy('id', 'desc')
            ->has('product')
            ->with('product', 'user');
        $userId = $userId ? : \auth()->id();

        if($userId) {
            $baskets->where('user_id', $userId);
        }else {
            $baskets->where('session_id', session()->getId());
        }

        return $baskets->sum('quantity');
    }

    public static function sumTotalDiscountPrice($userId = null) {
        $baskets = self::orderBy('id', 'desc')
            ->has('product')
            ->with('product', 'user');
        $userId = $userId ? : \auth()->id();

        if($userId) {
            $baskets->where('user_id', $userId);
        }else {
            $baskets->where('session_id', session()->getId());
        }

        return $baskets->sum('total_discount_price');
    }

    public static function sumTotalPrice($userId = null) {
        $baskets = self::orderBy('id', 'desc')
            ->has('product')
            ->with('product', 'user');
        $userId = $userId ? : \auth()->id();

        if($userId) {
            $baskets->where('user_id', $userId);
        }else {
            $baskets->where('session_id', session()->getId());
        }

        return $baskets->sum('total_price');
    }

    public static function totals($userId = null) {
        $totalPrice = 0;
        $totalFinallyPrice = 0;

        $baskets = self::getAll($userId);

        foreach ($baskets as $basket) {
            $totalPrice += $basket->product->price*$basket->quantity;
            $totalFinallyPrice += $basket->product->discount_price*$basket->quantity;
        }

        $totalDiscountPrice = $totalPrice-$totalFinallyPrice;

        return [
            'totalPrice' => $totalPrice,
            'totalDiscountPrice' => $totalDiscountPrice,
            'totalFinallyPrice' => $totalFinallyPrice
        ];
    }

    public static function getAll($userId = null, $oldSessionId = null) {
        $baskets = self::orderBy('id', 'desc')
          ->has('product')
          ->with('product', 'user');

        if(!$oldSessionId) {
            $userId = $userId ? : \auth()->id();

            if($userId) {
                $baskets->where('user_id', $userId);
            }else {
                $baskets->where('session_id', session()->getId());
            }
        }else {
            $baskets->whereNull('user_id');
            $baskets->where('session_id', $oldSessionId);
        }


      return $baskets->get();
    }

  // Sepete Ekle
  public static function add($product, $quantity = 1, $note = '', $userId = null) {
      $userId = $userId ? : \auth()->id();
      $basketElement = self::where('product_id', $product->id);

    if($userId) {
        $basketElement->where('user_id', $userId);
    }else {
        $basketElement->where('session_id', session()->getId());
    }

    $basketElement = $basketElement->first();

    if(!$basketElement) {
        $basketElement= new self();
        $basketElement->note = $note;
        $basketElement->product_id = $product->id;
        $basketElement->user_id = $userId;
        $basketElement->price = $product->price;
        $basketElement->quantity = $quantity;
        $basketElement->discount_price = $product->discount_price;
        $basketElement->total_price = $product->price*$quantity;
        $basketElement->total_discount_price = $product->discount_price*$quantity;
        $basketElement->session_id = session()->getId();
        $basketElement->save();

    }

    return $basketElement;
  }

  // Sepetten Çıkar
  public static function remove($productId, $userId=null) {
      $userId = $userId ? : \auth()->id();

    return self::where('book_id', $productId)->where('user_id', $userId)->delete();
  }
}
