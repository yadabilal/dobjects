<?php

namespace App\Http\Controllers;

use App\Model\Balance;
use App\Model\HomePage;
use App\Model\Job;
use App\Model\Order;
use App\Model\Product;
use App\Model\Sms;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class JobController extends Controller
{

    public function test() {
        exit;
        Sms::send('test mesajı', '+905346326393', 'Test');
    }
    public function cacheImage() {
        $typeOnes = HomePage::where('status', HomePage::STATUS_PUBLISH)
            ->orderBy('sorting')
            ->get();

        foreach ($typeOnes as $typeOne){
            $pic = $typeOne->getPic();
            $height = 0;
            $width = 0;

            if($typeOne->type == HomePage::TYPE_1) {
                $height = 1080;
                $width = 1920;
            }

            if($typeOne->type == HomePage::TYPE_2) {
                $height = 105;
                $width = 105;
            }

            if($typeOne->type == HomePage::TYPE_3) {
                $height = 496;
                $width = 577;
            }

            if($typeOne->type == HomePage::TYPE_4) {
                $height = 258;
                $width = 258;
            }

            if($typeOne->type == HomePage::TYPE_5) {
                $height = 961;
                $width = 452;
            }

            if($typeOne->type == HomePage::TYPE_6) {
                $height = 303;
                $width = 366;
            }
            if($typeOne->type == HomePage::TYPE_7) {
                $height = 450;
                $width = 450;
            }

            if($pic && $width && $height) {
                echo $pic.'<br>';
                $img = Image::cache(function($image) use ($pic, $width, $height) {
                    return $image->make($pic)->resize($width, $height)->greyscale();
                });
            }
        }


        $items = Product::list_all();

        foreach ($items as $item) {
            echo "===========".$item->id.'==============<br>';
            foreach ($item->files as $file) {
                if($file && $file->path) {
                    $pic = url('uploads/'.$file->path);
                    echo $pic.'<br>';
                    $img = Image::cache(function($image) use ($pic) {
                        return $image->make($pic)->resize(1000, 1000)->greyscale();
                    });
                }
            }

        }
        echo 'bitti';
    }

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
      DB::transaction(function () {
          // Waiting olan ilk kaydı lockla ve al
          $job = DB::table('jobs')
              ->where('status', Job::STATUS_WAITING)
              ->where('type', Job::TYPE_SMS)
              ->whereDate('send_at', '<=',Carbon::now())
              ->whereNull('locked_at')
              ->orderBy('id')
              ->lockForUpdate()
              ->first();

          if ($job) {
              // İşleme başlamadan önce durumu 'processing' yap ve locked_at zamanını güncelle
              DB::table('jobs')
                  ->where('id', $job->id)
                  ->update([
                      'status' => Job::STATUS_PROCESSING,
                      'locked_at' => Carbon::now(),
                  ]);

              // İşlem Yap (Burada payload işlenebilir)
              try {
                  $result = Sms::send($job->content, $job->contact, $job->subject);
                  if($result) {
                      // İşlem başarılı olursa durumu 'completed' yap
                      DB::table('jobs')
                          ->where('id', $job->id)
                          ->update(['status' => Job::STATUS_COMPLETED]);
                      Log::info($job->contact.' numarasına sms gönderildi.');
                  }else {
                      Log::error($job->contact.' numarasına sms gönderilemedi.');
                  }
              } catch (\Exception $e) {
                  // Hata olursa status 'waiting' olarak kalabilir veya loglama yapabilirsiniz
                  DB::table('jobs')
                      ->where('id', $job->id)
                      ->update(['status' => Job::STATUS_WAITING]);
                  Log::info("Hata: ".$e->getMessage());
              }
          }
      });


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
