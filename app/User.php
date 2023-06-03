<?php

namespace App;

use App\Model\Address;
use App\Model\Base;
use App\Model\Basket;
use App\Model\City;
use App\Model\File;
use App\Model\Order;
use App\Model\PhoneLog;
use App\Model\Sms;
use App\Model\Town;
use App\Model\UserPermission;
use App\Model\Wishlist;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;

class User extends Authenticatable
{
    use Notifiable;
    // Status
    const STATUS_STEP_SECOND = 'STEP_SECOND';
    const STATUS_STEP_THIRD = 'STEP_THIRD';
    const STATUS_COMPLETED = 'COMPLETED';

    const DEFAULT_BALANCE = 4;
    // Types
    const TYPE_USER = 'U';
    const TYPE_ADMIN = 'A';

    // Genders
    const GENDER_MALE = 'MALE';
    const GENDER_FEMALE = 'FEMALE';

    const LIST_ADMIN = 10;

    public $store_path = 'users';

    protected $fillable = [
      'uuid', 'total_balance', 'name', 'surname',
      'about', 'username', 'status','identify',
      'city_id', 'town_id','type', 'gender',
      'birth_date', 'phone', 'phone_code','phone_verified_at',
      'password', 'identify_verified_at', 'address', 'type'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'phone_verified_at' => 'datetime'
    ];

  // Tüm Siparişler
  public function orders() {
    return $this->hasMany(Order::class, 'user_id')->orderBy('id', 'desc')->with('address', 'cargo', 'lastFile');
  }

    // Tüm Siparişler
    public function waitingOrders() {
        return $this->hasMany(Order::class, 'user_id')->orderBy('id', 'desc')->with('address', 'cargo')->where('status', Order::STATUS_NEW);
    }

  public function files() {
    return $this->morphMany(File::class, 'files', 'model_name', 'model_id')
      ->orderBy('created_at', 'desc');
  }
  // izinler
  public function permissions() {
    return $this->hasMany(UserPermission::class, 'user_id')->orderBy('id', 'desc');
  }
  // izinler
  public function baskets() {
    return $this->hasMany(Basket::class, 'user_id')
        ->orderBy('id', 'desc')
        ->has('product')
        ->with('product');
  }


    public function wishlists() {
        return $this->hasMany(Wishlist::class, 'user_id')
            ->orderBy('id', 'desc')
            ->has('product')
            ->with('product');
    }

  public function basketTotals() {
      $totalPrice = 0;
      $totalFinallyPrice = 0;

      foreach ($this->baskets as $basket) {
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
  public function city() {
    return $this->belongsTo(City::class, 'city_id');
  }

  public function town() {
    return $this->belongsTo(Town::class, 'town_id');
  }

    public function address() {
        return $this->hasOne(Address::class, 'user_id');
    }


  public function notifications()
  {
    return $this->morphMany(DatabaseNotification::class, 'notifiable')
      ->orderBy('created_at', 'desc')
      ->orderBy('read_at', 'asc');
  }

  public function can_order() {
      return $this->status == self::STATUS_COMPLETED;
  }

    public function can_comment() {
        return $this->status == self::STATUS_COMPLETED;
    }

  /*
   * Adres Bilgisi
   * İlçe, İl
   */
  public function city_town() {
    $all = '';
    if($this->town) {
      $all = $this->town->name;
    }
    if($this->city) {
      $all = $all ? $all.', '.$this->city->name : $this->city->name;
    }
    return $all;
  }

  /*
   * Kullanıcı tam adı
   * Ad Soyad
   */
  public function full_name() {
    return $this->name.' '.$this->surname;
  }

  /*
   * Kullanıcı adı
   */
  public function user_name() {
    return '@'.$this->username;
  }
  /*
   * Profil Resmi
   */
  public function image() {
    $default = asset('theme/assets/img/avatars/no_gender.png');
    if($this->gender == User::GENDER_FEMALE) {
      $default = asset('theme/assets/img/avatars/woman.png');
    }else if($this->gender == User::GENDER_MALE) {
      $default = asset('theme/assets/img/avatars/man.png');
    }
    if($this->files) {
      return @$this->files[0] ? url('uploads/'.$this->files[0]->path) : $default;
    }
    return $default;
  }

  public function created_at() {
    return Base::time_read($this->created_at);
  }

  public function status() {
    $status = [
      self::STATUS_COMPLETED => 'Onaylandı',
      self::STATUS_STEP_SECOND => 'Telefon Onayı Bekliyor',
      self::STATUS_STEP_THIRD => 'Kullanıcı Adı Belirlemeyi Bekliyor',
    ];
    return @$status[$this->status] ? : 'Durum Bulunamadı!';
  }

  protected static function boot(){
    parent::boot();
    // İlk Oluşma
    static::creating(function ($model) {
      $model->uuid = (string) Str::uuid();
      $model->username = Str::lower($model->username);
      $model->phone = $model->phone ? PhoneNumber::make($model->phone, 'TR')->formatForCountry('TR') : null;
    });
    // Kullanıcı Güncellerken
    static::updating(function ($model) {
      $changes = [];
      foreach($model->getDirty() as $key => $value){
        $original = $model->getOriginal($key);
        $changes[$key] = $original;
      }

      //Eğer Telefon Numarası Değiştirilmişse yeni kod yolla onaylanma durumunu null yap adımı ikiye çek
      if($model->isDirty('phone')) {
        try{
          $data = [];
          $data['phone'] = $model->phone;
          $data['phone_verified_at'] = $model->phone_verified_at;
          $data['user_id'] = $model->id;
          PhoneLog::create($data);
        }catch (\Exception $e) {
          Log::info($model->id.' Eski telefon numarası kaydedilirken hata oluştu --> '.$e->getMessage());
        }

        $model->phone_code = rand(1000, 9999);
        //$model->phone_verified_at = null;
        $model->status = User::STATUS_STEP_SECOND;
        $model->phone = PhoneNumber::make($model->phone, 'TR')->formatForCountry('TR');
        /*
         * TODO : CANlıya Alırken Yorumu aç!!
         */
        //Sms::confirm_code($model);
      }


      $model->username = Str::lower($model->username);

      if(request()->file('files')) {
        File::upload(request()->file('files'), $model);
      }

    });

    self::saving(function($model){
      if(request()->file('files')) {
        File::upload(request()->file('files'), $model);
      }
    });

  }

  public static function genders() {
    return [
      self::GENDER_FEMALE => 'Kadın',
      self::GENDER_MALE => 'Erkek'
    ];
  }

  public static function hash_pasword ($pass) {
    return Hash::make($pass);
  }
}
