<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
    /*$data['type'] =Job::TYPE_SMS;
    $data['subject'] =self::SUBJECT_CONFIRM;
    $data['contact'] = $user->phone;
    $data['content'] ='birkitapbul.com onay kodun '.$user->phone_code.' . Bu kodu kimseyle paylaşma!';
    Job::create($data);*/

    $message= 'deekobjects.com onay kodun '.$user->phone_code.' . Bu kodu kimseyle paylaşma!';
    self::send($message,$user->phone,self::SUBJECT_CONFIRM);
  }

  public static function completed_order($order) {
    $data['type'] =Job::TYPE_SMS;
    $data['contact'] = $order->receiver->phone;
    $data['content'] ='deekobjects.com dan istediğin '.$order->book->full_name().' adlı kitap eline ulaştıysa giriş yaparak kitabın eline ulaştığını bildir. Detay için giriş yap.';
    Job::create($data);
  }

  public static function new_order($order) {
    $data['type'] =Job::TYPE_SMS;
    $data['contact'] = $order->sender->phone;
    $data['content'] ='deekobjects.com kullanıcısı senden '.$order->book->full_name().' kitabını istedi. En geç '.$order->last_send_at().' tarihinde kargola. Detay için giriş yap.';
    Job::create($data);
  }

  public static function order_cargo($order) {
    $data['type'] =Job::TYPE_SMS;
    $data['contact'] = $order->receiver->phone;
    $data['content'] ='deekobjects.com kullanıcısı istediğin '.$order->book->full_name().' kitabı kargoladı. İstediğin kitap eline ulaştığında sosyal medyalarda bizi (@birkitapbul) etiketlemeyi, takip etmeyi ve paylaşmayı unutma:) Detay için giriş yap.';
    Job::create($data);
  }

  public static function cancel_order($order) {
    $data['type'] =Job::TYPE_SMS;
    $data['contact'] = $order->receiver->phone;
    $data['content'] ='deekobjects.com kullanıcısı istediğin '.$order->book->full_name().' kitabın gönderimini iptal etti. Aradığın ücretsiz kitap başka kullanıcılar da olabilir. Detay için giriş yap.';
    Job::create($data);
  }

  public static function forgot_password($new_password, $phone) {
    $data['type'] =Job::TYPE_SMS;
    $data['contact'] = $phone;
    $data['content'] ='deekobjects.com için yeni şifren: "'.$new_password.'". Güvenliğin için bu şifreyi kimseyle paylaşmamalısın.';

    Job::create($data);
  }

  public static function order_last_one_day($order) {
    $data['type'] =Job::TYPE_SMS;
    $data['contact'] = $order->sender->phone;
    $data['content'] ='deekobjects.com kullanıcısının istediği '.$order->book->name.' adlı kitabı göndermen için yarın son gün! Detay için giriş yap!';

    Job::create($data);
  }
  public static function order_last_day($order) {
    $data['type'] =Job::TYPE_SMS;
    $data['contact'] = $order->sender->phone;
    $data['content'] ='deekobjects.com kullanıcısının istediği '.$order->book->name.' adlı kitabı göndermen için bugün son gün! Detay için giriş yap!';

    Job::create($data);
  }

  public static function not_completed_user($user) {
    $data['type'] =Job::TYPE_SMS;
    $data['contact'] = $user->phone;
    $data['content'] ='deekobjects.com üyeliğini tamamlamadın. Bir şeyler ters gitmişse üzgünüz. Şimdi en özgün tasarımlı mobilyalara sahip olmak için tekrar denemelisin.';

    Job::create($data);
  }

  public static function doesnt_book($user) {
    $data['type'] =Job::TYPE_SMS;
    $data['contact'] = $user->phone;
    $data['content'] ='birkitapbul.com ile okuduğun ya da okumayı düşünmediğin kitapları ekleyerek okurların kitap bulmalarına yardımcı olabilir ya da sen ücretsiz kitaplar isteyebilirsin. Detay için giriş yap.';

    Job::create($data);
  }

  public static function in_basket_error($user) {
    $data['type'] =Job::TYPE_SMS;
    $data['contact'] = $user->phone;
    $data['content'] ="deekobjects.com 'da en özgün mobilyaları satın alırken bir sorun yaşadığını fark ettik. Teknik problem için özür diler, düzelttiğimizi bildiririz.";

    Job::create($data);
  }
}
