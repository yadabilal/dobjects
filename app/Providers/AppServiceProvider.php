<?php

namespace App\Providers;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Propaganistas\LaravelPhone\PhoneNumber;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      // Boşluk olmasını kontrol eder
      Validator::extend('without_spaces', function($attr, $value){
        return preg_match('/^\S*$/u', $value);
      });
      // Cinsiyeti Kontrol eder
      Validator::extend('gender', function($attr, $value){
        return !in_array($value, User::genders());
      });
      // Tc Kimlik No KOntrol eder
      Validator::extend('identify', function($attr, $value, $parameters, $validator){
        $data = $validator->getData();
        $user = Auth::user();
        $name = @$data['name'] ? : $user->name;
        $surname = @$data['surname'] ? : $user->surname;
        $birth_date = @$data['birth_date'] ? : $user->birth_date;
        if($name && $surname && $birth_date && $value) {
          $client = new \SoapClient("https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?WSDL");
          try {
            $birth_year = Carbon::parse($birth_date)->format('Y');
            $result = $client->TCKimlikNoDogrula([
              'TCKimlikNo' => $value,
              'Ad' => $name,
              'Soyad' => $surname,
              'DogumYili' => $birth_year
            ]);
            if($result->TCKimlikNoDogrulaResult) {
              $user->update(['birth_date' =>$birth_date, 'identify'=>$value]);
            }

            return $result->TCKimlikNoDogrulaResult;
          } catch (\Exception $e) {
            Log::info($e->faultstring);
          }
        }
        return false;
      });
      // Girilen Şifreler Birbirinin aynısı mı diye kontrol et
      Validator::extend('confirm_password', function($attr, $value, $parameters, $validator){
        $data = $validator->getData();
        // Yeni ve Şifre Tekrar Aynı mı diye kontrol et
        if($value && @$data['new_password'] && $value==$data['new_password']) {
          return true;
        }
        return false;
      });
      Validator::extend('now_password', function($attr, $value, $parameters){
        // Yeni ve Şifre Tekrar Aynı mı diye kontrol et
        if($value && Hash::check($value, $parameters[0])) {
          return true;
        }
        return false;
      });
      Validator::extend('phone_code', function($attr, $value, $parameters){
        // Yeni ve Şifre Tekrar Aynı mı diye kontrol et
        if(@$parameters[0]) {
          $user = User::find($parameters[0]);
        }else {
          $user = Auth::user();
        }
        if($value && $value==$user->phone_code) {
          return true;
        }
        return false;
      });

      Validator::extend('phone_custom_unique', function($attr, $value, $parameters){
        // Yeni ve Şifre Tekrar Aynı mı diye kontrol et

        if($value) {
          $phone = PhoneNumber::make($value, 'TR')->formatForCountry('TR');
          $user_id = auth()->id();
          $model = User::where('phone' , $phone);
          if($user_id) {
            $model->where('id', '!=', $user_id);
          }
          $model = $model->first();
           return $model ? false : true;
        }
        return false;
      });

    }
}
