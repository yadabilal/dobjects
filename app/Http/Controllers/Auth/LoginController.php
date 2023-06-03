<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\Basket;
use App\Model\Product;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Propaganistas\LaravelPhone\PhoneNumber;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'hesabim';
    protected $adminRedirectTo = 'admin';

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
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

    protected function sendFailedLoginResponse(Request $request)
    {
      throw ValidationException::withMessages([
        $this->username() => [trans('auth.failed.'.$this->username())],
      ]);
    }

    public function redirectTo(){

        $carts = Session::get('basket.items') ?: [];

        foreach ($carts as $cartId => $cart) {
            $product = Product::where('uuid', $cartId)->where('status', Product::STATUS_PUBLISH)->first();
            if($product) {
                Basket::add($product, $cart->quantity);
            }
        }

        Session::put('basket.items', []);
      if(auth()->user()->type == User::TYPE_ADMIN) {
        return $this->adminRedirectTo;
      }else {
        return $this->redirectTo;
      }

    }
}
