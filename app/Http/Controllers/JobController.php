<?php

namespace App\Http\Controllers;

use App\Model\Balance;
use App\Model\Job;
use App\Model\Order;
use App\Model\Sms;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobController extends Controller
{

    public function waiting_payment() {

        $models = Job::where('status', Job::STATUS_WAITING)
            ->where('type', Job::TYPE_WAITING_PAYMENT)
            ->whereDate('send_at', '<=',Carbon::now())->get();

        foreach ($models as $model) {

            $order = Order::where('status', Order::STATUS_WAITING_PAYMENT)
                ->where('id', $model->contact)
                ->first();

            if($order) {

                if($order->checkPayment()) {
                    $model->delete();
                }
            }else {
                $model->delete();
            }
        }
    }

  public function send_sms() {

    Log::info('=============== SMS BAŞLADI ===============');

    $models = Job::where('status', Job::STATUS_WAITING)
      ->where('type', Job::TYPE_SMS)
      ->whereDate('send_at', '<=',Carbon::now());

    Log::info($models->count(). ' Sonuç Bulundu.');
    DB::beginTransaction();

    foreach ($models->get() as $model) {
      $result = Sms::send($model->content, $model->contact, $model->subject);
      $model->update(['status' => Job::STATUS_COMPLETED]);
      if($result) {
        Log::info($model->contact.' numarasına sms gönderildi.');
      }else {
        Log::error($model->contact.' numarasına sms gönderilemedi.');
      }
    }

    DB::commit();

    Log::info('=============== SMS BİTTİ ===============');
  }

  public function order_cargo() {
    return true;
    Log::info('=============== SİPARİŞ Kargo BAŞLADI ===============');

    $models = Order::where('status', Order::STATUS_CARGO);

    Log::info($models->count(). ' Sonuç Bulundu.');
    DB::beginTransaction();

    foreach ($models->get() as $model) {
      Sms::order_cargo($model);
      Log::info($model->id.' IDli sipariş tamamlandı.');
    }

    DB::commit();

    Log::info('=============== SİPARİŞ Kargo BİTTİ ===============');
  }

  public function in_basket() {
    return true;
    Log::info('=============== Sepetine Eklemeyen Kullanıcılar ===============');

    $models = User::where('status', User::STATUS_COMPLETED)
    ->where('type', User::TYPE_USER)->has('baskets');

    Log::info($models->count(). ' Sonuç Bulundu.');
    DB::beginTransaction();

    foreach ($models->get() as $model) {
      Sms::in_basket_error($model);
      Log::info($model->id.' IDli kullanıcıya gönderildi tamamlandı.');
    }

    DB::commit();

    Log::info('=============== Sepetine Ekleyen Kullanıcı bitiş ===============');
  }
}
