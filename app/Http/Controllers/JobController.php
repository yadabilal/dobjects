<?php

namespace App\Http\Controllers;


use App\Model\Balance;
use App\Model\Job;
use App\Model\Notification;
use App\Model\Order;
use App\Model\Sms;
use App\Notifications\TaskComplete;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobController extends Controller
{
  // Aylık Bakiye Yükleme
  public function monthly() {

    return true;
    Log::info('=============== Aylık Ödeme Başladı ===============');

    $models = User::whereDay('created_at', Carbon::today()->format('d'))
      ->where('status', User::STATUS_COMPLETED)
      ->where('type', User::TYPE_USER)
      ->whereDoesntHave('balances', function($query) {
        $query->where('type', Balance::TYPE_MONTHLY);
        $query->whereMonth('created_at', Carbon::now()->format('m'));
      });

    Log::info($models->count(). ' Sonuç Bulundu.');

    DB::beginTransaction();

    foreach ($models->get() as $model) {
      $amount = User::DEFAULT_BALANCE;
      $now_balance = $model->total_balance;
      $total_balance= $now_balance + $amount;
      $model->update(['total_balance'=>  $total_balance]);

      $balance['user_id'] = $model->id;
      $balance['amount'] = $amount;
      $balance['before_balance'] = $now_balance;
      $balance['after_balance'] = $total_balance;
      $balance['type'] = Balance::TYPE_MONTHLY;
      $balance['title'] = Balance::monthly_title();
      $balance['description'] = Balance::monthly_description();
      $modelBalance= Balance::create($balance);

      Notification::balance_monthly($model, $modelBalance);

      Log::info($model->id.' IDli '.$model->full_name().' adlı kullanıcını bakiyesi yüklendi.');
    }

    DB::commit();

    Log::info('=============== Aylık Ödeme bitti ===============');
  }
  public function order_cancel() {

    Log::info('=============== OTOMATIK IPTAL ETME BAŞLADI ===============');

    $models = Order::where('status', Order::STATUS_NEW)
      ->whereDate('last_send_at', '<', Carbon::today());

    Log::info($models->count(). ' Sonuç Bulundu.');
    DB::beginTransaction();

    foreach ($models->get() as $model) {
      $update['status'] = Order::STATUS_CANCEL;
      $update['note'] = $model->sender->full_name().', istediğin '.$model->book->name.' adlı kitabı '.Order::WAITING_DAY_COUNT.' gün içinde kargolamadığı için otomatik iptal edildi.';
      $model->update($update);

      Log::info($model->id.' IDli sipariş iptal edildi.');
    }

    DB::commit();

    Log::info('=============== OTOMATIK IPTAL ETME BITTI ===============');
  }

  public function order_cancel_today() {

    Log::info('=============== IPTAL BİLDİRİMİ BUGUN BAŞLADI ===============');

    $models = Order::where('status', Order::STATUS_NEW)
      ->whereDate('last_send_at', Carbon::today());

    Log::info($models->count(). ' Sonuç Bulundu.');
    DB::beginTransaction();

    foreach ($models->get() as $model) {
      $task_complete= [
        'type' => Notification::TYPE_ORDER_DEMAND,
        'id' => $model->id,
        'title' => $model->book->name.' adlı kitabı kargolamak için bugün son gün!',
        'description' => $model->receiver->full_name(). ' adlı kullanıcının istediği '.$model->book->name.' adlı kitabı göndermen için bugün son gün! Göndermediğin takdirde birkitapbul.com kuralları geçerli olacaktır.',
        'url' => '',
      ];
      $model->sender->notify(new TaskComplete($task_complete));

      Sms::order_last_day($model);

      Log::info($model->id.' IDli sipariş için bildirim gönderildi.');
    }

    DB::commit();

    Log::info('=============== IPTAL BİLDİRİMİ BUGUN BITTI ===============');
  }

  public function order_cancel_tomorrow() {

    Log::info('=============== IPTAL BİLDİRİMİ YARIN BAŞLADI ===============');

    $models = Order::where('status', Order::STATUS_NEW)
      ->whereDate('last_send_at', Carbon::tomorrow());

    Log::info($models->count(). ' Sonuç Bulundu.');
    DB::beginTransaction();

    foreach ($models->get() as $model) {

      $task_complete= [
        'type' => Notification::TYPE_ORDER_DEMAND,
        'id' => $model->id,
        'title' => $model->book->name.' adlı kitabı kargolamak için yarın son gün!',
        'description' => $model->receiver->full_name(). ' adlı kullanıcının istediği '.$model->book->name.' adlı kitabı göndermen için yarın son gün! Göndermediğin takdirde birkitapbul.com kuralları geçerli olacaktır.',
        'url' => '',
      ];
      $model->sender->notify(new TaskComplete($task_complete));
      Sms::order_last_one_day($model);

      Log::info($model->id.' IDli sipariş için bildirim gönderildi.');
    }

    DB::commit();

    Log::info('=============== IPTAL BİLDİRİMİ YARIN BİTTİ ===============');
  }
  public function order_completed() {

    Log::info('=============== SİPARİŞ TAMAMLANDI BAŞLADI ===============');

    $models = Order::where('status', Order::STATUS_CARGO)
      ->whereDate('last_completed_date', '<',Carbon::today());

    Log::info($models->count(). ' Sonuç Bulundu.');
    DB::beginTransaction();

    foreach ($models->get() as $model) {
      Sms::completed_order($model);
      /*$update['status'] = Order::STATUS_COMPLETED;
      $model->update($update);
      */
      Log::info($model->id.' IDli sipariş tamamlandı.');
    }

    DB::commit();

    Log::info('=============== SİPARİŞ TAMAMLANDI BİTTİ ===============');
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

  public function not_completed_user() {

    return true;
    Log::info('=============== Tamamlanmayan kullanıcı başladı ===============');

    $models = User::where('status', User::STATUS_STEP_SECOND);
    //dd($models->get());
    Log::info($models->count(). ' Sonuç Bulundu.');
    DB::beginTransaction();

    foreach ($models->get() as $model) {
      Sms::not_completed_user($model);
      Log::info($model->id.' IDli kullanıcıya gönderildi tamamlandı.');
    }

    DB::commit();

    Log::info('=============== Tamamlanmayan kullanıcı BİTTİ ===============');
  }

 public function deneme2()
{
  //Sms::send("Test birkitapbul", "0553 813 23 04");
  Sms::send("birkitapbul.com dan istediğin Germinal (Emile Zola) adlı kitap eline ulaştıysa giriş yaparak kitabın eline ulaştığını bildir. Detay için giriş yap.", "05346326393");

}
  public function doesnthave_book_user() {
    return true;
    Log::info('=============== Kitap Eklemeyen Kullanıcılar ===============');

    $models = User::where('status', User::STATUS_COMPLETED)
    ->where('type', User::TYPE_USER)->doesnthave('books');

    Log::info($models->count(). ' Sonuç Bulundu.');
    DB::beginTransaction();
    //dd($models->get());
    foreach ($models->get() as $model) {
      Sms::doesnt_book($model);
      Log::info($model->id.' IDli kullanıcıya gönderildi tamamlandı.');
    }

    DB::commit();

    Log::info('=============== Kitap Eklemeyen Kullanıcılar ===============');
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
