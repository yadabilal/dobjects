<?php

namespace App\Model;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Base extends Model
{
  const PAGINATION_MY_LIST = 5;
  const PAGINATION_LIST_ADMIN = 10;

  protected $field_uuid = 'uuid';

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      if(in_array($model->field_uuid, $model->getFillable())) {
        $model->uuid = (string) Str::uuid();
      }
    });
    self::created(function($model){
      // Eğer Dosya Varsa Kaydet
      if(request()->file('galeries')) {
        File::upload(request()->file('galeries'), $model);
      }
    });
    self::saving(function($model){
      if(request()->file('galeries')) {
        File::upload(request()->file('galeries'), $model);
      }
    });
    self::updating(function($model){

    });

    self::updated(function($model){
    });

    self::deleting(function($model){
    });

    self::deleted(function($model){
      /*if(@$model->files[0]) {
        $model->files[0]->delete();
      }*/
    });
  }

    public function twoFiles() {
        return $this->morphMany(File::class, 'files', 'model_name', 'model_id')
            ->orderBy('shorting', 'asc')->limit(2);
    }

  public function files() {
    return $this->morphMany(File::class, 'files', 'model_name', 'model_id')
      ->orderBy('shorting', 'asc');
  }

  public function file() {
        return $this->morphOne(File::class, 'files', 'model_name', 'model_id')
            ->orderBy('shorting', 'asc');
  }

    public function lastFile() {
        return $this->morphOne(File::class, 'files', 'model_name', 'model_id')
            ->orderBy('id', 'desc');
    }

  public function image() {

    if($this->file) {
      return  url('uploads/'.$this->file->path);
    }
    return '';
  }

  // Created User
  public function created_user() {
    return $this->belongsTo(User::class, 'user_id');
  }
  // Created At
  public function created_at() {
    return Base::time_read($this->created_at);
  }

    // Para birimi
    public static function currency() {
        return "₺";
    }

    public static function amountFormatterWithCurrency($amount) {
      return self::decimalFormat($amount).self::currency();
    }

    public static function decimalFormat($value) {
        return number_format($value,2,',','.');
    }
  //
  public static function by_uuid($uuid) {
    return self::where('uuid', $uuid)->first();
  }

  public static function js_xss($request) {
    $inputs = $request->all();
    array_walk_recursive($inputs, function(&$inputs) {
      $inputs = trim(strip_tags($inputs));
    });

    return $inputs;
  }

  public static function time_read($time) {
    return Carbon::parse($time)->format('d.m.Y H:i');
  }
  public static function date_read($time) {
    return Carbon::parse($time)->format('d.m.Y');
  }
  public static function error_messages() {
    return [
      'required' => 'Bu alan zorunludur.',
      'min'    => 'En az :min karakter girilmelidir.',
      'max'    => 'En fazla :max karakter girilmelidir.',
      'latitude.required'    => 'Haritadan adresini seçmelisin!',
      'reading_start_date.before_or_equal' => 'Seçilen tarih bugün ya da daha öncesi olmalıdır.',
      'reading_end_date.after_or_equal' => 'Seçilen tarih okuma tarihine eşit ya da daha sonra olmalıdır.',
      'integer' => 'Bu alan sadece sayı olabilir.',
      'phone.unique' => 'Telefon numaran sana özel, benzersiz olmalıdır.',
      'email.unique' => 'Email adresin sana özel, benzersiz olmalıdır.',
      'username.unique' => 'Kullanıcı adın sana özel, benzersiz olmalıdır.',
      'username.alpha_dash' => 'Kullanıcı adın yalnızca harf, rakam, tire ve alt çizgi içerebilir.',
      'city_id.exists' => 'Seçtiğin şehir hatalıdır.',
      'town_id.exists' => 'Seçtiğin ilçe hatalıdır.',
      'exists' => 'Geçersiz seçim',
      'identify' => 'Tc kimlik numaran ile girdiğin bilgiler eşleşmiyor.',
      'without_spaces' => 'Boşluk bırakamazsın.',
      'gender' => 'Cinsiyet bilgini doğru seçmedin.',
      'digits' => 'Sadece rakam ve :digits karakter gir.',
      'confirm_password'=> 'Şifreler uyuşmuyor.',
      'now_password'=> 'Hatalı şifre.',
      'email'=> 'Girdiğin email adresi gerçersiz.',
    ];
  }
}
