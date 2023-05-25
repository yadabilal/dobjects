<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\Base;
use App\Model\Sms;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
  
    protected $username;
    public function __construct()
    {
      $this->username = $this->findUsername();
    }
  
    public function showLinkRequestForm()
    {
      return view('auth.passwords.phone');
    }
  
    public function sendResetPassword(Request $request) {
      $inputs = $request->all();
      $validator= $this->validateUser($request);
      
      if ($validator->fails()) {
        return redirect('sifremi-unuttum')
          ->withErrors($validator)
          ->withInput();
      }
      
      // Başarılıysa Sms Yolla
      $new_password = rand(100000, 999999);
      $user = User::where($this->username(), $inputs[$this->username()])->whereNotNull('phone_verified_at')->first();
      $user->update(['password' => User::hash_pasword($new_password)]);
      Sms::forgot_password($new_password, $user->phone);
  
      return redirect('giris-yap')->with('success_message',  'Harika! Şifren sıfırlandı. Artık sms ile gelen yeni şifren ile giriş yapabilirsin.');
      
    }
    
    protected function validateUser(Request $request) {
      $inputs = Base::js_xss($request);

      $validator = Validator::make($inputs, [
        $this->username() => 'required|max:50|min:3'
      ]);

      $validator->after(function ($validator) use ($inputs){
        $user = User::where($this->username(), $inputs[$this->username()])->whereNotNull('phone_verified_at')->first();
        if (!$user) {
          $validator->errors()->add($this->username(), 'Girdiğin '.($this->username()== "username" ? 'kullanıcı adı': 'telefon numarası').' geçersiz.');
        }
      });
      
      return $validator;
      
    }
  
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function findUsername()
    {
      $login = request()->input('login');
  
      $rules = [
        'login' => 'phone:TR|regex:/(5)[0-9]/|not_regex:/[a-z]/|',
      ];
      $validator = Validator::make(request()->all(), $rules);
      $errors = $validator->getMessageBag()->toArray();

      if(@$errors['login']) {
        $fieldType = 'username';
    
      }else {
        $fieldType = 'phone';
        if($login) {
          $login = PhoneNumber::make($login, 'TR')->formatForCountry('TR');
        }
      }
      
      request()->merge([$fieldType => $login]);
      
      return $fieldType;
    }
    
    /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
      return $this->username;
    }
  
}
