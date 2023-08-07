<?php

namespace App\Model;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Order extends Base
{
  use SoftDeletes;

  const STATUS_WAITING_PAYMENT = 'WAITING_PAYMENT';
  const STATUS_NEW = 'NEW';
  const STATUS_PROCCESS = 'PROCCESS';
  const STATUS_CANCEL = 'CANCEL';
  const STATUS_ERROR = 'ERROR';
  const STATUS_CARGO = 'CARGO';
  const STATUS_COMPLETED = 'COMPLETED';

  const MESSAGE_USER_NOTE = 'USER_NOTE';
  const MESSAGE_CANCEL_NOTE = 'CANCEL_NOTE';
  const MESSAGE_PAYMENT_NOTE = 'PAYMENT_NOTE';

  //
  const PAGINATION_LIST = 10;
    public $store_path = 'orders';
  protected $table = 'orders';

  protected $fillable = [
    'uuid',  'status', 'number',
    'user_id', 'address_id',
    'total_quantity', 'total_price',
      'total_discount_price', 'discount_price',
      'cargo_id','folow_number', 'extra_messages',
      'payment_reference', 'payment_payload', 'billing_address_id'
  ];


    public function items() {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

  // Kullanıcı
  public function user() {
    return $this->belongsTo(User::class, 'user_id');
  }

  // Receiver
  public function address() {
    return $this->belongsTo(Address::class, 'address_id')->where('type', Address::TYPE_SHIPPING)
        ->withTrashed();
  }

    public function billing_address() {
        return $this->belongsTo(Address::class, 'billing_address_id')
            ->where('type', Address::TYPE_BILLING)
            ->withTrashed();
    }

  public function cargo() {
    return $this->belongsTo(CargoCompany::class, 'cargo_id')->withTrashed();
  }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items');
    }

  public function cargo_url() {
      return $this->cargo->folow_url;
  }

    public function readableTotalDiscountPrice() {
        return self::decimalFormat($this->total_discount_price).self::currency();
    }

    public function status($for_admin = false) {
      return @self::status_list($for_admin)[$this->status] ?: '';
    }
    public function status_color($for_admin = false) {
        return @self::color_list($for_admin)[$this->status] ?: '';
    }


    public function checkPayment($canSave = true) {

        if($this->status != self::STATUS_WAITING_PAYMENT) {
            return false;
        }

        $conversationId = time();
        $options = new \Iyzipay\Options();
        $options->setApiKey(env('IYZICO_API_KEY'));
        $options->setSecretKey(env('IYZICO_API_SECRET'));
        $options->setBaseUrl(env('IYZICO_BASE_URL'));

        $request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($conversationId);
        $request->setToken($this->payment_reference);

        $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, $options);

        if(strtolower($checkoutForm->getPaymentStatus()) == 'success' && $conversationId == $checkoutForm->getConversationId()) {
            $this->status = Order::STATUS_NEW;
            $this->save();

            try {
                $contents = [];
                $orderItems = $this->items;
                foreach ($orderItems as $orderItem) {
                    $content['id'] = $orderItem->product_id;
                    $content['quantity'] = $orderItem->quantity;
                    $content['item_price'] = $orderItem->discount_price;
                    $contents[] = $content;
                }

                $facebook = new Facebook();
                $facebook->event = Facebook::EVENT_SHOP;
                $facebook->sourceUrl = request()->url();
                $facebook->user = $this->user;
                $facebook->customData['contents'] = $contents;
                $result = $facebook->events($this->setting);
            }catch (\Exception $e) {}

            return $this;
        }else if($checkoutForm->getPaymentStatus() && $checkoutForm->getErrorCode()) {
            $this->status = Order::STATUS_ERROR;
            $this->setNote(Order::MESSAGE_PAYMENT_NOTE, $checkoutForm->getErrorMessage() ?: 'Ödeme Hatası. Hata Kodu: '.$checkoutForm->getErrorCode());
            return $this->save();
        }

        return false;
    }
  protected static function boot(){
    parent::boot();

    static::creating(function ($model) {
      $model->status = self::STATUS_WAITING_PAYMENT;
      $model->user_id = \auth()->id();
    });

    static::created(function ($model) {
        $job = new Job();
        $job->type = Job::TYPE_WAITING_PAYMENT;
        $job->send_at = Carbon::now()->addMinute(Job::WAITING_PAYMENT_MINUTE);
        $job->contact = $model->id;
        $job->save();
    });

    static::saving(function ($model) {
        if($model->isDirty('cargo_id') && $model->status == self::STATUS_CARGO){
            Sms::order_cargo($model);
        }

        if($model->isDirty('status') && $model->status == self::STATUS_NEW) {
            Sms::new_order($model);
            Basket::where('user_id', \auth()->id())->delete();
        }
    });

  }

  /*
   * Siparişe ait
   * Durum Bilgilerini
   * Listeler
   */
  public static function status_list($for_admin = false) {

      if($for_admin) {
          return [
              self::STATUS_ERROR => 'Ödeme Hatası',
              self::STATUS_WAITING_PAYMENT => 'Ödeme Bekleniyor',
              self::STATUS_NEW => 'Yeni',
              self::STATUS_PROCCESS => 'Hazırlanıyor',
              self::STATUS_CARGO => 'Kargolandı',
              self::STATUS_CANCEL => 'İptal Edildi',
              self::STATUS_COMPLETED => 'Teslim Edildi',
          ];
      }

    return [
      self::STATUS_NEW => 'İşleme Alındı',
      self::STATUS_PROCCESS => 'Hazırlanıyor',
      self::STATUS_CARGO => 'Kargolandı',
      self::STATUS_CANCEL => 'İptal Edildi',
      self::STATUS_COMPLETED => 'Teslim Edildi',
    ];
  }

  public function setNote($key, $note) {
      $messages = $this->extra_messages ? json_decode($this->extra_messages, true) : [];
      $messages[$key] = $note;
      $this->extra_messages = json_encode($messages);

      return $this->extra_messages;
  }

    public function getNote($key) {
        $messages = @json_decode($this->extra_messages, true) ?: [];

        return @$messages[$key] ?: '';
    }


  /*
   * Siparişe Ait Renk
   * Listesini Döner
   */
  public static function color_list() {
    return [
      self::STATUS_WAITING_PAYMENT => 'badge badge-warning',
      self::STATUS_NEW => 'badge badge-info',
      self::STATUS_PROCCESS => 'badge badge-warning',
      self::STATUS_CARGO => 'badge badge-secondary',
      self::STATUS_CANCEL => 'badge badge-danger',
      self::STATUS_ERROR => 'badge badge-danger',
      self::STATUS_COMPLETED => 'badge badge-success',
    ];
  }

}
