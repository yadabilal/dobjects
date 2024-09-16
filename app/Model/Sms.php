<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Sms extends Model
{

  const musteri_no = '39628';
  const kullainici_adi = '905346326393';
  const sifre = '93IgX963';
  const orginator = '8505906812';
  const tur = 'Normal';
  const post_url = 'http://panel.vatansms.com/panel/smsgonder1Npost.php';

  const SUBJECT_CONFIRM = 'CONFIRM';
  public static function send($message, $phone='', $subject=null) {

    $phone= $phone ? : \auth()->user()->phone;
    header('Content-Type: text/html; charset=utf-8');

    $xmlString='';
    $xmlString.='data=<sms>
                    <kno>'. self::musteri_no .'</kno>
                    <kulad>'. self::kullainici_adi .'</kulad>
                    <sifre>'.self::sifre .'</sifre>
                    <gonderen>'.  self::orginator .'</gonderen>
                    <mesaj>'. $message .'</mesaj>
                    <numaralar>'. $phone.'</numaralar>
                    <tur>'. self::tur .'</tur>
                    </sms>';

    $data =  $xmlString;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, self::post_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = true;
    if($response) {
      Log::debug('SMS SONUCLARI -->'.$phone.' - '. $response.' --- MESAJ: '.$message);
    }

    if (strpos($response, ':Zaman düzeltilip gönderildi') === false && strpos($response, ':Gonderildi') === false) {
      Log::info($phone.' --> Onay kodu gönderilemedi');
      $result = false;
      $job['type'] =Job::TYPE_SMS;
      $job['subject'] =$subject;
      $job['contact'] = $phone;
      $job['content'] =$message;
      Job::create($job);
    }

    return $result;
  }
  // Kayıt Smsi
  public static function confirm_code($user) {
    $message= 'deekobjects.com onay kodun '.$user->phone_code.' . Bu kodu kimseyle paylaşma!';
    self::send($message,$user->phone,self::SUBJECT_CONFIRM);
  }

  public static function new_order($order) {
    $address = $order->address()->first();
    $data['type'] =Job::TYPE_SMS;
    $data['contact'] = $order->user ? $order->user->phone : $address->phone;
    $data['content'] ='deekobjects.com ekibi olarak siparişlerini aldık. Sipariş durumunu profil sayfandan takip edebilirsin.';

    if(!$order->user) {
        $data['content'] ='deekobjects.com ekibi olarak siparişlerini aldık. Sipariş durumunu linkten takip edebilirsiniz: '.route('guest.shop.result', ['uuid' => $order->uuid]);
    }
    Job::create($data);
  }

    public static function cancel_order($order) {
        $address = $order->address()->first();
        $data['type'] =Job::TYPE_SMS;
        $data['contact'] = $order->user ? $order->user->phone : $address->phone;
        $data['content'] ='deekobjects.com ekibi olarak '.$order->number.' numaralı siparişini iptal ettik. Daha fazla detay için deekobjects.com.';
        Job::create($data);
    }

  public static function order_cargo($order) {
      $address = $order->address()->first();
      $data['type'] =Job::TYPE_SMS;
      $data['contact'] = $order->user ? $order->user->phone : $address->phone;
      $data['content'] ='deekobjects.com ekibi olarak siparişini kargoladık. Sipariş durumunu profil sayfandan takip edebilirsin.';
      if(!$order->user) {
          $data['content'] ='deekobjects.com ekibi olarak siparişini kargoladık. Sipariş durumunu linkten takip edebilirsiniz: '.route('guest.shop.result', ['uuid' => $order->uuid]);
      }
    Job::create($data);
  }

  public static function order_billing($order) {
      $address = $order->address()->first();
      $data['type'] =Job::TYPE_SMS;
      $data['contact'] = $order->user ? $order->user->phone : $address->phone;
    $data['content'] ='deekobjects.com ekibi olarak faturanı oluşturduk. Faturana profil sayfandan ulaşabilirsin.';
      if(!$order->user) {
          $data['content'] ='deekobjects.com ekibi olarak faturanı oluşturduk. Sipariş durumunu linkten takip edebilirsiniz: '.route('guest.shop.result', ['uuid' => $order->uuid]);
      }
    Job::create($data);
  }

  public static function forgot_password($new_password, $phone) {
    $data['type'] =Job::TYPE_SMS;
    $data['contact'] = $phone;
    $data['content'] ='deekobjects.com için yeni şifren: "'.$new_password.'". Güvenliğin için bu şifreyi kimseyle paylaşmamalısın.';

    Job::create($data);
  }

  public static function in_basket_error($user) {
    $data['type'] =Job::TYPE_SMS;
    $data['contact'] = $user->phone;
    $data['content'] ="deekobjects.com 'da en özgün mobilyaları satın alırken bir sorun yaşadığını fark ettik. Teknik problem için özür diler, düzelttiğimizi bildiririz.";

    Job::create($data);
  }
}
